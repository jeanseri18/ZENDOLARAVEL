@extends('admin.layouts.app')

@section('title', 'Modifier le Ticket #' . $ticket->ticket_number)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier le Ticket #{{ $ticket->ticket_number }}</h1>
            <p class="text-gray-600">Modifier les détails du ticket de support</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.support-tickets.show', $ticket->ticket_id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Voir le ticket
            </a>
            <a href="{{ route('admin.support-tickets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('admin.support-tickets.update', $ticket->ticket_id) }}" class="p-6">
        @csrf
        @method('PUT')
        
        <!-- Ticket Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Numéro:</span>
                    <span class="text-gray-900">#{{ $ticket->ticket_number }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Créé le:</span>
                    <span class="text-gray-900">{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y à H:i') : 'Date non disponible' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Utilisateur:</span>
                    @if($ticket->user)
                        <span class="text-gray-900">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</span>
                    @else
                        <span class="text-gray-900">Unknown User</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Basic Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject', $ticket->subject) }}" required 
                       placeholder="Sujet du ticket"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Colis associé</label>
                <select name="package_id" id="package_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('package_id') border-red-500 @enderror">
                    <option value="">Aucun colis</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->package_id }}" {{ old('package_id', $ticket->package_id) == $package->package_id ? 'selected' : '' }}>
                            {{ $package->tracking_number }} - {{ $package->pickup_city }} → {{ $package->delivery_city }}
                        </option>
                    @endforeach
                </select>
                @error('package_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Category and Priority -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                <select name="category" id="category" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                    <option value="">Sélectionner une catégorie</option>
                    <option value="delivery_issue" {{ old('category', $ticket->category) == 'delivery_issue' ? 'selected' : '' }}>Problème de livraison</option>
                    <option value="payment_issue" {{ old('category', $ticket->category) == 'payment_issue' ? 'selected' : '' }}>Problème de paiement</option>
                    <option value="account_issue" {{ old('category', $ticket->category) == 'account_issue' ? 'selected' : '' }}>Problème de compte</option>
                    <option value="technical_issue" {{ old('category', $ticket->category) == 'technical_issue' ? 'selected' : '' }}>Problème technique</option>
                    <option value="other" {{ old('category', $ticket->category) == 'other' ? 'selected' : '' }}>Autre</option>
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
                    <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>Haute</option>
                    <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
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
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $ticket->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Status and Assignment -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                <select name="status" id="status" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Ouvert</option>
                    <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Résolu</option>
                    <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Fermé</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigner à</label>
                <select name="assigned_to" id="assigned_to" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('assigned_to') border-red-500 @enderror">
                    <option value="">Non assigné</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->user_id }}" {{ old('assigned_to', $ticket->assigned_to) == $admin->user_id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Resolution -->
        <div id="resolution-section" class="mb-6" style="{{ in_array(old('status', $ticket->status), ['resolved', 'closed']) ? 'display: block;' : 'display: none;' }}">
            <label for="resolution" class="block text-sm font-medium text-gray-700 mb-2">
                Résolution 
                <span id="resolution-required" class="text-red-500" style="{{ in_array(old('status', $ticket->status), ['resolved', 'closed']) ? 'display: inline;' : 'display: none;' }}">*</span>
            </label>
            <textarea name="resolution" id="resolution" rows="4" 
                      placeholder="Décrivez la résolution du problème..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('resolution') border-red-500 @enderror">{{ old('resolution', $ticket->resolution) }}</textarea>
            @error('resolution')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes internes (optionnel)</label>
            <textarea name="notes" id="notes" rows="3" 
                      placeholder="Notes internes pour l'équipe de support..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $ticket->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Ces notes ne sont visibles que par l'équipe de support</p>
        </div>
        
        <!-- Current Status Info -->
        @if($ticket->resolved_at)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-green-800">Ticket résolu</p>
                    <p class="text-sm text-green-700">Résolu le {{ $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y à H:i') : 'Date non disponible' }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.support-tickets.show', $ticket->ticket_id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
// Show/hide resolution section based on status
document.getElementById('status').addEventListener('change', function() {
    const resolutionSection = document.getElementById('resolution-section');
    const resolutionField = document.getElementById('resolution');
    const resolutionRequired = document.getElementById('resolution-required');
    
    if (this.value === 'resolved' || this.value === 'closed') {
        resolutionSection.style.display = 'block';
        resolutionField.required = true;
        resolutionRequired.style.display = 'inline';
    } else {
        resolutionSection.style.display = 'none';
        resolutionField.required = false;
        resolutionRequired.style.display = 'none';
    }
});

// Auto-assign to current user if taking in progress
document.getElementById('status').addEventListener('change', function() {
    const assignedSelect = document.getElementById('assigned_to');
    if (this.value === 'in_progress' && !assignedSelect.value) {
        // Auto-assign to current user if not already assigned
        const currentUserId = '{{ auth()->id() }}';
        const currentUserOption = assignedSelect.querySelector(`option[value="${currentUserId}"]`);
        if (currentUserOption) {
            assignedSelect.value = currentUserId;
        }
    }
});

// Confirm status change to closed
document.querySelector('form').addEventListener('submit', function(e) {
    const status = document.getElementById('status').value;
    const originalStatus = '{{ $ticket->status }}';
    
    if (status === 'closed' && originalStatus !== 'closed') {
        if (!confirm('Êtes-vous sûr de vouloir fermer ce ticket ? Cette action ne peut pas être annulée facilement.')) {
            e.preventDefault();
        }
    }
});
</script>
@endsection