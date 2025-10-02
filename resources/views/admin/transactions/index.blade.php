@extends('admin.layouts.app')

@section('title', 'Gestion des Transactions')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
            <p class="text-gray-600">Gérez toutes les transactions de la plateforme</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.transactions.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Exporter CSV
            </a>
            <a href="{{ route('admin.transactions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Nouvelle Transaction
            </a>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Chiffre d'affaires</p>
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($summary['total_revenue'] ?? 0, 0, ',', ' ') }} XOF</p>
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
                <p class="text-sm font-medium text-gray-600">Complétées</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $summary['completed'] ?? 0 }}</p>
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
                <p class="text-sm font-medium text-gray-600">En attente</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $summary['pending'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Échouées</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $summary['failed'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Référence, utilisateur..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complétée</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échouée</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursée</option>
                </select>
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les types</option>
                    <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Paiement</option>
                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Remboursement</option>
                </select>
            </div>
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
                <select name="payment_method" id="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les méthodes</option>
                    <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Virement</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Carte</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
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

<!-- Transactions Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Transaction
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Utilisateur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Colis
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Montant
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Méthode
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
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
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $transaction->payment_reference }}</div>
                        <div class="text-sm text-gray-500">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                {{ $transaction->type === 'payment' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $transaction->type === 'payment' ? 'Paiement' : 'Remboursement' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-700">{{ substr($transaction->sender->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->sender->name }}</div>
                                <div class="text-sm text-gray-500">{{ $transaction->sender->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($transaction->package)
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->package->tracking_number }}</div>
                            <div class="text-sm text-gray-500">{{ $transaction->package->pickup_city }} → {{ $transaction->package->delivery_city }}</div>
                        @else
                            <span class="text-sm text-gray-400">Aucun colis</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($transaction->amount, 0, ',', ' ') }} XOF</div>
                        @if($transaction->discount_amount > 0)
                            <div class="text-xs text-green-600">-{{ number_format($transaction->discount_amount, 0, ',', ' ') }} XOF</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($transaction->payment_method)
                                @case('mobile_money') bg-green-100 text-green-800 @break
                                @case('bank_transfer') bg-blue-100 text-blue-800 @break
                                @case('cash') bg-yellow-100 text-yellow-800 @break
                                @case('card') bg-purple-100 text-purple-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($transaction->payment_method)
                                @case('mobile_money') Mobile Money @break
                                @case('bank_transfer') Virement @break
                                @case('cash') Espèces @break
                                @case('card') Carte @break
                                @default {{ $transaction->payment_method }}
                            @endswitch
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($transaction->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('processing') bg-blue-100 text-blue-800 @break
                                @case('completed') bg-green-100 text-green-800 @break
                                @case('failed') bg-red-100 text-red-800 @break
                                @case('cancelled') bg-gray-100 text-gray-800 @break
                                @case('refunded') bg-orange-100 text-orange-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($transaction->status)
                                @case('pending') En attente @break
                                @case('processing') En cours @break
                                @case('completed') Complétée @break
                                @case('failed') Échouée @break
                                @case('cancelled') Annulée @break
                                @case('refunded') Remboursée @break
                                @default {{ $transaction->status }}
                            @endswitch
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                        @if($transaction->processed_at)
                            <div class="text-xs text-green-600">Traitée: {{ $transaction->processed_at->format('d/m/Y H:i') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.transactions.show', $transaction->transaction_id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            <a href="{{ route('admin.transactions.edit', $transaction->transaction_id) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            
                            @if($transaction->status === 'pending')
                                <!-- Quick Actions Dropdown -->
                                <div class="relative inline-block text-left">
                                    <button type="button" class="text-green-600 hover:text-green-900" onclick="toggleDropdown('actions-{{ $transaction->id }}')">
                                        Actions
                                    </button>
                                    <div id="actions-{{ $transaction->id }}" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <form method="POST" action="{{ route('admin.transactions.update-status', $transaction) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-gray-100">Marquer complétée</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.transactions.update-status', $transaction) }}" class="block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="failed">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">Marquer échouée</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($transaction->status === 'completed' && $transaction->type === 'payment')
                                <form method="POST" action="{{ route('admin.transactions.refund', $transaction) }}" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir rembourser cette transaction ?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-orange-600 hover:text-orange-900">Rembourser</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Aucune transaction trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $transactions->links() }}
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