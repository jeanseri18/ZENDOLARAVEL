<?php

namespace App\Services;

class DeliveryTypeService
{
    /**
     * Determine delivery type based on origin and destination cities/countries
     *
     * @param string $origin
     * @param string $destination
     * @return string
     */
    public static function determineDeliveryType(string $origin, string $destination): string
    {
        // Normalize city names for comparison
        $origin = self::normalizeLocation($origin);
        $destination = self::normalizeLocation($destination);

        // Check if it's the same city (urban delivery)
        if (self::isSameCity($origin, $destination)) {
            return 'urban';
        }

        // Check if it's international delivery
        if (self::isInternationalDelivery($origin, $destination)) {
            return 'international';
        }

        // Default to intercity for different cities within same country
        return 'intercity';
    }

    /**
     * Normalize location name for comparison
     *
     * @param string $location
     * @return string
     */
    private static function normalizeLocation(string $location): string
    {
        return strtolower(trim($location));
    }

    /**
     * Check if two locations are the same city
     *
     * @param string $origin
     * @param string $destination
     * @return bool
     */
    private static function isSameCity(string $origin, string $destination): bool
    {
        // Direct comparison
        if ($origin === $destination) {
            return true;
        }

        // Check for common city variations
        $cityVariations = [
            'paris' => ['paris', 'paris 1er', 'paris 2ème', 'paris 3ème', 'paris 4ème', 'paris 5ème', 'paris 6ème', 'paris 7ème', 'paris 8ème', 'paris 9ème', 'paris 10ème', 'paris 11ème', 'paris 12ème', 'paris 13ème', 'paris 14ème', 'paris 15ème', 'paris 16ème', 'paris 17ème', 'paris 18ème', 'paris 19ème', 'paris 20ème'],
            'lyon' => ['lyon', 'lyon 1er', 'lyon 2ème', 'lyon 3ème', 'lyon 4ème', 'lyon 5ème', 'lyon 6ème', 'lyon 7ème', 'lyon 8ème', 'lyon 9ème'],
            'marseille' => ['marseille', 'marseille 1er', 'marseille 2ème', 'marseille 3ème', 'marseille 4ème', 'marseille 5ème', 'marseille 6ème', 'marseille 7ème', 'marseille 8ème', 'marseille 9ème', 'marseille 10ème', 'marseille 11ème', 'marseille 12ème', 'marseille 13ème', 'marseille 14ème', 'marseille 15ème', 'marseille 16ème'],
        ];

        foreach ($cityVariations as $baseCity => $variations) {
            if (in_array($origin, $variations) && in_array($destination, $variations)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if delivery is international based on countries
     *
     * @param string $origin
     * @param string $destination
     * @return bool
     */
    private static function isInternationalDelivery(string $origin, string $destination): bool
    {
        // Define country mappings for major cities
        $countryMappings = [
            // France
            'france' => ['paris', 'lyon', 'marseille', 'toulouse', 'nice', 'nantes', 'strasbourg', 'montpellier', 'bordeaux', 'lille', 'rennes', 'reims', 'le havre', 'saint-étienne', 'toulon', 'grenoble', 'dijon', 'angers', 'nîmes', 'villeurbanne'],
            
            // Belgium
            'belgique' => ['bruxelles', 'anvers', 'gand', 'charleroi', 'liège', 'bruges', 'namur', 'louvain', 'mons', 'aalst'],
            
            // Switzerland
            'suisse' => ['zurich', 'genève', 'bâle', 'lausanne', 'berne', 'winterthour', 'lucerne', 'saint-gall', 'lugano', 'bienne'],
            
            // Germany
            'allemagne' => ['berlin', 'hambourg', 'munich', 'cologne', 'francfort', 'stuttgart', 'düsseldorf', 'dortmund', 'essen', 'leipzig'],
            
            // Spain
            'espagne' => ['madrid', 'barcelone', 'valence', 'séville', 'saragosse', 'málaga', 'murcie', 'palma', 'las palmas', 'bilbao'],
            
            // Italy
            'italie' => ['rome', 'milan', 'naples', 'turin', 'palerme', 'gênes', 'bologne', 'florence', 'bari', 'catane'],
            
            // United Kingdom
            'royaume-uni' => ['londres', 'birmingham', 'leeds', 'glasgow', 'sheffield', 'bradford', 'liverpool', 'édimbourg', 'manchester', 'bristol'],
            
            // Netherlands
            'pays-bas' => ['amsterdam', 'rotterdam', 'la haye', 'utrecht', 'eindhoven', 'tilburg', 'groningue', 'almere', 'breda', 'nimègue'],
        ];

        $originCountry = self::getCountryFromCity($origin, $countryMappings);
        $destinationCountry = self::getCountryFromCity($destination, $countryMappings);

        // If we can determine both countries and they're different, it's international
        if ($originCountry && $destinationCountry && $originCountry !== $destinationCountry) {
            return true;
        }

        // Check for explicit country names in the location strings
        $countries = array_keys($countryMappings);
        $originHasCountry = false;
        $destinationHasCountry = false;
        $originCountryName = null;
        $destinationCountryName = null;

        foreach ($countries as $country) {
            if (strpos($origin, $country) !== false) {
                $originHasCountry = true;
                $originCountryName = $country;
            }
            if (strpos($destination, $country) !== false) {
                $destinationHasCountry = true;
                $destinationCountryName = $country;
            }
        }

        if ($originHasCountry && $destinationHasCountry && $originCountryName !== $destinationCountryName) {
            return true;
        }

        return false;
    }

    /**
     * Get country from city name
     *
     * @param string $city
     * @param array $countryMappings
     * @return string|null
     */
    private static function getCountryFromCity(string $city, array $countryMappings): ?string
    {
        foreach ($countryMappings as $country => $cities) {
            if (in_array($city, $cities)) {
                return $country;
            }
        }
        return null;
    }

    /**
     * Get all supported delivery types
     *
     * @return array
     */
    public static function getSupportedTypes(): array
    {
        return ['urban', 'intercity', 'international'];
    }

    /**
     * Get delivery type label in French
     *
     * @param string $type
     * @return string
     */
    public static function getTypeLabel(string $type): string
    {
        $labels = [
            'urban' => 'Urbain',
            'intercity' => 'Intercité',
            'international' => 'International',
        ];

        return $labels[$type] ?? $type;
    }
}