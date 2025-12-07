<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class ExamResult  extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Specify that the primary key is a UUID.
     *
     * @var string
     */
    protected $keyType = 'string'; // Change from 'uuid' to 'string'

    /**
     * Disable auto-incrementing since we are using UUIDs.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot function to handle UUID generation.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate a UUID when creating a new Admin record
        static::creating(function ($admin) {
            if (!$admin->getKey()) {
                $admin->{$admin->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $table = "exam_results";  

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
       protected $fillable = [
            'exam_id',
            'student_id',
            'started_at',
            'submitted_at',
            'total_score',
            'percentage',
            'status',
        ];

        protected $casts = [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];

        // Relationships
        public function exam()
        {
            return $this->belongsTo(Exam::class);
        }

        public function student()
        {
            return $this->belongsTo(Student::class);
        }

        public function studentAnswers()
        {
            return $this->hasMany(StudentAnswer::class);
        }

}
