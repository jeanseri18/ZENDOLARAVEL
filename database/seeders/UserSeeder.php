<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs de test
        $users = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont2@example.com',
                'phone_number' => '+33123456791',
                'password' => Hash::make('password'),
                'role' => 'expeditor',
                'city' => 'Paris',
                'country' => 'France',
                'bio' => 'Utilisateur régulier de la plateforme',
                'is_active' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Martin',
                'email' => 'marie.martin2@example.com',
                'phone_number' => '+33987654322',
                'password' => Hash::make('password'),
                'role' => 'expeditor',
                'city' => 'Lyon',
                'country' => 'France',
                'bio' => 'Expéditrice fréquente',
                'is_active' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Pierre',
                'last_name' => 'Bernard',
                'email' => 'pierre.bernard@example.com',
                'phone_number' => '+33456789124',
                'password' => Hash::make('password'),
                'role' => 'expeditor',
                'city' => 'Marseille',
                'country' => 'France',
                'bio' => 'Nouveau sur la plateforme',
                'is_active' => true,
                'is_verified' => false,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}