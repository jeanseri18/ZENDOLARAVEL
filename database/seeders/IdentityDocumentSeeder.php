<?php

namespace Database\Seeders;

use App\Models\IdentityDocument;
use App\Models\User;
use Illuminate\Database\Seeder;

class IdentityDocumentSeeder extends Seeder
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

        $documentTypes = ['passport', 'national_id', 'driver_license', 'residence_permit'];
        $statuses = ['pending', 'verified', 'rejected', 'expired'];
        
        // Créer des documents pour environ 80% des utilisateurs
        $usersWithDocuments = $users->random(min($users->count(), (int)($users->count() * 0.8)));
        
        foreach ($usersWithDocuments as $user) {
            // Chaque utilisateur peut avoir 1 à 3 documents
            $numDocuments = rand(1, 3);
            $userDocumentTypes = collect($documentTypes)->random($numDocuments);
            
            foreach ($userDocumentTypes as $docType) {
                $createdAt = now()->subDays(rand(1, 365));
                $expiryDate = now()->addYears(rand(1, 10));
                
                IdentityDocument::create([
                    'user_id' => $user->user_id,
                    'document_type' => $docType,
                    'document_number' => $this->generateDocumentNumber($docType),
                    'document_photo' => 'documents/' . $docType . '_front_' . uniqid() . '.jpg',
                    'document_photo_back' => rand(0, 1) ? 'documents/' . $docType . '_back_' . uniqid() . '.jpg' : null,
                    'expiry_date' => $expiryDate->format('Y-m-d'),
                    'issuing_country' => 'Côte d\'Ivoire',
                    'verification_status' => $statuses[array_rand($statuses)],
                    'rejection_reason' => rand(0, 1) && $statuses[array_rand($statuses)] === 'rejected' ? 'Document illisible ou incomplet' : null,
                    'uploaded_at' => $createdAt,
                    'verified_at' => rand(0, 1) ? $createdAt->copy()->addDays(rand(1, 30)) : null,
                    'verified_by' => rand(0, 1) ? rand(1, 5) : null,
                    'is_primary' => rand(0, 1) ? true : false,
                ]);
            }
        }
    }
    
    private function generateDocumentNumber(string $type): string
    {
        return match($type) {
            'passport' => rand(10, 99) . 'AA' . rand(10000, 99999),
            'national_id' => rand(100000000, 999999999) . rand(10, 99),
            'driver_license' => rand(100000000000, 999999999999),
            'residence_permit' => 'RP' . rand(1000000, 9999999),
            default => 'DOC' . rand(100000, 999999)
        };
    }
    
    private function getIssuingAuthority(string $type): string
    {
        return match($type) {
            'passport' => 'Préfecture de ' . ['Paris', 'Lyon', 'Marseille'][array_rand(['Paris', 'Lyon', 'Marseille'])],
            'national_id' => 'Mairie de ' . ['Paris', 'Lyon', 'Marseille', 'Toulouse'][array_rand(['Paris', 'Lyon', 'Marseille', 'Toulouse'])],
            'driver_license' => 'Préfecture - Service des Permis de Conduire',
            'residence_permit' => 'Préfecture - Service des Étrangers',
            default => 'Autorité Compétente'
        };
    }
    
    private function getVerificationNotes(string $status): string
    {
        return match($status) {
            'verified' => 'Document vérifié avec succès. Toutes les informations sont conformes.',
            'rejected' => 'Document rejeté : qualité insuffisante ou informations non conformes.',
            'pending' => 'Document en cours de vérification par notre équipe.',
            'expired' => 'Document expiré. Veuillez fournir un document valide.',
            default => 'En attente de traitement.'
        };
    }
}