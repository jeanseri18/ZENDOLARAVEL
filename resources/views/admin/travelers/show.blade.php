@extends('admin.layouts.app')

@section('title', 'Détails du Livreur - ' . ($traveler->user ? $traveler->user->first_name . ' ' . $traveler->user->last_name : 'Unknown User'))

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            @if($traveler->user)
                <h1 class="text-2xl font-bold text-gray-900">{{ $traveler->user->first_name }} {{ $traveler->user->last_name }}</h1>
            @else
                <h1 class="text-2xl font-bold text-gray-900">Unknown User</h1>
            @endif
            <p class="text-gray-600">Livreur depuis le {{ $traveler->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.travelers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
            <a href="{{ route('admin.travelers.edit', $traveler->traveler_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Modifier
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Informations Personnelles</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 h-20 w-20">
                        @if($traveler->user)
                            @if($traveler->profile && $traveler->profile->avatar)
                                <img class="h-20 w-20 rounded-full" src="{{ asset('storage/' . $traveler->profile->avatar) }}" alt="{{ $traveler->user->first_name }} {{ $traveler->user->last_name }}">
                            @else
                                <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-2xl font-medium text-gray-700">{{ substr($traveler->user->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                        @else
                            <div class="h-20 w-20 rounded-full bg-gray-400 flex items-center justify-center">
                                <span class="text-2xl font-medium text-gray-600">?</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-6">
                        @if($traveler->user)
                            <h3 class="text-xl font-semibold text-gray-900">{{ $traveler->user->first_name }} {{ $traveler->user->last_name }}</h3>
                            <p class="text-gray-600">{{ $traveler->user->email }}</p>
                            @if($traveler->user->phone_number)
                                <p class="text-gray-600">{{ $traveler->user->phone_number }}</p>
                            @endif
                        @else
                            <h3 class="text-xl font-semibold text-gray-900">Unknown User</h3>
                            <p class="text-gray-600">No email available</p>
                        @endif
                    </div>
                </div>
                
                @if($traveler->profile)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                        <p class="text-sm text-gray-900">{{ $traveler->profile->date_of_birth ? $traveler->profile->date_of_birth->format('d/m/Y') : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                        <p class="text-sm text-gray-900">{{ $traveler->profile->gender ? ucfirst($traveler->profile->gender) : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <p class="text-sm text-gray-900">{{ $traveler->profile->address ?: 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <p class="text-sm text-gray-900">{{ $traveler->profile->city ?: 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                        <p class="text-sm text-gray-900">{{ $traveler->profile->country ?: 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                        <p class="text-sm text-gray-900">{{ $traveler->profile->postal_code ?: 'Non renseigné' }}</p>
                    </div>
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-yellow-800">Le profil de ce livreur est incomplet</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Packages -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Colis Récents</h2>
                    <a href="{{ route('admin.packages.index', ['traveler' => $traveler->id]) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir tous →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentPackages->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentPackages as $package)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $package->tracking_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $package->pickup_city }} → {{ $package->delivery_city }}</p>
                                    <p class="text-xs text-gray-400">{{ $package->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @switch($package->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('confirmed') bg-blue-100 text-blue-800 @break
                                        @case('in_transit') bg-purple-100 text-purple-800 @break
                                        @case('delivered') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    @switch($package->status)
                                        @case('pending') En attente @break
                                        @case('confirmed') Confirmé @break
                                        @case('in_transit') En transit @break
                                        @case('delivered') Livré @break
                                        @case('cancelled') Annulé @break
                                        @default {{ $package->status }}
                                    @endswitch
                                </span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($package->final_delivery_fee ?? $package->estimated_delivery_fee ?? 0, 0, ',', ' ') }} XOF</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucun colis assigné</p>
                @endif
            </div>
        </div>
        
        <!-- Delivery History -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Historique des Livraisons</h2>
            </div>
            <div class="p-6">
                @if(isset($deliveryStats) && count($deliveryStats) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $deliveryStats['total_deliveries'] ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Total livraisons</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $deliveryStats['success_rate'] ?? 0 }}%</p>
                            <p class="text-sm text-gray-600">Taux de réussite</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $deliveryStats['avg_delivery_time'] ?? 0 }}j</p>
                            <p class="text-sm text-gray-600">Temps moyen</p>
                        </div>
                    </div>
                @endif
                
                @if($deliveredPackages->count() > 0)
                    <div class="space-y-3">
                        @foreach($deliveredPackages as $package)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $package->tracking_number }}</p>
                                <p class="text-sm text-gray-500">{{ $package->pickup_city }} → {{ $package->delivery_city }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-green-600 font-medium">Livré</p>
                                <p class="text-xs text-gray-400">{{ $package->delivered_at ? $package->delivered_at->format('d/m/Y') : 'Date inconnue' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune livraison effectuée</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Statut</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Compte actif</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $traveler->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $traveler->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Email vérifié</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $traveler->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $traveler->email_verified_at ? 'Vérifié' : 'Non vérifié' }}
                    </span>
                </div>
                
                @if($traveler->profile)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Identité vérifiée</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $traveler->profile->identity_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $traveler->profile->identity_verified_at ? 'Vérifiée' : 'En attente' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Disponibilité</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $traveler->profile->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $traveler->profile->is_available ? 'Disponible' : 'Indisponible' }}
                    </span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Statistiques</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Colis assignés</span>
                    <span class="text-sm font-medium text-gray-900">{{ $traveler->packages_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Colis livrés</span>
                    <span class="text-sm font-medium text-gray-900">{{ $traveler->delivered_packages_count ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tickets créés</span>
                    <span class="text-sm font-medium text-gray-900">{{ $traveler->support_tickets_count ?? 0 }}</span>
                </div>
                @if(isset($traveler->rating))
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Note moyenne</span>
                    <div class="flex items-center">
                        <span class="text-yellow-400 mr-1">★</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($traveler->rating, 1) }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Actions Rapides</h2>
            </div>
            <div class="p-6 space-y-3">
                @if(!$traveler->email_verified_at)
                <form method="POST" action="{{ route('admin.travelers.toggle-verification', $traveler) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Vérifier l'email
                    </button>
                </form>
                @endif
                
                <form method="POST" action="{{ route('admin.travelers.toggle-availability', $traveler) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        {{ $traveler->profile && $traveler->profile->is_available ? 'Marquer indisponible' : 'Marquer disponible' }}
                    </button>
                </form>
                
                @if($traveler->packages_count == 0)
                <form method="POST" action="{{ route('admin.travelers.destroy', $traveler->traveler_id) }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livreur ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Supprimer le compte
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <!-- Activity Timeline -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Activité Récente</h2>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">Inscription sur la plateforme</p>
                                            <p class="text-xs text-gray-400">{{ $traveler->created_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        @if($traveler->email_verified_at)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884zM18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">Email vérifié</p>
                                            <p class="text-xs text-gray-400">{{ $traveler->email_verified_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                        
                        @if($traveler->last_login_at)
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">Dernière connexion</p>
                                            <p class="text-xs text-gray-400">{{ $traveler->last_login_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection