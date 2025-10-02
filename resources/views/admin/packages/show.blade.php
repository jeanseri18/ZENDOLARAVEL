@extends('admin.layouts.app')

@section('title', 'Détails Colis - ' . $package->tracking_number)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Détails du Colis</h1>
            <p class="text-gray-600">Numéro de suivi: {{ $package->tracking_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.packages.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
            <a href="{{ route('admin.packages.edit', $package->package_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Modifier
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Package Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations du Colis</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de suivi</label>
                        <p class="text-sm text-gray-900 font-mono">{{ $package->tracking_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($package->status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('confirmed') bg-blue-100 text-blue-800 @break
                                @case('active') bg-green-100 text-green-800 @break
                                @case('in_transit') bg-purple-100 text-purple-800 @break
                                @case('delivered') bg-green-100 text-green-800 @break
                                @case('cancelled') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($package->status)
                                @case('pending') En attente @break
                                @case('confirmed') Confirmé @break
                                @case('active') Actif @break
                                @case('in_transit') En transit @break
                                @case('delivered') Livré @break
                                @case('cancelled') Annulé @break
                                @default {{ $package->status }}
                            @endswitch
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-sm text-gray-900">{{ $package->package_description ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <p class="text-sm text-gray-900">{{ $package->category ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poids</label>
                        <p class="text-sm text-gray-900">{{ $package->weight ? $package->weight . ' kg' : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                        <p class="text-sm text-gray-900">
                            @if($package->dimensions_length && $package->dimensions_width && $package->dimensions_height)
                                {{ $package->dimensions_length }} x {{ $package->dimensions_width }} x {{ $package->dimensions_height }} cm
                            @else
                                Non renseigné
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valeur déclarée</label>
                        <p class="text-sm text-gray-900">{{ $package->declared_value ? number_format($package->declared_value, 2) . ' XOF' : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de livraison</label>
                        <p class="text-sm text-gray-900">
                            @switch($package->delivery_type)
                                @case('urban') Urbaine @break
                                @case('intercity') Intercité @break
                                @case('international') Internationale @break
                                @default {{ $package->delivery_type ?? 'Non renseigné' }}
                            @endswitch
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($package->priority)
                                @case('low') bg-gray-100 text-gray-800 @break
                                @case('normal') bg-blue-100 text-blue-800 @break
                                @case('high') bg-orange-100 text-orange-800 @break
                                @case('urgent') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($package->priority)
                                @case('low') Faible @break
                                @case('normal') Normale @break
                                @case('high') Élevée @break
                                @case('urgent') Urgente @break
                                @default {{ $package->priority ?? 'Normale' }}
                            @endswitch
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fragile</label>
                        <p class="text-sm text-gray-900">{{ $package->fragile ? 'Oui' : 'Non' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature requise</label>
                        <p class="text-sm text-gray-900">{{ $package->requires_signature ? 'Oui' : 'Non' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frais de livraison estimés</label>
                        <p class="text-sm text-gray-900">{{ $package->estimated_delivery_fee ? number_format($package->estimated_delivery_fee, 2) . ' XOF' : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frais de livraison finaux</label>
                        <p class="text-sm text-gray-900">{{ $package->final_delivery_fee ? number_format($package->final_delivery_fee, 2) . ' XOF' : 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Adresses</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Adresse de collecte</h4>
                        <div class="bg-gray-50 p-3 rounded-md">
                            <p class="text-sm text-gray-900">{{ $package->pickup_address ?? 'Non renseigné' }}</p>
                            @if($package->pickup_city)
                                <p class="text-sm text-gray-600">{{ $package->pickup_city }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Adresse de livraison</h4>
                        <div class="bg-gray-50 p-3 rounded-md">
                            <p class="text-sm text-gray-900">{{ $package->delivery_address ?? 'Non renseigné' }}</p>
                            @if($package->delivery_city)
                                <p class="text-sm text-gray-600">{{ $package->delivery_city }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipient Info -->
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations du Destinataire</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <p class="text-sm text-gray-900">{{ $package->recipient_name ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <p class="text-sm text-gray-900">{{ $package->recipient_phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $package->recipient_email ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Sender Info -->
        @if($package->sender)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Expéditeur</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-700">{{ substr($package->sender->first_name, 0, 1) }}{{ substr($package->sender->last_name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">{{ $package->sender->first_name }} {{ $package->sender->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $package->sender->email }}</p>
                        @if($package->sender->phone_number)
                            <p class="text-sm text-gray-500">{{ $package->sender->phone_number }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $package->sender->user_id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir le profil →
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Traveler Info -->
        @if($package->traveler)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Livreur</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-green-700">{{ substr($package->traveler->user->first_name, 0, 1) }}{{ substr($package->traveler->user->last_name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">{{ $package->traveler->user->first_name }} {{ $package->traveler->user->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $package->traveler->user->email }}</p>
                        @if($package->traveler->user->phone_number)
                            <p class="text-sm text-gray-500">{{ $package->traveler->user->phone_number }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.travelers.show', $package->traveler->traveler_id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir le profil →
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Dates</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Créé le</label>
                    <p class="text-sm text-gray-900">{{ $package->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dernière modification</label>
                    <p class="text-sm text-gray-900">{{ $package->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions -->
@if($package->transactions && $package->transactions->count() > 0)
<div class="bg-white rounded-lg shadow mt-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Transactions ({{ $package->transactions->count() }})</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($package->transactions as $transaction)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ $transaction->transaction_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($transaction->transaction_status)
                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                @case('completed') bg-green-100 text-green-800 @break
                                @case('failed') bg-red-100 text-red-800 @break
                                @case('cancelled') bg-gray-100 text-gray-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($transaction->transaction_status)
                                @case('pending') En attente @break
                                @case('completed') Complétée @break
                                @case('failed') Échouée @break
                                @case('cancelled') Annulée @break
                                @default {{ $transaction->transaction_status }}
                            @endswitch
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.transactions.show', $transaction->transaction_id) }}" class="text-blue-600 hover:text-blue-900">
                            Voir
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Messages -->
@if($package->messages && $package->messages->count() > 0)
<div class="bg-white rounded-lg shadow mt-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Messages ({{ $package->messages->count() }})</h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @foreach($package->messages->take(5) as $message)
            <div class="border-l-4 border-blue-400 pl-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-900">{{ Str::limit($message->message_content, 100) }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $message->sent_at ? $message->sent_at->format('d/m/Y à H:i') : $message->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($message->message_type) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @if($package->messages->count() > 5)
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Et {{ $package->messages->count() - 5 }} autres messages...</p>
        </div>
        @endif
    </div>
</div>
@endif

@endsection