<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PExam extends Model
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

    protected $table = "p_exams";

    protected $fillable = [
        'p_class_id',
        'title',
        'subject',
        'department_id',
        'description',
        'duration',
        'total_questions',
        'passing_score',
        'session',
        'term',
        'randomize_questions',
        'show_one_question_at_time',
        'show_results',
        'show_explanations',
        'instructions',
        'is_published',
    ];

    protected $casts = [
        'randomize_questions' => 'boolean',
        'show_one_question_at_time' => 'boolean',
        'show_results' => 'boolean',
        'show_explanations' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function pClass(): BelongsTo
    {
        return $this->belongsTo(PClass::class, 'p_class_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(PQuestion::class, 'p_exam_id');
    }
}