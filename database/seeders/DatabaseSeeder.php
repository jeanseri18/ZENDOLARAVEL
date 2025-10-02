<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            TravelerSeeder::class,
            PackageSeeder::class,
            DeliverySeeder::class,
            TransactionSeeder::class,
            SupportTicketSeeder::class,
            IdentityDocumentSeeder::class,
        ]);
    }
}
