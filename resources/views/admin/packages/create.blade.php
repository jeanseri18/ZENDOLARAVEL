@extends('admin.layouts.app')

@section('title', 'Nouveau Colis')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nouveau Colis</h1>
            <p class="text-gray-600">Créer un nouveau colis manuellement</p>
        </div>
        <div>
            <a href="{{ route('admin.packages.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <form method="POST" action="{{ route('admin.packages.store') }}" class="space-y-6">
        @csrf
        
        <!-- Informations de base -->
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
                                <option value="{{ $user->user_id }}" {{ old('sender_id') == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('sender_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="receiver_id" class="block text-sm font-medium text-gray-700 mb-1">Destinataire *</label>
                        <select name="receiver_id" id="receiver_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('receiver_id') border-red-500 @enderror">
                            <option value="">Sélectionner un destinataire</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}" {{ old('receiver_id') == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('receiver_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre du colis *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                        <input type="text" name="category" id="category" value="{{ old('category') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité *</label>
                        <select name="priority" id="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                            <option value="">Sélectionner une priorité</option>
                            <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="express" {{ old('priority') == 'express' ? 'selected' : '' }}>Express</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Poids (kg) *</label>
                        <input type="number" name="weight_kg" id="weight_kg" value="{{ old('weight_kg') }}" required min="0.1" max="50" step="0.1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight_kg') border-red-500 @enderror">
                        @error('weight_kg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="value_euros" class="block text-sm font-medium text-gray-700 mb-1">Valeur (€) *</label>
                        <input type="number" name="value_euros" id="value_euros" value="{{ old('value_euros') }}" required min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('value_euros') border-red-500 @enderror">
                        @error('value_euros')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description du colis *</label>
                    <textarea name="description" id="description" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Décrivez le contenu du colis...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                    <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions') }}" placeholder="ex: 20x15x10 cm"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dimensions') border-red-500 @enderror">
                    @error('dimensions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Informations de localisation -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de localisation</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="origin_city" class="block text-sm font-medium text-gray-700 mb-1">Ville d'origine *</label>
                        <input type="text" name="origin_city" id="origin_city" value="{{ old('origin_city') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('origin_city') border-red-500 @enderror">
                        @error('origin_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="destination_city" class="block text-sm font-medium text-gray-700 mb-1">Ville de destination *</label>
                        <input type="text" name="destination_city" id="destination_city" value="{{ old('destination_city') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('destination_city') border-red-500 @enderror">
                        @error('destination_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations de date -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de date</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-1">Date de collecte *</label>
                        <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_date') border-red-500 @enderror">
                        @error('pickup_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="delivery_deadline" class="block text-sm font-medium text-gray-700 mb-1">Date limite de livraison *</label>
                        <input type="date" name="delivery_deadline" id="delivery_deadline" value="{{ old('delivery_deadline') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('delivery_deadline') border-red-500 @enderror">
                        @error('delivery_deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Exigences spéciales -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Exigences spéciales</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="fragile" id="fragile" value="1" {{ old('fragile') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="fragile" class="ml-2 block text-sm text-gray-900">
                            Colis fragile
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="requires_signature" id="requires_signature" value="1" {{ old('requires_signature') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="requires_signature" class="ml-2 block text-sm text-gray-900">
                            Signature requise à la livraison
                        </label>
                    </div>
                </div>
                
                <div>
                    <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-1">Instructions spéciales</label>
                    <textarea name="special_instructions" id="special_instructions" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('special_instructions') border-red-500 @enderror"
                              placeholder="Instructions spéciales pour la livraison...">{{ old('special_instructions') }}</textarea>
                    @error('special_instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Actions du formulaire -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.packages.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Créer le colis
            </button>
        </div>
    </form>
</div>
@endsection