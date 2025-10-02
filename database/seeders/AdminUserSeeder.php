<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si l'admin existe déjà
        $adminExists = User::where('email', 'admin@zendo.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'first_name' => 'Admin',
                'last_name' => 'Zendo',
                'email' => 'admin@zendo.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'both', // Le rôle admin n'existe pas, on utilise 'both'
                'is_active' => true,
                'phone_number' => '+33123456789',
                'city' => 'Paris',
                'country' => 'France',
                'bio' => 'Compte administrateur du système Zendo',
                'is_verified' => true,
                'phone_verified_at' => now(),
                'reliability_score' => 5.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Compte administrateur créé avec succès!');
            $this->command->info('Email: admin@zendo.com');
            $this->command->info('Mot de passe: admin123');
        } else {
            $this->command->info('Le compte administrateur existe déjà.');
        }
        
        // Créer quelques utilisateurs de test supplémentaires
        $testUsers = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont@example.com',
                'password' => Hash::make('password123'),
                'role' => 'expeditor',
                'is_active' => true,
                'phone_number' => '+33123456780',
                'city' => 'Lyon',
                'country' => 'France',
                'bio' => 'Utilisateur expéditeur de Lyon',
                'is_verified' => true,
                'reliability_score' => 4.2,
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Martin',
                'email' => 'marie.martin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'expeditor',
                'is_active' => true,
                'phone_number' => '+33123456781',
                'city' => 'Marseille',
                'country' => 'France',
                'bio' => 'Utilisatrice expéditrice de Marseille',
                'is_verified' => true,
                'reliability_score' => 4.5,
            ],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Diallo',
                'email' => 'ahmed.diallo@example.com',
                'password' => Hash::make('password123'),
                'role' => 'traveler',
                'is_active' => true,
                'phone_number' => '+221123456789',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'bio' => 'Voyageur expérimenté entre l\'Afrique et l\'Europe',
                'is_verified' => true,
                'reliability_score' => 4.8,
            ],
            [
                'first_name' => 'Fatou',
                'last_name' => 'Sow',
                'email' => 'fatou.sow@example.com',
                'password' => Hash::make('password123'),
                'role' => 'traveler',
                'is_active' => true,
                'phone_number' => '+221123456790',
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'bio' => 'Voyageuse régulière sur les routes africaines',
                'is_verified' => true,
                'reliability_score' => 4.6,
            ]
        ];
        
        foreach ($testUsers as $userData) {
            $userExists = User::where('email', $userData['email'])->exists();
            
            if (!$userExists) {
                User::create(array_merge($userData, [
                    'email_verified_at' => now(),
                    'phone_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
        
        $this->command->info('Utilisateurs de test créés avec succès!');
    }
}