<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PStudentAttempt extends Model
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

    protected $table = "p_student_attempts";

    protected $fillable = [
        'student_id',
        'p_exam_id',
        'score',
        'total_marks',
        'percentage',
        'passed',
        'started_at',
        'completed_at',
        'time_spent',
        'is_completed',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'is_completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function pExam(): BelongsTo
    {
        return $this->belongsTo(PExam::class, 'p_exam_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PStudentAnswer::class, 'p_student_attempt_id');
    }
}