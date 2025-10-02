<?php

namespace Database\Seeders;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Veuillez d\'abord exécuter UserSeeder.');
            return;
        }

        $categories = ['delivery_issue', 'payment_issue', 'account_issue', 'technical_issue', 'other'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'in_progress', 'waiting_response', 'resolved', 'closed'];
        
        $subjects = [
            'Problème de connexion à mon compte',
            'Ma livraison n\'est pas arrivée',
            'Erreur de facturation sur ma commande',
            'Comment modifier mon profil ?',
            'Le livreur n\'a pas trouvé l\'adresse',
            'Remboursement suite à un problème',
            'Application mobile qui plante',
            'Notification non reçue',
            'Problème avec le paiement',
            'Question sur les tarifs',
            'Colis endommagé à la livraison',
            'Impossible de suivre mon colis',
            'Changement d\'adresse de livraison',
            'Problème avec mon mot de passe',
            'Service client non réactif'
        ];
        
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $createdAt = now()->subDays(rand(1, 60));
            $subject = $subjects[array_rand($subjects)];
            
            SupportTicket::create([
                'user_id' => $user->user_id,
                'ticket_number' => 'TICKET' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'subject' => $subject,
                'description' => 'Description détaillée du problème : ' . $subject . '. L\'utilisateur rencontre des difficultés et souhaite obtenir de l\'aide pour résoudre cette situation.',
                'category' => $categories[array_rand($categories)],
                'priority' => $priorities[array_rand($priorities)],
                'status' => $statuses[array_rand($statuses)],
                'assigned_to' => rand(0, 1) ? $users->random()->user_id : null,
                'resolution' => rand(0, 1) ? 'Problème résolu avec succès.' : null,
                'resolved_at' => rand(0, 1) ? $createdAt->copy()->addDays(rand(1, 14)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(0, 3)),
            ]);
        }
    }
}