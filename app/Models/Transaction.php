<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Specify that the primary key is a UUID.
     *
     * @var string
     */
    protected $keyType = 'string';

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
        
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $table = "transactions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'category',
        'amount',
        'balance_before',
        'balance_after',
        'reference',
        'status',
        'payment_method',
        'course_id',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Transaction types
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    // Transaction categories
    const CATEGORY_COURSE_PURCHASE = 'course_purchase';
    const CATEGORY_WALLET_TOPUP = 'wallet_topup';
    const CATEGORY_WITHDRAWAL = 'withdrawal';
    const CATEGORY_COURSE_REFUND = 'course_refund';

    // Transaction statuses
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCredit($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    public function scopeDebit($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isCredit(): bool
    {
        return $this->type === self::TYPE_CREDIT;
    }

    public function isDebit(): bool
    {
        return $this->type === self::TYPE_DEBIT;
    }

    // Generate unique reference
    public static function generateReference(): string
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . time();
    }
}