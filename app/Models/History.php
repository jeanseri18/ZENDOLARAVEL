<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    use HasFactory;

    protected $table = 'histories';
    protected $primaryKey = 'history_id';

    public $timestamps = false; // Only has created_at

    protected $fillable = [
        'package_id',
        'user_id',
        'action_type',
        'details',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the package related to this history entry.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope for specific action types.
     */
    public function scopeByAction($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope for specific package.
     */
    public function scopeForPackage($query, $packageId)
    {
        return $query->where('package_id', $packageId);
    }

    /**
     * Scope for specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Create a history entry.
     */
    public static function createEntry($packageId, $userId, $actionType, $details = null)
    {
        return static::create([
            'package_id' => $packageId,
            'user_id' => $userId,
            'action_type' => $actionType,
            'details' => $details,
            'created_at' => now(),
        ]);
    }

    /**
     * Get formatted action description.
     */
    public function getActionDescriptionAttribute()
    {
        return match($this->action_type) {
            'published' => 'Colis publié',
            'modified' => 'Colis modifié',
            'deleted' => 'Colis supprimé',
            'accepted' => 'Proposition acceptée',
            'rejected' => 'Proposition rejetée',
            'delivered' => 'Colis livré',
            'evaluated' => 'Évaluation ajoutée',
            default => ucfirst($this->action_type)
        };
    }
}