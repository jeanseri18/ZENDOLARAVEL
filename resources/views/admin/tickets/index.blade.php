@extends('admin.layouts.app')

@section('title', 'Gestion des Tickets de Support')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tickets de Support</h1>
            <p class="text-gray-600">Gérez tous les tickets de support de la plateforme</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.support-tickets.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Exporter CSV
            </a>
            <a href="{{ route('admin.support-tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Nouveau Ticket
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Ouverts</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['open'] ?? 0 }}</p>
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
                <p class="text-sm font-medium text-gray-600">En cours</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['in_progress'] ?? 0 }}</p>
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
                <p class="text-sm font-medium text-gray-600">Résolus</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['resolved'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-gray-100 rounded-lg">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Fermés</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['closed'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Numéro, sujet, utilisateur..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Ouvert</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Résolu</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Fermé</option>
                </select>
            </div>
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les priorités</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigné à</label>
                <select name="assigned_to" id="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Non assigné</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tickets Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ticket
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Utilisateur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sujet
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Priorité
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Assigné à
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
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $ticket->ticket_number }}</div>
                        <div class="text-sm text-gray-500">ID: {{ $ticket->id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-700">{{ substr($ticket->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($ticket->subject, 30) }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($ticket->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($ticket->priority)
                                @case('low') bg-gray-100 text-gray-800 @break
                                @case('medium') bg-yellow-100 text-yellow-800 @break
                                @case('high') bg-orange-100 text-orange-800 @break
                                @case('urgent') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($ticket->priority)
                                @case('low') Basse @break
                                @case('medium') Moyenne @break
                                @case('high') Haute @break
                                @case('urgent') Urgente @break
                                @default {{ $ticket->priority }}
                            @endswitch
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($ticket->assignedTo)
                            <div class="text-sm font-medium text-gray-900">{{ $ticket->assignedTo->name }}</div>
                        @else
                            <span class="text-sm text-gray-400">Non assigné</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                        @if($ticket->resolved_at)
                            <div class="text-xs text-green-600">Résolu: {{ $ticket->resolved_at->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.support-tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('admin.support-tickets.edit', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            
                            @if($ticket->status !== 'closed')
                                <!-- Quick Actions Dropdown -->
                                <div class="relative inline-block text-left">
                                    <button type="button" class="text-green-600 hover:text-green-900" onclick="toggleDropdown('actions-{{ $ticket->id }}')">
                                        Actions
                                    </button>
                                    <div id="actions-{{ $ticket->id }}" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            @if($ticket->status === 'open')
                                            <form method="POST" action="{{ route('admin.support-tickets.update-status', $ticket) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Prendre en charge</button>
                                            </form>
                                            @endif
                                            @if($ticket->status === 'in_progress')
                                            <form method="POST" action="{{ route('admin.support-tickets.update-status', $ticket) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Marquer résolu</button>
                                            </form>
                                            @endif
                                            @if($ticket->status === 'resolved')
                                            <form method="POST" action="{{ route('admin.support-tickets.update-status', $ticket) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="closed">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Fermer</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.support-tickets.reopen', $ticket) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-yellow-700 hover:bg-gray-100">Rouvrir</button>
                                            </form>
                                            @endif
                                            @if(!$ticket->assigned_to)
                                            <form method="POST" action="{{ route('admin.support-tickets.assign', $ticket) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-blue-700 hover:bg-gray-100">M'assigner</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($ticket->status === 'closed')
                                <form method="POST" action="{{ route('admin.support-tickets.destroy', $ticket) }}" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ?')">
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
                        Aucun ticket trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $tickets->links() }}
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