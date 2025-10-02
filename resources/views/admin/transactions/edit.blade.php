@extends('admin.layouts.app')

@section('title', 'Modifier la Transaction')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier la Transaction</h1>
            <p class="text-gray-600">Transaction #{{ $transaction->payment_reference }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.transactions.show', $transaction->transaction_id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Voir les détails
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.transactions.update', $transaction->transaction_id) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Transaction Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de la transaction</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Référence</label>
                        <input type="text" value="{{ $transaction->payment_reference }}" disabled 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <input type="text" value="{{ $transaction->type === 'payment' ? 'Paiement' : 'Remboursement' }}" disabled 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de création</label>
                        <input type="text" value="{{ $transaction->created_at->format('d/m/Y à H:i') }}" disabled 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations modifiables</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sender_id" class="block text-sm font-medium text-gray-700 mb-1">Expéditeur *</label>
                        <select name="sender_id" id="sender_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('sender_id') border-red-500 @enderror">
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}" {{ $transaction->sender_id == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('sender_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Colis associé</label>
                        <select name="package_id" id="package_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('package_id') border-red-500 @enderror">
                            <option value="">Aucun colis associé</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ $transaction->package_id == $package->id ? 'selected' : '' }}>
                                    {{ $package->tracking_number }} - {{ $package->pickup_city }} → {{ $package->delivery_city }}
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Montant (XOF) *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $transaction->amount) }}" required min="0" step="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Méthode de paiement *</label>
                        <select name="payment_method" id="payment_method" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('payment_method') border-red-500 @enderror">
                            <option value="mobile_money" {{ $transaction->payment_method == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="bank_transfer" {{ $transaction->payment_method == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                            <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="card" {{ $transaction->payment_method == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Discount Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de remise</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="promo_code_id" class="block text-sm font-medium text-gray-700 mb-1">Code promo</label>
                        <select name="promo_code_id" id="promo_code_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('promo_code_id') border-red-500 @enderror">
                            <option value="">Aucun code promo</option>
                            @foreach($promoCodes as $promoCode)
                                <option value="{{ $promoCode->id }}" {{ $transaction->promo_code_id == $promoCode->id ? 'selected' : '' }}>
                                    {{ $promoCode->code }} ({{ $promoCode->discount_type === 'percentage' ? $promoCode->discount_value . '%' : number_format($promoCode->discount_value, 0, ',', ' ') . ' XOF' }})
                                </option>
                            @endforeach
                        </select>
                        @error('promo_code_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant de la remise (XOF)</label>
                        <input type="number" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', $transaction->discount_amount) }}" min="0" step="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('discount_amount') border-red-500 @enderror">
                        @error('discount_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status and Notes -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Statut et notes</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                    <select name="status" id="status" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            onchange="handleStatusChange()">
                        <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ $transaction->status == 'processing' ? 'selected' : '' }}>En cours de traitement</option>
                        <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Complétée</option>
                        <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Échouée</option>
                        <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        @if($transaction->type === 'payment')
                            <option value="refunded" {{ $transaction->status == 'refunded' ? 'selected' : '' }}>Remboursée</option>
                        @endif
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Attention: Changer le statut peut affecter les processus automatiques</p>
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes internes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="Notes internes sur cette transaction...">{{ old('notes', $transaction->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Current Status Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informations sur le statut actuel</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p><strong>Statut actuel:</strong> 
                            @switch($transaction->status)
                                @case('pending') En attente @break
                                @case('processing') En cours de traitement @break
                                @case('completed') Complétée @break
                                @case('failed') Échouée @break
                                @case('cancelled') Annulée @break
                                @case('refunded') Remboursée @break
                                @default {{ $transaction->status }}
                            @endswitch
                        </p>
                        @if($transaction->processed_at)
                            <p><strong>Traitée le:</strong> {{ $transaction->processed_at->format('d/m/Y à H:i') }}</p>
                        @endif
                        @if($transaction->refund_transaction)
                            <p><strong>Transaction de remboursement:</strong> #{{ $transaction->refund_transaction->payment_reference }}</p>
                        @endif
                        @if($transaction->original_transaction)
                            <p><strong>Transaction originale:</strong> #{{ $transaction->original_transaction->payment_reference }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.transactions.show', $transaction->transaction_id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
function handleStatusChange() {
    const status = document.getElementById('status').value;
    const originalStatus = '{{ $transaction->status }}';
    
    if (status !== originalStatus) {
        if (status === 'completed' || status === 'failed' || status === 'cancelled') {
            if (!confirm('Êtes-vous sûr de vouloir changer le statut vers "' + status + '" ? Cette action peut déclencher des processus automatiques.')) {
                document.getElementById('status').value = originalStatus;
                return;
            }
        }
    }
}

// Auto-calculate discount when promo code is selected
document.getElementById('promo_code_id').addEventListener('change', function() {
    const promoCodeId = this.value;
    const discountInput = document.getElementById('discount_amount');
    
    if (promoCodeId) {
        discountInput.readOnly = true;
        discountInput.style.backgroundColor = '#f9fafb';
    } else {
        discountInput.readOnly = false;
        discountInput.style.backgroundColor = 'white';
    }
});

// Clear promo code when manual discount is entered
document.getElementById('discount_amount').addEventListener('input', function() {
    if (this.value > 0 && !this.readOnly) {
        document.getElementById('promo_code_id').value = '';
    }
});

// Validate that discount doesn't exceed amount
document.getElementById('amount').addEventListener('input', validateDiscount);
document.getElementById('discount_amount').addEventListener('input', validateDiscount);

function validateDiscount() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    
    if (discount > amount) {
        document.getElementById('discount_amount').setCustomValidity('La remise ne peut pas être supérieure au montant');
    } else {
        document.getElementById('discount_amount').setCustomValidity('');
    }
}

// Initialize promo code state
window.addEventListener('load', function() {
    const promoCodeSelect = document.getElementById('promo_code_id');
    if (promoCodeSelect.value) {
        const discountInput = document.getElementById('discount_amount');
        discountInput.readOnly = true;
        discountInput.style.backgroundColor = '#f9fafb';
    }
});
</script>
@endsection