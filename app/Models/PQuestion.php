<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PQuestion extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $table = "p_questions";

    protected $fillable = [
        'p_exam_id',
        'question_text',
        'question_image',
        'question_type',
        'mark',
        'order',
        'explanation',
        'hint',
    ];

    public function pExam(): BelongsTo
    {
        return $this->belongsTo(PExam::class, 'p_exam_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(PQuestionOption::class, 'p_question_id');
    }
}