@extends('admin.layouts.app')

@section('title', 'Créer un Voyageur')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Créer un Nouveau Voyageur</h1>
            <p class="text-gray-600">Ajoutez un nouveau voyageur à la plateforme</p>
        </div>
        <a href="{{ route('admin.travelers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            Retour à la liste
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('admin.travelers.store') }}" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Selection -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Sélection de l'Utilisateur</h3>
            </div>
            
            <div class="md:col-span-2">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Utilisateur *</label>
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
            
            <!-- Vehicle Information -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du Véhicule</h3>
            </div>
            
            <div>
                <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Type de véhicule *</label>
                <select name="vehicle_type" id="vehicle_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_type') border-red-500 @enderror">
                    <option value="">Sélectionner un type</option>
                    <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Voiture</option>
                    <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Camionnette</option>
                    <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Camion</option>
                    <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Moto</option>
                    <option value="bicycle" {{ old('vehicle_type') == 'bicycle' ? 'selected' : '' }}>Vélo</option>
                </select>
                @error('vehicle_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="vehicle_model" class="block text-sm font-medium text-gray-700 mb-1">Modèle du véhicule</label>
                <input type="text" name="vehicle_model" id="vehicle_model" value="{{ old('vehicle_model') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_model') border-red-500 @enderror">
                @error('vehicle_model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="vehicle_year" class="block text-sm font-medium text-gray-700 mb-1">Année du véhicule</label>
                <input type="number" name="vehicle_year" id="vehicle_year" value="{{ old('vehicle_year') }}" min="1900" max="{{ date('Y') + 1 }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_year') border-red-500 @enderror">
                @error('vehicle_year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">Plaque d'immatriculation</label>
                <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('license_plate') border-red-500 @enderror">
                @error('license_plate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="vehicle_color" class="block text-sm font-medium text-gray-700 mb-1">Couleur du véhicule</label>
                <input type="text" name="vehicle_color" id="vehicle_color" value="{{ old('vehicle_color') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_color') border-red-500 @enderror">
                @error('vehicle_color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Capacity Information -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Capacité de Transport</h3>
            </div>
            
            <div>
                <label for="max_weight" class="block text-sm font-medium text-gray-700 mb-1">Poids maximum (kg)</label>
                <input type="number" name="max_weight" id="max_weight" value="{{ old('max_weight') }}" min="0" step="0.1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_weight') border-red-500 @enderror">
                @error('max_weight')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="max_volume" class="block text-sm font-medium text-gray-700 mb-1">Volume maximum (m³)</label>
                <input type="number" name="max_volume" id="max_volume" value="{{ old('max_volume') }}" min="0" step="0.1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_volume') border-red-500 @enderror">
                @error('max_volume')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Legal Information -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Légales</h3>
            </div>
            
            <div>
                <label for="insurance_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro d'assurance</label>
                <input type="text" name="insurance_number" id="insurance_number" value="{{ old('insurance_number') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('insurance_number') border-red-500 @enderror">
                @error('insurance_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="driver_license" class="block text-sm font-medium text-gray-700 mb-1">Numéro de permis de conduire</label>
                <input type="text" name="driver_license" id="driver_license" value="{{ old('driver_license') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('driver_license') border-red-500 @enderror">
                @error('driver_license')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status and Notes -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut et Notes</h3>
            </div>
            
            <div>
                <label for="verification_status" class="block text-sm font-medium text-gray-700 mb-1">Statut de vérification</label>
                <select name="verification_status" id="verification_status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('verification_status') border-red-500 @enderror">
                    <option value="pending" {{ old('verification_status', 'pending') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="verified" {{ old('verification_status') == 'verified' ? 'selected' : '' }}>Vérifié</option>
                    <option value="rejected" {{ old('verification_status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                </select>
                @error('verification_status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Account Settings -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Paramètres du Compte</h3>
            </div>
            
            <div class="md:col-span-2">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_verified" id="is_verified" value="1" 
                               {{ old('is_verified') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_verified" class="ml-2 block text-sm text-gray-900">
                            Voyageur vérifié
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Compte actif
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.travelers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                Créer le voyageur
            </button>
        </div>
    </form>
</div>
@endsection