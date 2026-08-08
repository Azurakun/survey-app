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
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

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
     * Determine the active period status badge label.
     */
    public function getPeriodStatusAttribute(): string
    {
        if ($this->status !== 'PUBLISHED') {
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
