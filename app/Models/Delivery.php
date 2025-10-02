<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'delivery_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_id',
        'traveler_id',
        'delivery_type',
        'start_time',
        'end_time',
        'pickup_location',
        'delivery_location',
        'actual_delivery_time',
        'customs_declaration',
        'flight_ticket_path',
        'commission_fee',
        'gps_tracking_data',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'commission_fee' => 'decimal:2',
        'gps_tracking_data' => 'json'
    ];

    /**
     * Get the package associated with this delivery.
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }

    /**
     * Get the traveler assigned to this delivery.
     */
    public function traveler()
    {
        return $this->belongsTo(Traveler::class, 'traveler_id', 'traveler_id');
    }

    /**
     * Scope for filtering by delivery type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('delivery_type', $type);
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the status label in French.
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'to_pickup' => 'À récupérer',
            'in_transit' => 'En transit',
            'delivered' => 'Livré',
            'delayed' => 'Retardé',
            'canceled' => 'Annulé'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get the delivery type label in French.
     */
    public function getDeliveryTypeLabelAttribute()
    {
        $labels = [
            'urban' => 'Urbaine',
            'intercity' => 'Intercité',
            'international' => 'Internationale'
        ];

        return $labels[$this->delivery_type] ?? $this->delivery_type;
    }

    /**
     * Check if delivery is international.
     */
    public function isInternational()
    {
        return $this->delivery_type === 'international';
    }

    /**
     * Check if delivery is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'delivered';
    }
}