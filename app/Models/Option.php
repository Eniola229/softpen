<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

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

    protected $table = "options"; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'option_text',
        'option_image',
        'is_correct',
        'order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // Relationships
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'selected_option_id');
    }

}
