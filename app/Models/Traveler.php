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
        'driver_license',
        'max_weight_kg',
        'max_dimensions',
        'service_areas',
        'hourly_rate',
        'bio',
        'is_verified',
        'is_available',
        'rating',
        'total_deliveries',
        'departure_city',
        'arrival_city',
        'departure_date',
        'arrival_date',
        'departure_time',
        'arrival_time',
        'available_weight',
        'max_package_weight',
        'price_per_kg',
        'transport_type',
        'status',
        'is_online',
        'last_seen',
        'special_instructions',
        'accepts_fragile',
        'accepts_documents',
        'verification_required',
        'supported_delivery_types'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'vehicle_year' => 'integer',
        'max_weight_kg' => 'decimal:2',
        'service_areas' => 'json',
        'hourly_rate' => 'decimal:2',
        'is_verified' => 'boolean',
        'is_available' => 'boolean',
        'rating' => 'decimal:2',
        'total_deliveries' => 'integer',
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'available_weight' => 'decimal:2',
        'max_package_weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'is_online' => 'boolean',
        'last_seen' => 'datetime',
        'accepts_fragile' => 'boolean',
        'accepts_documents' => 'boolean',
        'verification_required' => 'boolean',
        'supported_delivery_types' => 'json'
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

    /**
     * Check if traveler supports a specific delivery type.
     */
    public function supportsDeliveryType($type)
    {
        return in_array($type, $this->supported_delivery_types ?? []);
    }

    /**
     * Get supported delivery types labels.
     */
    public function getSupportedDeliveryTypesLabelsAttribute()
    {
        $labels = [
            'urban' => 'Urbaine',
            'intercity' => 'Intercité',
            'international' => 'Internationale'
        ];

        return collect($this->supported_delivery_types ?? [])
            ->map(fn($type) => $labels[$type] ?? $type)
            ->toArray();
    }

    /**
     * Scope for travelers supporting specific delivery type.
     */
    public function scopeSupportsDeliveryType($query, $type)
    {
        return $query->whereJsonContains('supported_delivery_types', $type);
    }

    /**
     * Determine delivery type based on cities.
     */
    public static function determineDeliveryType($originCity, $destinationCity)
    {
        // Liste des pays pour déterminer si c'est international
        $countries = [
            'France' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille'],
            'Allemagne' => ['Berlin', 'Munich', 'Hambourg', 'Cologne', 'Francfort'],
            'Espagne' => ['Madrid', 'Barcelone', 'Valence', 'Séville', 'Bilbao'],
            'Italie' => ['Rome', 'Milan', 'Naples', 'Turin', 'Florence'],
            'Belgique' => ['Bruxelles', 'Anvers', 'Gand', 'Charleroi', 'Liège'],
            'Suisse' => ['Zurich', 'Genève', 'Bâle', 'Berne', 'Lausanne']
        ];

        $originCountry = null;
        $destinationCountry = null;

        // Déterminer le pays d'origine et de destination
        foreach ($countries as $country => $cities) {
            if (in_array($originCity, $cities)) {
                $originCountry = $country;
            }
            if (in_array($destinationCity, $cities)) {
                $destinationCountry = $country;
            }
        }

        // Si différents pays = international
        if ($originCountry && $destinationCountry && $originCountry !== $destinationCountry) {
            return 'international';
        }

        // Si même pays, vérifier si c'est urbain ou intercité
        if ($originCountry && $destinationCountry && $originCountry === $destinationCountry) {
            // Villes principales pour déterminer urbain vs intercité
            $majorCities = ['Paris', 'Lyon', 'Marseille', 'Berlin', 'Munich', 'Madrid', 'Barcelone', 'Rome', 'Milan'];
            
            if (in_array($originCity, $majorCities) && in_array($destinationCity, $majorCities)) {
                return $originCity === $destinationCity ? 'urban' : 'intercity';
            }
            
            return 'intercity';
        }

        // Par défaut, si on ne peut pas déterminer
        return 'intercity';
    }
}
