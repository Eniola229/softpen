<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PStudentAnswer extends Model
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

    protected $table = "p_student_answers";

    protected $fillable = [
        'p_student_attempt_id',
        'p_question_id',
        'p_question_option_id',
        'answer_text',
        'is_correct',
        'marks_obtained',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(PStudentAttempt::class, 'p_student_attempt_id');
    }

    public function pQuestion(): BelongsTo
    {
        return $this->belongsTo(PQuestion::class, 'p_question_id');
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(PQuestionOption::class, 'p_question_option_id');
    }
}