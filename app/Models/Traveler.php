<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traveler extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'traveler_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_model',
        'vehicle_year',
        'license_plate',
        'vehicle_color',
        'max_weight',
        'max_volume',
        'insurance_number',
        'driver_license',
        'is_verified',
        'is_active',
        'verification_status',
        'notes'
    ];

    /**
     * Get the user that owns the traveler profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the packages assigned to this traveler.
     */
    public function packages()
    {
        return $this->hasMany(Package::class, 'traveler_id', 'user_id');
    }

    /**
     * Get the proposed trips for this traveler.
     */
    public function proposedTrips()
    {
        return $this->hasMany(ProposedTrip::class, 'traveler_id', 'traveler_id');
    }
}
