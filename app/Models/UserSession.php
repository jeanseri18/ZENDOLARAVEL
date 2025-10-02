<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    use HasFactory;

    protected $table = 'user_sessions';
    protected $primaryKey = 'session_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'location_country',
        'location_city',
        'is_active',
        'last_activity',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_activity' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns this session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope for active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope for expired sessions.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for sessions by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if session is expired.
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if session is active and not expired.
     */
    public function isValid()
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Terminate the session.
     */
    public function terminate()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Update last activity.
     */
    public function updateActivity()
    {
        $this->update(['last_activity' => now()]);
    }

    /**
     * Get device icon based on device type.
     */
    public function getDeviceIconAttribute()
    {
        return match($this->device_type) {
            'mobile' => 'phone',
            'tablet' => 'tablet',
            'desktop' => 'desktop',
            default => 'device'
        };
    }

    /**
     * Get formatted device type.
     */
    public function getFormattedDeviceTypeAttribute()
    {
        return match($this->device_type) {
            'mobile' => 'Mobile',
            'tablet' => 'Tablette',
            'desktop' => 'Ordinateur',
            default => 'Inconnu'
        };
    }

    /**
     * Get browser name from user agent.
     */
    public function getBrowserAttribute()
    {
        $userAgent = $this->user_agent;
        
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        } elseif (str_contains($userAgent, 'Edge')) {
            return 'Edge';
        }
        
        return 'Autre';
    }

    /**
     * Clean up expired sessions.
     */
    public static function cleanupExpired()
    {
        return static::expired()->delete();
    }
}