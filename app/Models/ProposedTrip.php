<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ProposedTrip extends Model
{
    use HasFactory;

    protected $table = 'proposed_trips';
    protected $primaryKey = 'trip_id';

    protected $fillable = [
        'traveler_id',
        'origin_city',
        'destination_city',
        'trip_date',
        'estimated_time',
        'vehicle_type',
        'max_packages',
        'max_weight_kg',
        'accepted_package_types',
        'requires_secure_payment',
        'express_delivery',
        'vehicle_photo',
        'status',
    ];

    protected $casts = [
        'trip_date' => 'date',
        'estimated_time' => 'datetime',
        'max_packages' => 'integer',
        'max_weight_kg' => 'decimal:2',
        'requires_secure_payment' => 'boolean',
        'express_delivery' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the traveler who proposed this trip.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class, 'traveler_id', 'traveler_id');
    }

    /**
     * Get the user who owns this trip (through traveler).
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Traveler::class,
            'traveler_id', // Foreign key on travelers table
            'user_id',     // Foreign key on users table
            'traveler_id', // Local key on proposed_trips table
            'user_id'      // Local key on travelers table
        );
    }

    /**
     * Scope for active trips.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed trips.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for upcoming trips.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('trip_date', '>=', now()->toDateString())
                    ->where('status', 'active');
    }

    /**
     * Scope for trips by route.
     */
    public function scopeByRoute($query, $origin, $destination)
    {
        return $query->where('origin_city', $origin)
                    ->where('destination_city', $destination);
    }

    /**
     * Scope for trips by vehicle type.
     */
    public function scopeByVehicleType($query, $vehicleType)
    {
        return $query->where('vehicle_type', $vehicleType);
    }

    /**
     * Check if trip is in the past.
     */
    public function isPast()
    {
        return $this->trip_date->isPast();
    }

    /**
     * Check if trip is today.
     */
    public function isToday()
    {
        return $this->trip_date->isToday();
    }

    /**
     * Get accepted package types as array.
     */
    public function getAcceptedPackageTypesArrayAttribute()
    {
        return $this->accepted_package_types ? explode(',', $this->accepted_package_types) : [];
    }

    /**
     * Set accepted package types from array.
     */
    public function setAcceptedPackageTypesArrayAttribute($value)
    {
        $this->accepted_package_types = is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * Complete the trip.
     */
    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Cancel the trip.
     */
    public function cancel()
    {
        $this->update(['status' => 'canceled']);
    }

    /**
     * Get formatted vehicle type.
     */
    public function getFormattedVehicleTypeAttribute()
    {
        return match($this->vehicle_type) {
            'moto' => 'Moto',
            'car' => 'Voiture',
            'bus' => 'Bus',
            'plane' => 'Avion',
            default => ucfirst($this->vehicle_type)
        };
    }
}