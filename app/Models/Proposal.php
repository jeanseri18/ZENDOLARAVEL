<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';
    protected $primaryKey = 'proposal_id';

    protected $fillable = [
        'package_id',
        'traveler_id',
        'proposed_price',
        'original_price',
        'discount_percentage',
        'estimated_pickup_date',
        'estimated_delivery_date',
        'message',
        'terms_conditions',
        'status',
        'expires_at',
        'accepted_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'estimated_pickup_date' => 'date',
        'estimated_delivery_date' => 'date',
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the package this proposal is for.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }

    /**
     * Get the traveler who made this proposal.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traveler_id', 'user_id');
    }

    /**
     * Scope for pending proposals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for accepted proposals.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for active proposals (not expired).
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })->whereIn('status', ['pending']);
    }

    /**
     * Check if proposal is expired.
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Accept the proposal.
     */
    public function accept()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    /**
     * Reject the proposal.
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Withdraw the proposal.
     */
    public function withdraw()
    {
        $this->update([
            'status' => 'withdrawn',
        ]);
    }

    /**
     * Calculate savings amount.
     */
    public function getSavingsAttribute()
    {
        if ($this->original_price && $this->proposed_price) {
            return $this->original_price - $this->proposed_price;
        }
        return 0;
    }

    /**
     * Calculate actual discount percentage.
     */
    public function getActualDiscountPercentageAttribute()
    {
        if ($this->original_price && $this->proposed_price && $this->original_price > 0) {
            return round((($this->original_price - $this->proposed_price) / $this->original_price) * 100, 2);
        }
        return 0;
    }

    /**
     * Check if proposal can be modified.
     */
    public function canBeModified()
    {
        return in_array($this->status, ['pending']) && !$this->isExpired();
    }
}