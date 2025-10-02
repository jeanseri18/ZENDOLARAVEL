@extends('admin.layouts.app')

@section('title', 'Documents d\'Identité')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Documents d'Identité</h1>
            <p class="text-gray-600">Gérez les documents d'identité des utilisateurs</p>
        </div>
        <div class="flex space-x-3">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $documents->total() }} documents</span>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.identity-documents.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nom, numéro..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                <select name="document_type" id="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les types</option>
                    <option value="passport" {{ request('document_type') == 'passport' ? 'selected' : '' }}>Passeport</option>
                    <option value="national_id" {{ request('document_type') == 'national_id' ? 'selected' : '' }}>Carte d'identité</option>
                    <option value="driver_license" {{ request('document_type') == 'driver_license' ? 'selected' : '' }}>Permis de conduire</option>
                    <option value="residence_permit" {{ request('document_type') == 'residence_permit' ? 'selected' : '' }}>Titre de séjour</option>
                </select>
            </div>
            <div>
                <label for="verification_status" class="block text-sm font-medium text-gray-700 mb-1">Statut de vérification</label>
                <select name="verification_status" id="verification_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Vérifié</option>
                    <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                    <option value="expired" {{ request('verification_status') == 'expired' ? 'selected' : '' }}>Expiré</option>
                </select>
            </div>
            <div>
                <label for="expiry_status" class="block text-sm font-medium text-gray-700 mb-1">Expiration</label>
                <select name="expiry_status" id="expiry_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="expired" {{ request('expiry_status') == 'expired' ? 'selected' : '' }}>Expirés</option>
                    <option value="expiring_soon" {{ request('expiry_status') == 'expiring_soon' ? 'selected' : '' }}>Expire bientôt</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Filtrer</button>
                <a href="{{ route('admin.identity-documents.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">Réinitialiser</a>
            </div>
                    </form>
                </div>

</div>

<!-- Tableau des documents -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($documents->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pays d'émission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'expiration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléchargé le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($document->user->profile_photo)
                                        <img src="{{ asset('storage/' . $document->user->profile_photo) }}" alt="Photo" class="w-8 h-8 rounded-full mr-3">
                                    @else
                                        <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        @if($document->user)
                                    <div class="text-sm font-medium text-gray-900">{{ $document->user->first_name }} {{ $document->user->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $document->user->email }}</div>
                                @else
                                    <div class="text-sm font-medium text-gray-900">Unknown User</div>
                                    <div class="text-sm text-gray-500">No email available</div>
                                @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $document->document_type_label }}</span>
                                @if($document->is_primary)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-1">Principal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $document->document_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->issuing_country }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($document->expiry_date)
                                    <span class="{{ $document->isExpired() ? 'text-red-600' : ($document->expiry_date->diffInDays() < 30 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $document->expiry_date->format('d/m/Y') }}
                                    </span>
                                    @if($document->isExpired())
                                        <div class="text-xs text-red-600">Expiré</div>
                                    @elseif($document->expiry_date->diffInDays() < 30)
                                        <div class="text-xs text-yellow-600">Expire bientôt</div>
                                    @endif
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($document->verification_status)
                                    @case('pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $document->status_label }}</span>
                                        @break
                                    @case('verified')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $document->status_label }}</span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $document->status_label }}</span>
                                        @break
                                    @case('expired')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $document->status_label }}</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $document->uploaded_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.identity-documents.show', $document->document_id) }}" class="text-blue-600 hover:text-blue-900" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($document->verification_status === 'pending')
                                        <button type="button" class="text-green-600 hover:text-green-900" onclick="openModal('verifyModal{{ $document->document_id }}')" title="Vérifier">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if(!$document->is_primary && $document->verification_status === 'verified')
                                        <form action="{{ route('admin.identity-documents.set-primary', $document->document_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Définir comme principal">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.identity-documents.destroy', $document->document_id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                                        </tr>

                                        <!-- Modal de vérification -->
                        @if($document->verification_status === 'pending')
                            <div id="verifyModal{{ $document->document_id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                    <div class="flex justify-between items-center pb-3">
                                        <h3 class="text-lg font-bold text-gray-900">Vérifier le document</h3>
                                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('verifyModal{{ $document->document_id }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.identity-documents.verify', $document->document_id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="py-3">
                                            <p class="mb-2"><strong>Utilisateur:</strong> 
                                @if($document->user)
                                    {{ $document->user->first_name }} {{ $document->user->last_name }}
                                @else
                                    Unknown User
                                @endif
                            </p>
                                            <p class="mb-2"><strong>Type:</strong> {{ $document->document_type_label }}</p>
                                            <p class="mb-4"><strong>Numéro:</strong> {{ $document->document_number }}</p>
                                            
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                                                <div class="space-y-2">
                                                    <div class="flex items-center">
                                                        <input type="radio" name="action" value="verify" id="verify{{ $document->document_id }}" class="mr-2" checked>
                                                        <label class="text-green-600" for="verify{{ $document->document_id }}">
                                                            <i class="fas fa-check"></i> Vérifier le document
                                                        </label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input type="radio" name="action" value="reject" id="reject{{ $document->document_id }}" class="mr-2">
                                                        <label class="text-red-600" for="reject{{ $document->document_id }}">
                                                            <i class="fas fa-times"></i> Rejeter le document
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4 hidden" id="rejectionReason{{ $document->document_id }}">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Raison du rejet</label>
                                                <textarea name="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Expliquez pourquoi ce document est rejeté..."></textarea>
                                            </div>
                                        </div>
                                        <div class="flex justify-end space-x-2 pt-3">
                                            <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400" onclick="closeModal('verifyModal{{ $document->document_id }}')">Annuler</button>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Confirmer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-id-card text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun document trouvé</h3>
            <p class="text-gray-500">Aucun document d'identité ne correspond aux critères de recherche.</p>
        </div>
    @endif
</div>

@if($documents->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $documents->appends(request()->query())->links() }}
                    </div>
    @endif
</div>

@push('scripts')
<script>
// Fonctions pour les modaux
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Afficher/masquer le champ de raison de rejet
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="action"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const modalId = this.id.replace('verify', '').replace('reject', '');
            const reasonDiv = document.getElementById('rejectionReason' + modalId);
            
            if (this.value === 'reject') {
                reasonDiv.classList.remove('hidden');
                reasonDiv.querySelector('textarea').required = true;
            } else {
                reasonDiv.classList.add('hidden');
                reasonDiv.querySelector('textarea').required = false;
            }
        });
    });
    
    // Fermer les modaux en cliquant à l'extérieur
    document.querySelectorAll('[id^="verifyModal"]').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
});
</script>
@endpush
@endsection