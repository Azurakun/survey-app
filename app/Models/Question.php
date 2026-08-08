<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'survey_id',
        'tipe_pertanyaan',
        'teks_pertanyaan',
        'opsi_jawaban',
        'wajib_diisi',
        'urutan',
    ];

    protected $casts = [
        'wajib_diisi' => 'boolean',
        'urutan'      => 'integer',
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

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Decode opsi_jawaban JSON as array. Returns [] if null.
     */
    public function getParsedOptionsAttribute(): array
    {
        if (!$this->opsi_jawaban) return [];
        return json_decode($this->opsi_jawaban, true) ?: [];
    }
}
