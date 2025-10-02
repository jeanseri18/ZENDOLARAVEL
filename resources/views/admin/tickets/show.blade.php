@extends('admin.layouts.app')

@section('title', 'Détails du Ticket #' . $ticket->ticket_number)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ticket #{{ $ticket->ticket_number }}</h1>
            <p class="text-gray-600">Créé le {{ $ticket->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.support-tickets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
            <a href="{{ route('admin.support-tickets.edit', $ticket) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Modifier
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Ticket Details -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Détails du Ticket</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                        <p class="text-sm text-gray-900">{{ $ticket->subject }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <p class="text-sm text-gray-900">{{ ucfirst($ticket->category) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
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
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>
                
                @if($ticket->resolution)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Résolution</label>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $ticket->resolution }}</p>
                        @if($ticket->resolved_at)
                        <p class="text-xs text-green-600 mt-2">Résolu le {{ $ticket->resolved_at->format('d/m/Y à H:i') }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Related Package -->
        @if($ticket->package)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Colis Associé</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $ticket->package->tracking_number }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->package->origin_city }} → {{ $ticket->package->destination_city }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->package->weight }}kg - {{ number_format($ticket->package->price, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($ticket->package->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('confirmed') bg-blue-100 text-blue-800 @break
                                @case('in_transit') bg-purple-100 text-purple-800 @break
                                @case('delivered') bg-green-100 text-green-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($ticket->package->status)
                                @case('pending') En attente @break
                                @case('confirmed') Confirmé @break
                                @case('in_transit') En transit @break
                                @case('delivered') Livré @break
                                @case('cancelled') Annulé @break
                                @default {{ $ticket->package->status }}
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.packages.show', $ticket->package) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir le colis →
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- User Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Utilisateur</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-lg font-medium text-gray-700">{{ substr($ticket->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $ticket->user->email }}</p>
                        @if($ticket->user->phone)
                        <p class="text-sm text-gray-500">{{ $ticket->user->phone }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $ticket->user) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir le profil →
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Assignment -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Attribution</h2>
            </div>
            <div class="p-6">
                @if($ticket->assignedTo)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-700">{{ substr($ticket->assignedTo->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $ticket->assignedTo->name }}</p>
                            <p class="text-sm text-gray-500">{{ $ticket->assignedTo->email }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 mb-4">Non assigné</p>
                @endif
                
                @if($ticket->status !== 'closed')
                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.support-tickets.assign', $ticket) }}">
                        @csrf
                        @method('PATCH')
                        <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" onchange="this.form.submit()">
                            <option value="">Sélectionner un admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        @if($ticket->status !== 'closed')
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Actions Rapides</h2>
            </div>
            <div class="p-6 space-y-3">
                @if($ticket->status === 'open')
                <form method="POST" action="{{ route('admin.support-tickets.update-status', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Prendre en charge
                    </button>
                </form>
                @endif
                
                @if($ticket->status === 'in_progress')
                <form method="POST" action="{{ route('admin.support-tickets.update-status', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="resolved">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Marquer comme résolu
                    </button>
                </form>
                @endif
                
                @if($ticket->status === 'resolved')
                <form method="POST" action="{{ route('admin.support-tickets.update-status', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="closed">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Fermer le ticket
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.support-tickets.reopen', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Rouvrir le ticket
                    </button>
                </form>
                @endif
                
                @if(!$ticket->assigned_to)
                <form method="POST" action="{{ route('admin.support-tickets.assign', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        M'assigner ce ticket
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Historique</h2>
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
                                            <p class="text-sm text-gray-500">Ticket créé par <span class="font-medium text-gray-900">{{ $ticket->user->name }}</span></p>
                                            <p class="text-xs text-gray-400">{{ $ticket->created_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        @if($ticket->assigned_to)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">Assigné à <span class="font-medium text-gray-900">{{ $ticket->assignedTo->name }}</span></p>
                                            <p class="text-xs text-gray-400">{{ $ticket->updated_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                        
                        @if($ticket->resolved_at)
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">Ticket résolu</p>
                                            <p class="text-xs text-gray-400">{{ $ticket->resolved_at->format('d/m/Y à H:i') }}</p>
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