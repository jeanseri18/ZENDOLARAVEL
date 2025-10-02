<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    use HasFactory;

    protected $table = 'promo_codes';
    protected $primaryKey = 'promo_id';

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_count',
        'user_limit',
        'is_active',
        'valid_from',
        'valid_until',
        'applicable_to',
        'created_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'user_limit' => 'integer',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this promo code.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    /**
     * Get the usage records for this promo code.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(PromoCodeUsage::class, 'promo_id', 'promo_id');
    }

    /**
     * Scope for active promo codes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('valid_from', '<=', now())
                    ->where('valid_until', '>=', now());
    }

    /**
     * Scope for expired promo codes.
     */
    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now());
    }

    /**
     * Scope by code.
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    /**
     * Check if promo code is valid.
     */
    public function isValid()
    {
        return $this->is_active 
            && $this->valid_from <= now() 
            && $this->valid_until >= now()
            && ($this->usage_limit === null || $this->usage_count < $this->usage_limit);
    }

    /**
     * Check if promo code is expired.
     */
    public function isExpired()
    {
        return $this->valid_until < now();
    }

    /**
     * Check if usage limit is reached.
     */
    public function isUsageLimitReached()
    {
        return $this->usage_limit !== null && $this->usage_count >= $this->usage_limit;
    }

    /**
     * Check if user can use this promo code.
     */
    public function canBeUsedByUser($userId)
    {
        if (!$this->isValid()) {
            return false;
        }

        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        return $userUsageCount < $this->user_limit;
    }

    /**
     * Calculate discount amount for given order amount.
     */
    public function calculateDiscount($orderAmount)
    {
        if ($orderAmount < $this->min_order_amount) {
            return 0;
        }

        $discount = match($this->discount_type) {
            'percentage' => ($orderAmount * $this->discount_value) / 100,
            'fixed_amount' => $this->discount_value,
            default => 0
        };

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return round($discount, 2);
    }

    /**
     * Use the promo code (increment usage count).
     */
    public function use($userId, $packageId = null, $transactionId = null, $discountApplied = null)
    {
        if (!$this->canBeUsedByUser($userId)) {
            throw new \Exception('Ce code promo ne peut pas être utilisé par cet utilisateur.');
        }

        $this->increment('usage_count');

        return PromoCodeUsage::create([
            'promo_id' => $this->promo_id,
            'user_id' => $userId,
            'package_id' => $packageId,
            'transaction_id' => $transactionId,
            'discount_applied' => $discountApplied ?? $this->calculateDiscount(0),
        ]);
    }

    /**
     * Get remaining usage count.
     */
    public function getRemainingUsageAttribute()
    {
        if ($this->usage_limit === null) {
            return null; // Unlimited
        }
        
        return max(0, $this->usage_limit - $this->usage_count);
    }

    /**
     * Get formatted discount.
     */
    public function getFormattedDiscountAttribute()
    {
        return match($this->discount_type) {
            'percentage' => $this->discount_value . '%',
            'fixed_amount' => number_format($this->discount_value, 0) . ' XOF',
            default => $this->discount_value
        };
    }

    /**
     * Mutator to ensure code is always uppercase.
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }
}