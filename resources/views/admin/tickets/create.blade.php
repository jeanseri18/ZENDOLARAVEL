@extends('admin.layouts.app')

@section('title', 'Créer un Ticket de Support')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Créer un Ticket de Support</h1>
            <p class="text-gray-600">Créer un nouveau ticket de support pour un utilisateur</p>
        </div>
        <div>
            <a href="{{ route('admin.support-tickets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('admin.support-tickets.store') }}" class="p-6">
        @csrf
        
        <!-- User Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Utilisateur *</label>
                <select name="user_id" id="user_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror">
                    <option value="">Sélectionner un utilisateur</option>
                    @foreach($users as $user)
                        <option value="{{ $user->user_id }}" {{ old('user_id') == $user->user_id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Colis associé (optionnel)</label>
                <select name="package_id" id="package_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('package_id') border-red-500 @enderror">
                    <option value="">Aucun colis</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->package_id }}" {{ old('package_id') == $package->package_id ? 'selected' : '' }}>
                            {{ $package->tracking_number }} - {{ $package->pickup_city }} → {{ $package->delivery_city }}
                        </option>
                    @endforeach
                </select>
                @error('package_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Ticket Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required 
                       placeholder="Sujet du ticket"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                <select name="category" id="category" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                    <option value="">Sélectionner une catégorie</option>
                    <option value="delivery_issue" {{ old('category') == 'delivery_issue' ? 'selected' : '' }}>Problème de livraison</option>
                    <option value="payment_issue" {{ old('category') == 'payment_issue' ? 'selected' : '' }}>Problème de paiement</option>
                    <option value="account_issue" {{ old('category') == 'account_issue' ? 'selected' : '' }}>Problème de compte</option>
                    <option value="technical_issue" {{ old('category') == 'technical_issue' ? 'selected' : '' }}>Problème technique</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Autre</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priorité *</label>
                <select name="priority" id="priority" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                    <option value="">Sélectionner une priorité</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
                @error('priority')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
            <textarea name="description" id="description" rows="6" required 
                      placeholder="Décrivez le problème ou la demande en détail..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Assignment -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigner à (optionnel)</label>
                <select name="assigned_to" id="assigned_to" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('assigned_to') border-red-500 @enderror">
                    <option value="">Non assigné</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->user_id }}" {{ old('assigned_to') == $admin->user_id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut initial</label>
                <select name="status" id="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Ouvert</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Resolution (if status is resolved) -->
        <div id="resolution-section" class="mb-6" style="display: none;">
            <label for="resolution" class="block text-sm font-medium text-gray-700 mb-2">Résolution</label>
            <textarea name="resolution" id="resolution" rows="4" 
                      placeholder="Décrivez la résolution du problème..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('resolution') border-red-500 @enderror">{{ old('resolution') }}</textarea>
            @error('resolution')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.support-tickets.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Créer le Ticket
            </button>
        </div>
    </form>
</div>

<script>
// Show/hide resolution section based on status
document.getElementById('status').addEventListener('change', function() {
    const resolutionSection = document.getElementById('resolution-section');
    if (this.value === 'resolved' || this.value === 'closed') {
        resolutionSection.style.display = 'block';
        document.getElementById('resolution').required = true;
    } else {
        resolutionSection.style.display = 'none';
        document.getElementById('resolution').required = false;
    }
});

// Filter packages by selected user
document.getElementById('user_id').addEventListener('change', function() {
    const userId = this.value;
    const packageSelect = document.getElementById('package_id');
    
    // Reset package options
    packageSelect.innerHTML = '<option value="">Aucun colis</option>';
    
    if (userId) {
        // In a real application, you would make an AJAX call here
        // to fetch packages for the selected user
        console.log('Filter packages for user:', userId);
    }
});

// Auto-assign to current user if taking in progress
document.getElementById('status').addEventListener('change', function() {
    const assignedSelect = document.getElementById('assigned_to');
    if (this.value === 'in_progress' && !assignedSelect.value) {
        // Auto-assign to current user (you would need to pass the current user ID)
        // assignedSelect.value = '{{ auth()->id() }}';
    }
});
</script>
@endsection