<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Answer extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'respondent_id',
        'question_id',
        'jawaban',
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

    public function respondent()
    {
        return $this->belongsTo(Respondent::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Accessor alias for jawaban column
     */
    public function getNilaiJawabanAttribute()
    {
        return $this->jawaban;
    }
}
