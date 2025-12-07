<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($question) {
            if (!$question->getKey()) {
                $question->{$question->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $table = "questions"; 

    protected $fillable = [
        'exam_id',
        'question_text',
        'question_image',
        'question_type',
        'order',
        'mark',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class)->orderBy('order');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}