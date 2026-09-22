<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Survey extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'judul',
        'deskripsi',
        'status',
        'accepting_responses',
        'closed_at',
        'custom_closed_message',
        'limit_one_response',
        'tanggal_mulai',
        'tanggal_selesai',
        'ai_analysis',
        'ai_analyzed_at',
    ];

    protected $casts = [
        'accepting_responses' => 'boolean',
        'closed_at'           => 'datetime',
        'limit_one_response'  => 'boolean',
        'tanggal_mulai'       => 'date',
        'tanggal_selesai'     => 'date',
        'ai_analysis'         => 'array',
        'ai_analyzed_at'      => 'datetime',
    ];

    /**
     * Check if survey is currently accepting responses (GMT+7 Asia/Jakarta check)
     */
    public function isAcceptingResponses(): bool
    {
        if ($this->status !== 'PUBLISHED') {
            return false;
        }

        if (array_key_exists('accepting_responses', $this->attributes) && !$this->accepting_responses) {
            return false;
        }

        if (!empty($this->closed_at)) {
            $nowWib = \Carbon\Carbon::now('Asia/Jakarta');
            $closedAtWib = \Carbon\Carbon::parse($this->closed_at)->timezone('Asia/Jakarta');
            if ($nowWib->greaterThanOrEqualTo($closedAtWib)) {
                return false;
            }
        }

        $nowWib = \Carbon\Carbon::now('Asia/Jakarta');
        if ($this->tanggal_mulai && $this->tanggal_mulai->timezone('Asia/Jakarta')->startOfDay()->isFuture()) {
            return false;
        }

        if ($this->tanggal_selesai && $this->tanggal_selesai->timezone('Asia/Jakarta')->endOfDay()->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get customized or default closed message
     */
    public function getClosedMessage(): string
    {
        if (!empty($this->custom_closed_message)) {
            return $this->custom_closed_message;
        }

        $nowWib = \Carbon\Carbon::now('Asia/Jakarta');

        if ($this->tanggal_mulai && $this->tanggal_mulai->timezone('Asia/Jakarta')->startOfDay()->isFuture()) {
            return "Survey ini belum dibuka. Survey akan dimulai pada " . $this->tanggal_mulai->timezone('Asia/Jakarta')->format('d M Y') . ".";
        }

        if (!empty($this->closed_at) && $nowWib->greaterThanOrEqualTo(\Carbon\Carbon::parse($this->closed_at)->timezone('Asia/Jakarta'))) {
            return "Survey ini telah otomatis ditutup sesuai jadwal pada " . \Carbon\Carbon::parse($this->closed_at)->timezone('Asia/Jakarta')->format('d M Y H:i') . " WIB.";
        }

        if ($this->tanggal_selesai && $this->tanggal_selesai->timezone('Asia/Jakarta')->endOfDay()->isPast()) {
            return "Periode pelaksanaan survey ini telah berakhir pada " . $this->tanggal_selesai->timezone('Asia/Jakarta')->format('d M Y') . ".";
        }

        return "Survey ini telah ditutup oleh pemilik survey dan tidak lagi menerima tanggapan baru.";
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('urutan', 'asc');
    }

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }

    /**
     * Determine effective category status (DRAFT, PUBLISHED, or CLOSED)
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status === 'DRAFT') {
            return 'DRAFT';
        }
        if ($this->status === 'CLOSED' || (array_key_exists('accepting_responses', $this->attributes) && !$this->accepting_responses)) {
            return 'CLOSED';
        }
        if (!empty($this->closed_at) && \Carbon\Carbon::now('Asia/Jakarta')->greaterThanOrEqualTo(\Carbon\Carbon::parse($this->closed_at)->timezone('Asia/Jakarta'))) {
            return 'CLOSED';
        }
        if ($this->tanggal_selesai && $this->tanggal_selesai->lt(now()->startOfDay())) {
            return 'CLOSED';
        }
        return 'PUBLISHED';
    }

    /**
     * Determine the active period status badge label.
     */
    public function getPeriodStatusAttribute(): string
    {
        if ($this->effective_status !== 'PUBLISHED') {
            return '';
        }
        $today = now()->startOfDay();
        if ($this->tanggal_mulai && $this->tanggal_mulai->gt($today)) {
            return 'BELUM_MULAI';
        }
        if ($this->tanggal_selesai && $this->tanggal_selesai->lt($today)) {
            return 'BERAKHIR';
        }
        return 'AKTIF';
    }
}
