@extends('admin.layouts.app')

@section('title', 'Modifier le Voyageur - ' . ($traveler->user ? $traveler->user->first_name . ' ' . $traveler->user->last_name : 'Unknown User'))

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier le Voyageur</h1>
            @if($traveler->user)
                <p class="text-gray-600">Modifiez les informations du voyageur {{ $traveler->user->first_name }} {{ $traveler->user->last_name }}</p>
            @else
                <p class="text-gray-600">Modifiez les informations du voyageur (Unknown User)</p>
            @endif
        </div>
        <a href="{{ route('admin.travelers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            Retour à la liste
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('admin.travelers.update', $traveler->traveler_id) }}" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de l'Utilisateur</h3>
            </div>
            
            <div class="md:col-span-2">
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="text-sm text-gray-600">Utilisateur associé:</p>
                    @if($traveler->user)
                        <p class="font-medium text-gray-900">{{ $traveler->user->first_name }} {{ $traveler->user->last_name }} ({{ $traveler->user->email }})</p>
                    @else
                        <p class="font-medium text-gray-900">Unknown User (No email available)</p>
                    @endif
                </div>
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
                    <option value="car" {{ old('vehicle_type', $traveler->vehicle_type) == 'car' ? 'selected' : '' }}>Voiture</option>
                    <option value="van" {{ old('vehicle_type', $traveler->vehicle_type) == 'van' ? 'selected' : '' }}>Camionnette</option>
                    <option value="truck" {{ old('vehicle_type', $traveler->vehicle_type) == 'truck' ? 'selected' : '' }}>Camion</option>
                    <option value="motorcycle" {{ old('vehicle_type', $traveler->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Moto</option>
                    <option value="bicycle" {{ old('vehicle_type', $traveler->vehicle_type) == 'bicycle' ? 'selected' : '' }}>Vélo</option>
                </select>
                @error('vehicle_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="vehicle_model" class="block text-sm font-medium text-gray-700 mb-1">Modèle du véhicule *</label>
                <input type="text" name="vehicle_model" id="vehicle_model" value="{{ old('vehicle_model', $traveler->vehicle_model) }}" required maxlength="255"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_model') border-red-500 @enderror">
                @error('vehicle_model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="vehicle_year" class="block text-sm font-medium text-gray-700 mb-1">Année du véhicule *</label>
                <input type="number" name="vehicle_year" id="vehicle_year" value="{{ old('vehicle_year', $traveler->vehicle_year) }}" required min="1990" max="{{ date('Y') + 1 }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_year') border-red-500 @enderror">
                @error('vehicle_year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">Plaque d'immatriculation *</label>
                <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate', $traveler->license_plate) }}" required maxlength="20"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('license_plate') border-red-500 @enderror">
                @error('license_plate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="driver_license" class="block text-sm font-medium text-gray-700 mb-1">Numéro de permis de conduire *</label>
                <input type="text" name="driver_license" id="driver_license" value="{{ old('driver_license', $traveler->driver_license) }}" required maxlength="50"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('driver_license') border-red-500 @enderror">
                @error('driver_license')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="max_weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Poids maximum (kg) *</label>
                <input type="number" name="max_weight_kg" id="max_weight_kg" value="{{ old('max_weight_kg', $traveler->max_weight_kg) }}" required min="0" step="0.1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_weight_kg') border-red-500 @enderror">
                @error('max_weight_kg')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="max_dimensions" class="block text-sm font-medium text-gray-700 mb-1">Dimensions maximales</label>
                <input type="text" name="max_dimensions" id="max_dimensions" value="{{ old('max_dimensions', $traveler->max_dimensions) }}" maxlength="255"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_dimensions') border-red-500 @enderror">
                @error('max_dimensions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="service_areas" class="block text-sm font-medium text-gray-700 mb-1">Zones de service</label>
                <textarea name="service_areas" id="service_areas" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('service_areas') border-red-500 @enderror">{{ old('service_areas', $traveler->service_areas) }}</textarea>
                @error('service_areas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-1">Tarif horaire</label>
                <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $traveler->hourly_rate) }}" min="0" step="0.01"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hourly_rate') border-red-500 @enderror">
                @error('hourly_rate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                <textarea name="bio" id="bio" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror">{{ old('bio', $traveler->bio) }}</textarea>
                @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Delivery Types -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Types de livraison supportés</h3>
                <div class="space-y-2">
                    @php
                        $currentTypes = old('supported_delivery_types', $traveler->supported_delivery_types ?? []);
                        if (is_string($currentTypes)) {
                            $currentTypes = json_decode($currentTypes, true) ?? [];
                        }
                    @endphp
                    
                    <label class="flex items-center">
                        <input type="checkbox" name="supported_delivery_types[]" value="standard"
                               {{ in_array('standard', $currentTypes) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Standard</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="supported_delivery_types[]" value="express"
                               {{ in_array('express', $currentTypes) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Express</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="supported_delivery_types[]" value="fragile"
                               {{ in_array('fragile', $currentTypes) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Fragile</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="supported_delivery_types[]" value="documents"
                               {{ in_array('documents', $currentTypes) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Documents</span>
                    </label>
                </div>
                @error('supported_delivery_types')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status and Verification -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut et Vérification</h3>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_verified" id="is_verified" value="1"
                       {{ old('is_verified', $traveler->is_verified) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <label for="is_verified" class="ml-2 text-sm text-gray-700">Voyageur vérifié</label>
                @error('is_verified')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_available" id="is_available" value="1"
                       {{ old('is_available', $traveler->is_available) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <label for="is_available" class="ml-2 text-sm text-gray-700">Disponible</label>
                @error('is_available')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.travelers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection