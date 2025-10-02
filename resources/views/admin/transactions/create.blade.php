@extends('admin.layouts.app')

@section('title', 'Nouvelle Transaction')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nouvelle Transaction</h1>
            <p class="text-gray-600">Créer une nouvelle transaction manuellement</p>
        </div>
        <div>
            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.transactions.store') }}" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de base</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sender_id" class="block text-sm font-medium text-gray-700 mb-1">Expéditeur *</label>
                        <select name="sender_id" id="sender_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sender_id') border-red-500 @enderror">
                            <option value="">Sélectionner un expéditeur</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('sender_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('sender_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Colis (optionnel)</label>
                        <select name="package_id" id="package_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('package_id') border-red-500 @enderror">
                            <option value="">Aucun colis associé</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->tracking_number }} - {{ $package->pickup_city }} → {{ $package->delivery_city }}
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" id="type" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">Sélectionner le type</option>
                            <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Paiement</option>
                            <option value="refund" {{ old('type') == 'refund' ? 'selected' : '' }}>Remboursement</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Montant (XOF) *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" step="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Méthode de paiement *</label>
                        <select name="payment_method" id="payment_method" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('payment_method') border-red-500 @enderror">
                            <option value="">Sélectionner la méthode</option>
                            <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
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
                <h3 class="text-lg font-medium text-gray-900">Remise (optionnel)</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="promo_code_id" class="block text-sm font-medium text-gray-700 mb-1">Code promo</label>
                        <select name="promo_code_id" id="promo_code_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('promo_code_id') border-red-500 @enderror">
                            <option value="">Aucun code promo</option>
                            @foreach($promoCodes as $promoCode)
                                <option value="{{ $promoCode->id }}" {{ old('promo_code_id') == $promoCode->id ? 'selected' : '' }}>
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
                        <input type="number" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}" min="0" step="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('discount_amount') border-red-500 @enderror">
                        @error('discount_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Laissez 0 si aucune remise ou si vous utilisez un code promo</p>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>En cours de traitement</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Complétée</option>
                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Échouée</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes internes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="Notes internes sur cette transaction...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Créer la transaction
            </button>
        </div>
    </form>
</div>

<script>
// Auto-calculate discount when promo code is selected
document.getElementById('promo_code_id').addEventListener('change', function() {
    const promoCodeId = this.value;
    const amountInput = document.getElementById('amount');
    const discountInput = document.getElementById('discount_amount');
    
    if (promoCodeId && amountInput.value) {
        // In a real application, you would make an AJAX call to calculate the discount
        // For now, we'll just clear the manual discount amount
        discountInput.value = 0;
        discountInput.readOnly = true;
    } else {
        discountInput.readOnly = false;
    }
});

// Clear promo code when manual discount is entered
document.getElementById('discount_amount').addEventListener('input', function() {
    if (this.value > 0) {
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
</script>
@endsection