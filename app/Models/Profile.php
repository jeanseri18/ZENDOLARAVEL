<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';
    protected $primaryKey = 'profile_id';

    protected $fillable = [
        'user_id',
        'badge',
        'total_packages_sent',
        'successful_deliveries',
        'canceled_packages',
        'reliability_percentage',
        'joined_date',
        'activity_zone',
    ];

    protected $casts = [
        'total_packages_sent' => 'integer',
        'successful_deliveries' => 'integer',
        'canceled_packages' => 'integer',
        'reliability_percentage' => 'decimal:2',
        'joined_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the success rate percentage.
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_packages_sent == 0) {
            return 0;
        }
        
        return round(($this->successful_deliveries / $this->total_packages_sent) * 100, 2);
    }

    /**
     * Get the cancellation rate percentage.
     */
    public function getCancellationRateAttribute()
    {
        if ($this->total_packages_sent == 0) {
            return 0;
        }
        
        return round(($this->canceled_packages / $this->total_packages_sent) * 100, 2);
    }

    /**
     * Update package statistics.
     */
    public function updatePackageStats($status)
    {
        switch ($status) {
            case 'delivered':
                $this->increment('successful_deliveries');
                break;
            case 'cancelled':
                $this->increment('canceled_packages');
                break;
        }
        
        $this->increment('total_packages_sent');
        $this->updateReliabilityPercentage();
    }

    /**
     * Update reliability percentage based on current stats.
     */
    public function updateReliabilityPercentage()
    {
        if ($this->total_packages_sent > 0) {
            $this->reliability_percentage = ($this->successful_deliveries / $this->total_packages_sent) * 100;
            $this->save();
        }
    }

    /**
     * Get badge color for display.
     */
    public function getBadgeColorAttribute()
    {
        return match($this->badge) {
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'platinum' => '#E5E4E2',
            default => '#CD7F32'
        };
    }
}