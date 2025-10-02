@extends('admin.layouts.app')

@section('title', 'Gestion des Colis')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Colis</h1>
            <p class="text-gray-600">Gérez tous les colis de la plateforme</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.packages.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Exporter CSV
            </a>
            <a href="{{ route('admin.packages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Nouveau Colis
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.packages.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Numéro, origine, destination..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>En transit</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livré</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700 mb-1">Origine</label>
                <input type="text" name="origin" id="origin" value="{{ request('origin') }}" 
                       placeholder="Ville d'origine" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                <input type="text" name="destination" id="destination" value="{{ request('destination') }}" 
                       placeholder="Ville de destination" 
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

<!-- Packages Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Numéro de suivi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Expéditeur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Route
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Voyageur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Prix
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
                @forelse($packages as $package)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $package->tracking_number }}</div>
                        <div class="text-sm text-gray-500">{{ $package->weight }}kg</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-700">{{ substr($package->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $package->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $package->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $package->origin }}</div>
                        <div class="text-sm text-gray-500">↓</div>
                        <div class="text-sm text-gray-900">{{ $package->destination }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($package->traveler)
                            <div class="text-sm font-medium text-gray-900">{{ $package->traveler->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $package->traveler->user->email }}</div>
                        @else
                            <span class="text-sm text-gray-400">Non assigné</span>
                        @endif
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($package->price, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $package->created_at->format('d/m/Y') }}
                        @if($package->delivered_at)
                            <div class="text-xs text-green-600">Livré: {{ $package->delivered_at->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.packages.show', $package) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('admin.packages.edit', $package) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            
                            @if($package->status !== 'delivered' && $package->status !== 'cancelled')
                                <!-- Status Update Dropdown -->
                                <div class="relative inline-block text-left">
                                    <button type="button" class="text-green-600 hover:text-green-900" onclick="toggleDropdown('status-{{ $package->id }}')">
                                        Statut
                                    </button>
                                    <div id="status-{{ $package->id }}" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            @if($package->status !== 'in_transit')
                                            <form method="POST" action="{{ route('admin.packages.update-status', $package) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="in_transit">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">En transit</button>
                                            </form>
                                            @endif
                                            @if($package->status !== 'delivered')
                                            <form method="POST" action="{{ route('admin.packages.update-status', $package) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="delivered">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Livré</button>
                                            </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.packages.update-status', $package) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce colis ?')">Annuler</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($package->status === 'pending' || $package->status === 'cancelled')
                                <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce colis ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Aucun colis trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($packages->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $packages->links() }}
    </div>
    @endif
</div>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('hidden');
    
    // Close other dropdowns
    document.querySelectorAll('[id^="status-"]').forEach(el => {
        if (el.id !== id) {
            el.classList.add('hidden');
        }
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleDropdown"]')) {
        document.querySelectorAll('[id^="status-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>
@endsection