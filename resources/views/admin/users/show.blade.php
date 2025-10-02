@extends('admin.layouts.app')

@section('title', 'Détails Utilisateur - ' . $user->first_name . ' ' . $user->last_name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Détails de l'Utilisateur</h1>
            <p class="text-gray-600">Informations complètes sur {{ $user->first_name }} {{ $user->last_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Modifier
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations Personnelles</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <p class="text-sm text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <p class="text-sm text-gray-900">{{ $user->phone_number ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                        <p class="text-sm text-gray-900">
                            {{ $user->profile?->date_of_birth?->format('d/m/Y') ?? 'Non renseigné' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <p class="text-sm text-gray-900">{{ $user->profile?->address ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <p class="text-sm text-gray-900">{{ $user->profile?->city ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                        <p class="text-sm text-gray-900">{{ $user->profile?->country ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($user->role)
                                @case('admin') bg-purple-100 text-purple-800 @break
                                @case('traveler') bg-blue-100 text-blue-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($user->role)
                                @case('admin') Admin @break
                                @case('traveler') Voyageur @break
                                @default Utilisateur
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Packages History -->
        @if($user->packages && $user->packages->count() > 0)
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Historique des Colis ({{ $user->packages->count() }})</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origine → Destination</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->packages->take(5) as $package)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.packages.show', $package->package_id) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $package->tracking_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $package->origin }} → {{ $package->destination }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @switch($package->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('in_transit') bg-blue-100 text-blue-800 @break
                                            @case('delivered') bg-green-100 text-green-800 @break
                                            @case('cancelled') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($package->status)
                                            @case('pending') En attente @break
                                            @case('in_transit') En transit @break
                                            @case('delivered') Livré @break
                                            @case('cancelled') Annulé @break
                                            @default {{ $package->status }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $package->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($user->packages->count() > 5)
                <div class="mt-4">
                    <a href="{{ route('admin.packages.index', ['user_id' => $user->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Voir tous les colis ({{ $user->packages->count() }}) →
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Support Tickets -->
        @if($user->supportTickets && $user->supportTickets->count() > 0)
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Tickets de Support ({{ $user->supportTickets->count() }})</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($user->supportTickets->take(3) as $ticket)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.support-tickets.show', $ticket->ticket_id) }}" class="text-blue-600 hover:text-blue-900">
                                        #{{ $ticket->ticket_number }} - {{ $ticket->subject }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($ticket->description, 100) }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @switch($ticket->status)
                                    @case('open') bg-red-100 text-red-800 @break
                                    @case('in_progress') bg-yellow-100 text-yellow-800 @break
                                    @case('resolved') bg-green-100 text-green-800 @break
                                    @case('closed') bg-gray-100 text-gray-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($ticket->status)
                                    @case('open') Ouvert @break
                                    @case('in_progress') En cours @break
                                    @case('resolved') Résolu @break
                                    @case('closed') Fermé @break
                                    @default {{ $ticket->status }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($user->supportTickets->count() > 3)
                <div class="mt-4">
                    <a href="{{ route('admin.support-tickets.index', ['user_id' => $user->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Voir tous les tickets ({{ $user->supportTickets->count() }}) →
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Statut du Compte</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Statut</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Email vérifié</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $user->email_verified_at ? 'Oui' : 'Non' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Inscription</span>
                    <span class="text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Dernière connexion</span>
                    <span class="text-sm text-gray-500">
                        {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Statistiques</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Colis envoyés</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $user->packages->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Tickets créés</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $user->supportTickets->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Transactions</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $user->transactions->count() }}</span>
                </div>
                @if($user->transactions->where('transaction_status', 'completed')->count() > 0)
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Total dépensé</span>
                    <span class="text-sm font-semibold text-gray-900">
                        {{ number_format($user->transactions->where('transaction_status', 'completed')->sum('amount'), 0, ',', ' ') }} XOF
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        @if($user->role !== 'admin')
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actions Rapides</h3>
            </div>
            <div class="p-6 space-y-3">
                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-md text-sm font-medium">
                        {{ $user->is_active ? 'Désactiver le compte' : 'Activer le compte' }}
                    </button>
                </form>
                
                @if(!$user->email_verified_at)
                <form method="POST" action="{{ route('admin.users.toggle-verification', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Vérifier l'email
                    </button>
                </form>
                @endif
                
                <form method="POST" action="{{ route('admin.users.destroy', $user->user_id) }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Supprimer le compte
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection