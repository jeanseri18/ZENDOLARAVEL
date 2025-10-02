@extends('admin.layouts.app')

@section('title', 'Modifier le Colis - ' . $package->tracking_number)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier le Colis</h1>
            <p class="text-gray-600">Modifier les informations du colis {{ $package->tracking_number }}</p>
        </div>
        <div>
            <a href="{{ route('admin.packages.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <form method="POST" action="{{ route('admin.packages.update', $package->package_id) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
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
                                <option value="{{ $user->user_id }}" {{ old('sender_id', $package->sender_id) == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('sender_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du destinataire *</label>
                        <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', $package->recipient_name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('recipient_name') border-red-500 @enderror">
                        @error('recipient_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone du destinataire *</label>
                        <input type="tel" name="recipient_phone" id="recipient_phone" value="{{ old('recipient_phone', $package->recipient_phone) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('recipient_phone') border-red-500 @enderror">
                        @error('recipient_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="recipient_email" class="block text-sm font-medium text-gray-700 mb-1">Email du destinataire *</label>
                        <input type="email" name="recipient_email" id="recipient_email" value="{{ old('recipient_email', $package->recipient_email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('recipient_email') border-red-500 @enderror">
                        @error('recipient_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="recipient_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse du destinataire *</label>
                        <input type="text" name="recipient_address" id="recipient_address" value="{{ old('recipient_address', $package->recipient_address) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('recipient_address') border-red-500 @enderror">
                        @error('recipient_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label for="package_description" class="block text-sm font-medium text-gray-700 mb-1">Description du colis *</label>
                    <textarea name="package_description" id="package_description" required rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('package_description') border-red-500 @enderror">{{ old('package_description', $package->package_description) }}</textarea>
                    @error('package_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                        <select name="category" id="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="documents" {{ old('category', $package->category) == 'documents' ? 'selected' : '' }}>Documents</option>
                            <option value="electronics" {{ old('category', $package->category) == 'electronics' ? 'selected' : '' }}>Électronique</option>
                            <option value="clothing" {{ old('category', $package->category) == 'clothing' ? 'selected' : '' }}>Vêtements</option>
                            <option value="food" {{ old('category', $package->category) == 'food' ? 'selected' : '' }}>Nourriture</option>
                            <option value="medicine" {{ old('category', $package->category) == 'medicine' ? 'selected' : '' }}>Médicaments</option>
                            <option value="fragile" {{ old('category', $package->category) == 'fragile' ? 'selected' : '' }}>Fragile</option>
                            <option value="other" {{ old('category', $package->category) == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priorité *</label>
                        <select name="priority" id="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                            <option value="">Sélectionner une priorité</option>
                            <option value="standard" {{ old('priority', $package->priority) == 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="express" {{ old('priority', $package->priority) == 'express' ? 'selected' : '' }}>Express</option>
                            <option value="urgent" {{ old('priority', $package->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="delivery_type" class="block text-sm font-medium text-gray-700 mb-1">Type de livraison</label>
                        <select name="delivery_type" id="delivery_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('delivery_type') border-red-500 @enderror">
                            <option value="">Détermination automatique</option>
                            <option value="urban" {{ old('delivery_type', $package->delivery_type) == 'urban' ? 'selected' : '' }}>Urbain</option>
                            <option value="intercity" {{ old('delivery_type', $package->delivery_type) == 'intercity' ? 'selected' : '' }}>Intercité</option>
                            <option value="international" {{ old('delivery_type', $package->delivery_type) == 'international' ? 'selected' : '' }}>International</option>
                        </select>
                        @error('delivery_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Si non spécifié, le type sera déterminé automatiquement selon les villes</p>
                    </div>
                    
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Poids (kg) *</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight', $package->weight) }}" required min="0.1" max="50" step="0.1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight') border-red-500 @enderror">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="declared_value" class="block text-sm font-medium text-gray-700 mb-1">Valeur déclarée (XOF)</label>
                        <input type="number" name="declared_value" id="declared_value" value="{{ old('declared_value', $package->declared_value) }}" min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('declared_value') border-red-500 @enderror">
                        @error('declared_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="dimensions_length" class="block text-sm font-medium text-gray-700 mb-1">Longueur (cm)</label>
                        <input type="number" name="dimensions_length" id="dimensions_length" value="{{ old('dimensions_length', $package->dimensions_length) }}" min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dimensions_length') border-red-500 @enderror">
                        @error('dimensions_length')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="dimensions_width" class="block text-sm font-medium text-gray-700 mb-1">Largeur (cm)</label>
                        <input type="number" name="dimensions_width" id="dimensions_width" value="{{ old('dimensions_width', $package->dimensions_width) }}" min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dimensions_width') border-red-500 @enderror">
                        @error('dimensions_width')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="dimensions_height" class="block text-sm font-medium text-gray-700 mb-1">Hauteur (cm)</label>
                        <input type="number" name="dimensions_height" id="dimensions_height" value="{{ old('dimensions_height', $package->dimensions_height) }}" min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dimensions_height') border-red-500 @enderror">
                        @error('dimensions_height')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Informations de livraison -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de livraison</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pickup_city" class="block text-sm font-medium text-gray-700 mb-1">Ville de collecte *</label>
                        <input type="text" name="pickup_city" id="pickup_city" value="{{ old('pickup_city', $package->pickup_city) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_city') border-red-500 @enderror">
                        @error('pickup_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="delivery_city" class="block text-sm font-medium text-gray-700 mb-1">Ville de livraison *</label>
                        <input type="text" name="delivery_city" id="delivery_city" value="{{ old('delivery_city', $package->delivery_city) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('delivery_city') border-red-500 @enderror">
                        @error('delivery_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pickup_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse de collecte *</label>
                        <textarea name="pickup_address" id="pickup_address" rows="2" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_address') border-red-500 @enderror">{{ old('pickup_address', $package->pickup_address) }}</textarea>
                        @error('pickup_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse de livraison *</label>
                        <textarea name="delivery_address" id="delivery_address" rows="2" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('delivery_address') border-red-500 @enderror">{{ old('delivery_address', $package->delivery_address) }}</textarea>
                        @error('delivery_address')
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
                        <input type="checkbox" name="fragile" id="fragile" value="1" {{ old('fragile', $package->fragile) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="fragile" class="ml-2 block text-sm text-gray-900">
                            Colis fragile
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="express_delivery" id="express_delivery" value="1" {{ old('express_delivery', $package->express_delivery) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="express_delivery" class="ml-2 block text-sm text-gray-900">
                            Livraison express
                        </label>
                    </div>
                </div>
                
                <div>
                    <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-1">Instructions spéciales</label>
                    <textarea name="special_instructions" id="special_instructions" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('special_instructions') border-red-500 @enderror"
                              placeholder="Instructions particulières pour la livraison...">{{ old('special_instructions', $package->special_instructions) }}</textarea>
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
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection