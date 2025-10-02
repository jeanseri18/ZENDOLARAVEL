@extends('admin.layouts.app')

@section('title', 'Gestion des Livreurs')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Livreurs</h1>
            <p class="text-gray-600">Gérez tous les livreurs de la plateforme</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.travelers.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Exporter CSV
            </a>
            <a href="{{ route('admin.travelers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Nouveau Livreur
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Vérifiés</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['verified'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Disponibles</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['available'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Actifs</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.travelers.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nom, email, téléphone..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="verification_status" class="block text-sm font-medium text-gray-700 mb-1">Vérification</label>
                <select name="verification_status" id="verification_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Vérifiés</option>
                    <option value="unverified" {{ request('verification_status') == 'unverified' ? 'selected' : '' }}>Non vérifiés</option>
                </select>
            </div>
            <div>
                <label for="availability_status" class="block text-sm font-medium text-gray-700 mb-1">Disponibilité</label>
                <select name="availability_status" id="availability_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="available" {{ request('availability_status') == 'available' ? 'selected' : '' }}>Disponibles</option>
                    <option value="unavailable" {{ request('availability_status') == 'unavailable' ? 'selected' : '' }}>Indisponibles</option>
                </select>
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                <input type="text" name="city" id="city" value="{{ request('city') }}" 
                       placeholder="Ville de résidence" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Travelers Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Livreur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Localisation
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Vérification
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Disponibilité
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statistiques
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($travelers as $traveler)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($traveler->profile && $traveler->profile->avatar)
                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $traveler->profile->avatar) }}" alt="{{ $traveler->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">{{ substr($traveler->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $traveler->name }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $traveler->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $traveler->email }}</div>
                        @if($traveler->phone)
                            <div class="text-sm text-gray-500">{{ $traveler->phone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($traveler->profile)
                            <div class="text-sm text-gray-900">{{ $traveler->profile->city ?? 'Non renseigné' }}</div>
                            <div class="text-sm text-gray-500">{{ $traveler->profile->country ?? 'Non renseigné' }}</div>
                        @else
                            <span class="text-sm text-gray-400">Profil incomplet</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($traveler->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Vérifié
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Non vérifié
                                </span>
                            @endif
                        </div>
                        @if($traveler->profile && $traveler->profile->identity_verified_at)
                            <div class="text-xs text-green-600 mt-1">Identité vérifiée</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($traveler->profile && $traveler->profile->is_available)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Disponible
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Indisponible
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>Colis: {{ $traveler->packages_count ?? 0 }}</div>
                        <div>Livrés: {{ $traveler->delivered_packages_count ?? 0 }}</div>
                        @if(isset($traveler->rating))
                            <div class="flex items-center">
                                <span class="text-yellow-400">★</span>
                                <span class="ml-1">{{ number_format($traveler->rating, 1) }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $traveler->created_at->format('d/m/Y') }}</div>
                        @if($traveler->last_login_at)
                            <div class="text-xs text-gray-400">Vu: {{ $traveler->last_login_at->diffForHumans() }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.travelers.show', $traveler) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('admin.travelers.edit', $traveler) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            
                            <!-- Quick Actions Dropdown -->
                            <div class="relative inline-block text-left">
                                <button type="button" class="text-green-600 hover:text-green-900" onclick="toggleDropdown('actions-{{ $traveler->id }}')">
                                    Actions
                                </button>
                                <div id="actions-{{ $traveler->id }}" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        @if(!$traveler->email_verified_at)
                                        <form method="POST" action="{{ route('admin.travelers.toggle-verification', $traveler) }}" class="block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-gray-100">Vérifier email</button>
                                        </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.travelers.toggle-availability', $traveler) }}" class="block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-blue-700 hover:bg-gray-100">
                                                {{ $traveler->profile && $traveler->profile->is_available ? 'Marquer indisponible' : 'Marquer disponible' }}
                                            </button>
                                        </form>
                                        
                                        @if($traveler->packages_count == 0)
                                        <form method="POST" action="{{ route('admin.travelers.destroy', $traveler) }}" class="block" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livreur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">Supprimer</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Aucun livreur trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($travelers->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $travelers->links() }}
    </div>
    @endif
</div>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('hidden');
    
    // Close other dropdowns
    document.querySelectorAll('[id^="actions-"]').forEach(el => {
        if (el.id !== id) {
            el.classList.add('hidden');
        }
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleDropdown"]')) {
        document.querySelectorAll('[id^="actions-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>
@endsection