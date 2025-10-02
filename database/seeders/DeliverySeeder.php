<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Package;
use App\Models\Traveler;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = Package::all();
        $travelers = Traveler::all();
        
        if ($packages->isEmpty()) {
            $this->command->warn('Aucun colis trouvé. Veuillez d\'abord exécuter PackageSeeder.');
            return;
        }
        
        if ($travelers->isEmpty()) {
            $this->command->warn('Aucun livreur trouvé. Veuillez d\'abord exécuter TravelerSeeder.');
            return;
        }

        $statuses = ['to_pickup', 'in_transit', 'delivered', 'delayed', 'canceled'];
        $deliveryTypes = ['urban', 'intercity', 'international'];
        
        // Créer des livraisons pour environ 70% des colis
        $packagesToDeliver = $packages->random(min($packages->count(), (int)($packages->count() * 0.7)));
        
        foreach ($packagesToDeliver as $package) {
            $traveler = $travelers->random();
            $createdAt = $package->created_at->copy()->addDays(rand(1, 3));
            
            Delivery::create([
                'package_id' => $package->package_id,
                'traveler_id' => $traveler->traveler_id,
                'delivery_type' => $deliveryTypes[array_rand($deliveryTypes)],
                'start_time' => rand(0, 1) ? $createdAt->copy()->addHours(rand(1, 24)) : null,
                'end_time' => rand(0, 1) ? $createdAt->copy()->addDays(rand(1, 5)) : null,
                'pickup_location' => $package->pickup_address,
                'delivery_location' => $package->delivery_address,
                'actual_delivery_time' => rand(0, 1) ? sprintf('%02d:%02d', rand(8, 18), rand(0, 59)) : null,
                'customs_declaration' => rand(0, 1) ? 'Déclaration douanière pour livraison internationale' : null,
                'flight_ticket_path' => rand(0, 1) ? 'tickets/flight_' . uniqid() . '.pdf' : null,
                'commission_fee' => rand(500, 5000) / 100, // Entre 5.00 et 50.00
                'gps_tracking_data' => json_encode([
                    'latitude' => rand(-90000, 90000) / 1000,
                    'longitude' => rand(-180000, 180000) / 1000,
                    'timestamp' => now()->toISOString()
                ]),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(0, 7)),
            ]);
        }
    }
}