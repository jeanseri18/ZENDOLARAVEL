<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCodeUsage extends Model
{
    use HasFactory;

    protected $table = 'promo_code_usage';
    protected $primaryKey = 'usage_id';

    public $timestamps = false; // Only has used_at

    protected $fillable = [
        'promo_id',
        'user_id',
        'package_id',
        'transaction_id',
        'discount_applied',
        'used_at',
    ];

    protected $casts = [
        'discount_applied' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    /**
     * Get the promo code that was used.
     */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class, 'promo_id', 'promo_id');
    }

    /**
     * Get the user who used the promo code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the package this usage is related to.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }

    /**
     * Get the transaction this usage is related to.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Scope for specific promo code.
     */
    public function scopeForPromoCode($query, $promoId)
    {
        return $query->where('promo_id', $promoId);
    }

    /**
     * Scope for specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific package.
     */
    public function scopeForPackage($query, $packageId)
    {
        return $query->where('package_id', $packageId);
    }

    /**
     * Scope for usage within date range.
     */
    public function scopeWithinDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('used_at', [$startDate, $endDate]);
    }

    /**
     * Get usage statistics for a promo code.
     */
    public static function getUsageStats($promoId)
    {
        $usages = static::forPromoCode($promoId)->get();
        
        return [
            'total_usage' => $usages->count(),
            'total_discount' => $usages->sum('discount_applied'),
            'unique_users' => $usages->unique('user_id')->count(),
            'average_discount' => $usages->avg('discount_applied'),
            'first_used' => $usages->min('used_at'),
            'last_used' => $usages->max('used_at'),
        ];
    }

    /**
     * Get user's usage history for promo codes.
     */
    public static function getUserUsageHistory($userId)
    {
        return static::byUser($userId)
                    ->with(['promoCode', 'package', 'transaction'])
                    ->orderBy('used_at', 'desc')
                    ->get();
    }

    /**
     * Check if user has used a specific promo code.
     */
    public static function hasUserUsedPromoCode($userId, $promoId)
    {
        return static::byUser($userId)
                    ->forPromoCode($promoId)
                    ->exists();
    }

    /**
     * Get total savings for a user from promo codes.
     */
    public static function getUserTotalSavings($userId)
    {
        return static::byUser($userId)->sum('discount_applied');
    }

    /**
     * Boot method to set used_at automatically.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->used_at) {
                $model->used_at = now();
            }
        });
    }
}