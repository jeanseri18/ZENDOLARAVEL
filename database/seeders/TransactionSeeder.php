<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Package;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $packages = Package::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Veuillez d\'abord exécuter UserSeeder.');
            return;
        }

        $transactionTypes = ['payment', 'refund', 'commission', 'withdrawal', 'deposit'];
        $statuses = ['pending', 'completed', 'failed', 'cancelled', 'processing'];
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'wallet', 'cash'];
        
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $package = $packages->isNotEmpty() ? $packages->random() : null;
            $createdAt = now()->subDays(rand(1, 180));
            $type = $transactionTypes[array_rand($transactionTypes)];
            
            // Déterminer le montant selon le type
            $amount = match($type) {
                'payment' => rand(500, 10000) / 100, // 5 XOF à 100 XOF
                'refund' => rand(500, 5000) / 100,   // 5 XOF à 50 XOF
                'commission' => rand(50, 500) / 100,  // 0.5 XOF à 5 XOF
                'withdrawal' => rand(1000, 50000) / 100, // 10 XOF à 500 XOF
                'deposit' => rand(2000, 100000) / 100,   // 20 XOF à 1000 XOF
                default => rand(500, 5000) / 100
            };
            
            Transaction::create([
                'sender_id' => $user->user_id,
                'traveler_id' => $package?->traveler_id,
                'package_id' => $package?->package_id,
                'transaction_reference' => 'TXN' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'transaction_type' => match($type) {
                    'payment' => 'delivery_fee',
                    'refund' => 'refund',
                    'commission' => 'commission',
                    'withdrawal' => 'wallet_withdrawal',
                    'deposit' => 'wallet_topup',
                    default => 'delivery_fee'
                },
                'amount' => $amount,
                'currency' => 'XOF',
                'commission_rate' => rand(5, 15) / 100, // 5% à 15%
                'commission_amount' => $amount * (rand(5, 15) / 100),
                'net_amount' => $amount - ($amount * (rand(5, 15) / 100)),
                'payment_method' => ['mobile_money', 'orange_money', 'mtn_money', 'moov_money', 'bank_transfer', 'cash', 'card', 'wallet'][array_rand(['mobile_money', 'orange_money', 'mtn_money', 'moov_money', 'bank_transfer', 'cash', 'card', 'wallet'])],
                'payment_provider' => ['Orange', 'MTN', 'Moov', 'Wave', 'Bank'][array_rand(['Orange', 'MTN', 'Moov', 'Wave', 'Bank'])],
                'transaction_status' => ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded', 'disputed'][array_rand(['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded', 'disputed'])],
                'failure_reason' => rand(0, 1) ? 'Insufficient funds' : null,
                'processed_at' => rand(0, 1) ? $createdAt->copy()->addMinutes(rand(1, 60)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addMinutes(rand(0, 60)),
            ]);
        }
    }
}