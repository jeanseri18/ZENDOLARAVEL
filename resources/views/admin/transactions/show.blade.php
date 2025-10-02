@extends('admin.layouts.app')

@section('title', 'Détails de la Transaction')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transaction #{{ $transaction->payment_reference }}</h1>
            <p class="text-gray-600">Détails complets de la transaction</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
            <a href="{{ route('admin.transactions.edit', $transaction) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Modifier
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Transaction Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de base</h3>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Référence de paiement</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">#{{ $transaction->payment_reference }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $transaction->type === 'payment' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $transaction->type === 'payment' ? 'Paiement' : 'Remboursement' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Montant</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Méthode de paiement</dt>
                        <dd class="mt-1">
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
                                    @case('bank_transfer') Virement bancaire @break
                                    @case('cash') Espèces @break
                                    @case('card') Carte bancaire @break
                                    @default {{ $transaction->payment_method }}
                                @endswitch
                            </span>
                        </dd>
                    </div>
                    @if($transaction->discount_amount > 0)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Remise appliquée</dt>
                        <dd class="mt-1 text-sm text-green-600 font-medium">-{{ number_format($transaction->discount_amount, 0, ',', ' ') }} FCFA</dd>
                    </div>
                    @endif
                    @if($transaction->promo_code)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Code promo utilisé</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $transaction->promo_code->code }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @if($transaction->processed_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de traitement</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $transaction->processed_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Utilisateur</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-lg font-medium text-gray-700">{{ substr($transaction->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900">{{ $transaction->user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $transaction->user->email }}</p>
                        @if($transaction->user->phone)
                            <p class="text-sm text-gray-500">{{ $transaction->user->phone }}</p>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('admin.users.show', $transaction->user) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Voir le profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Information -->
        @if($transaction->package)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Colis associé</h3>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Numéro de suivi</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $transaction->package->tracking_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Statut du colis</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @switch($transaction->package->status)
                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                    @case('confirmed') bg-blue-100 text-blue-800 @break
                                    @case('picked_up') bg-indigo-100 text-indigo-800 @break
                                    @case('in_transit') bg-purple-100 text-purple-800 @break
                                    @case('delivered') bg-green-100 text-green-800 @break
                                    @case('cancelled') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($transaction->package->status)
                                    @case('pending') En attente @break
                                    @case('confirmed') Confirmé @break
                                    @case('picked_up') Récupéré @break
                                    @case('in_transit') En transit @break
                                    @case('delivered') Livré @break
                                    @case('cancelled') Annulé @break
                                    @default {{ $transaction->package->status }}
                                @endswitch
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Itinéraire</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $transaction->package->origin_city }} → {{ $transaction->package->destination_city }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Poids</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $transaction->package->weight }} kg</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $transaction->package->description }}</dd>
                    </div>
                </dl>
                <div class="mt-4">
                    <a href="{{ route('admin.packages.show', $transaction->package) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir les détails du colis
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Transaction Notes -->
        @if($transaction->notes)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Notes</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-gray-700">{{ $transaction->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Statut</h3>
            </div>
            <div class="px-6 py-4">
                <div class="text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
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
                            @case('processing') En cours de traitement @break
                            @case('completed') Complétée @break
                            @case('failed') Échouée @break
                            @case('cancelled') Annulée @break
                            @case('refunded') Remboursée @break
                            @default {{ $transaction->status }}
                        @endswitch
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actions rapides</h3>
            </div>
            <div class="px-6 py-4 space-y-3">
                @if($transaction->status === 'pending')
                    <form method="POST" action="{{ route('admin.transactions.update-status', $transaction) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Marquer comme complétée
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.transactions.update-status', $transaction) }}" class="w-full" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir marquer cette transaction comme échouée ?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="failed">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Marquer comme échouée
                        </button>
                    </form>
                @endif
                
                @if($transaction->status === 'completed' && $transaction->type === 'payment')
                    <form method="POST" action="{{ route('admin.transactions.refund', $transaction) }}" class="w-full" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir rembourser cette transaction ? Cette action créera une nouvelle transaction de remboursement.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Rembourser
                        </button>
                    </form>
                @endif
                
                @if($transaction->status !== 'cancelled' && $transaction->status !== 'refunded')
                    <form method="POST" action="{{ route('admin.transactions.update-status', $transaction) }}" class="w-full" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction ?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Annuler
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Related Transactions -->
        @if($transaction->type === 'payment' && $transaction->refund_transaction)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Transaction de remboursement</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">#{{ $transaction->refund_transaction->payment_reference }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->refund_transaction->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <a href="{{ route('admin.transactions.show', $transaction->refund_transaction) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir
                    </a>
                </div>
            </div>
        </div>
        @endif
        
        @if($transaction->type === 'refund' && $transaction->original_transaction)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Transaction originale</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">#{{ $transaction->original_transaction->payment_reference }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->original_transaction->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <a href="{{ route('admin.transactions.show', $transaction->original_transaction) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection