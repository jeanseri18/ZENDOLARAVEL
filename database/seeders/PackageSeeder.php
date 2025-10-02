<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['expeditor', 'both'])->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Veuillez d\'abord exécuter UserSeeder.');
            return;
        }

        // Variables supprimées car non utilisées
        
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $createdAt = now()->subDays(rand(1, 90));
            $deliveryCity = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice'][array_rand(['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice'])];
            
            Package::create([
                'sender_id' => $user->user_id,
                'tracking_number' => 'PKG' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'recipient_name' => 'Destinataire ' . ($i + 1),
                'recipient_phone' => '+33' . rand(600000000, 799999999),
                'recipient_email' => 'destinataire' . ($i + 1) . '@example.com',
                'recipient_address' => rand(1, 999) . ' Avenue des Champs, ' . $deliveryCity,
                'package_description' => 'Description du colis ' . ($i + 1),
                'category' => ['documents', 'electronics', 'clothing', 'food', 'medicine', 'fragile', 'other'][array_rand(['documents', 'electronics', 'clothing', 'food', 'medicine', 'fragile', 'other'])],
                'weight' => rand(1, 50) / 10, // Poids entre 0.1 et 5.0 kg
                'dimensions_length' => rand(10, 50),
                'dimensions_width' => rand(10, 50),
                'dimensions_height' => rand(5, 30),
                'declared_value' => rand(10, 500),
                'insurance_value' => rand(0, 100),
                'fragile' => rand(0, 1),
                'requires_signature' => rand(0, 1),
                'pickup_address' => rand(1, 999) . ' Rue de la Paix, ' . $user->city,
                'delivery_address' => rand(1, 999) . ' Avenue des Champs, ' . $deliveryCity,
                'pickup_city' => $user->city,
                'delivery_city' => $deliveryCity,
                'estimated_delivery_fee' => rand(5, 50),
                'status' => ['pending', 'accepted', 'picked_up', 'in_transit', 'delivered'][array_rand(['pending', 'accepted', 'picked_up', 'in_transit', 'delivered'])],
                'priority' => ['standard', 'express', 'urgent'][array_rand(['standard', 'express', 'urgent'])],
                'delivery_type' => ['urban', 'intercity', 'international'][array_rand(['urban', 'intercity', 'international'])],
                'special_instructions' => 'Instructions spéciales pour le colis ' . ($i + 1),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(0, 5)),
            ]);
        }
    }
}