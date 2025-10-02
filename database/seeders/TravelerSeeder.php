<?php

namespace Database\Seeders;

use App\Models\Traveler;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TravelerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs pour les livreurs
        $travelerUsers = [
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Benali',
                'email' => 'ahmed.benali2@example.com',
                'phone_number' => '+33612345679',
                'password' => Hash::make('password'),
                'role' => 'traveler',
                'city' => 'Paris',
                'country' => 'France',
                'bio' => 'Livreur expérimenté avec 5 ans d\'expérience',
                'is_active' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Moreau',
                'email' => 'sophie.moreau2@example.com',
                'phone_number' => '+33687654322',
                'password' => Hash::make('password'),
                'role' => 'traveler',
                'city' => 'Lyon',
                'country' => 'France',
                'bio' => 'Livreuse rapide et fiable',
                'is_active' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'Rodriguez',
                'email' => 'carlos.rodriguez2@example.com',
                'phone_number' => '+33698765433',
                'password' => Hash::make('password'),
                'role' => 'traveler',
                'city' => 'Marseille',
                'country' => 'France',
                'bio' => 'Nouveau livreur motivé',
                'is_active' => true,
                'is_verified' => false,
            ],
        ];

        foreach ($travelerUsers as $userData) {
            $user = User::create($userData);
            
            // Créer le profil traveler avec les colonnes existantes
            Traveler::create([
                'user_id' => $user->user_id,
                'vehicle_type' => ['car', 'motorcycle', 'bicycle', 'van'][array_rand(['car', 'motorcycle', 'bicycle', 'van'])],
                'vehicle_model' => 'Model ' . rand(2015, 2024),
                'vehicle_year' => rand(2015, 2024),
                'license_plate' => 'LIC' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'driver_license' => 'DL' . str_pad(rand(1, 999999), 8, '0', STR_PAD_LEFT),
                'max_weight_kg' => rand(10, 100),
                'max_dimensions' => rand(50, 200) . 'x' . rand(50, 200) . 'x' . rand(50, 200),
                'service_areas' => json_encode([$user->city, 'Paris', 'Lyon']),
                'hourly_rate' => rand(15, 50),
                'bio' => 'Livreur expérimenté et fiable',
                'is_verified' => rand(0, 1),
                'is_available' => rand(0, 1),
                'rating' => rand(35, 50) / 10,
                'total_deliveries' => rand(0, 100),
                'departure_city' => $user->city,
                'arrival_city' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice'][array_rand(['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice'])],
                'departure_date' => now()->addDays(rand(1, 30)),
                'arrival_date' => now()->addDays(rand(31, 60)),
                'departure_time' => now()->format('H:i:s'),
                'arrival_time' => now()->addHours(rand(2, 8))->format('H:i:s'),
                'available_weight' => rand(5, 50),
                'max_package_weight' => rand(10, 30),
                'price_per_kg' => rand(5, 20),
                'transport_type' => ['avion', 'bus', 'voiture', 'train'][array_rand(['avion', 'bus', 'voiture', 'train'])],
                'status' => 'active',
                'is_online' => rand(0, 1),
                'last_seen' => now()->subMinutes(rand(1, 1440)),
                'special_instructions' => 'Instructions spéciales pour la livraison',
                'accepts_fragile' => rand(0, 1),
                'accepts_documents' => true,
                'verification_required' => rand(0, 1),
                'supported_delivery_types' => json_encode(['urban', 'intercity', 'international']),
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now(),
            ]);
        }
    }
}