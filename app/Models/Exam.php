<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Exam extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($exam) {
            if (!$exam->getKey()) {
                $exam->{$exam->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $table = "exams";

    protected $fillable = [
        'school_id',
        'class_id',
        'title',
        'description',
        'subject',
        'duration',
        'total_questions',
        'passing_score',
        'instructions',
        'is_published',
        'show_results',
        'randomize_questions',
        'show_one_question_at_time',
        'exam_date_time',
        'department',
        'session',
        'term',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_results' => 'boolean',
        'randomize_questions' => 'boolean',
        'show_one_question_at_time' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchClass::class, 'class_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject', 'id');
    }

}