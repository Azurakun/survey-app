<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentAnalysis extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'judul',
        'nama_file_asli',
        'file_path',
        'file_type',
        'file_size',
        'total_baris_halaman',
        'headers',
        'preview_data',
        'extracted_text',
        'ai_analysis',
        'ai_analyzed_at',
        'chat_history',
    ];

    protected $casts = [
        'headers'        => 'array',
        'preview_data'   => 'array',
        'ai_analysis'    => 'array',
        'chat_history'   => 'array',
        'ai_analyzed_at' => 'datetime',
    ];

    protected static function booted()
    {
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
}
