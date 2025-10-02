@extends('admin.layouts.app')

@section('title', 'Modifier Utilisateur - ' . $user->first_name . ' ' . $user->last_name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier l'Utilisateur</h1>
            <p class="text-gray-600">Modifiez les informations de {{ $user->first_name }} {{ $user->last_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.show', $user->user_id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Voir le profil
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de Base</h3>
            </div>
            
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required maxlength="50"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror">
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required maxlength="50"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror">
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" maxlength="100"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                <select name="role" id="role" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                    <option value="expeditor" {{ old('role', $user->role) == 'expeditor' ? 'selected' : '' }}>Expéditeur</option>
                    <option value="traveler" {{ old('role', $user->role) == 'traveler' ? 'selected' : '' }}>Voyageur</option>
                    <option value="both" {{ old('role', $user->role) == 'both' ? 'selected' : '' }}>Les deux</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required maxlength="20"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone_number') border-red-500 @enderror">
                @error('phone_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Change -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Changer le Mot de Passe</h3>
                <p class="text-sm text-gray-600 mb-4">Laissez vide pour conserver le mot de passe actuel</p>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                <input type="password" name="password" id="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Additional Information -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Supplémentaires</h3>
            </div>
            
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" maxlength="50"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror">
                @error('city')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                <input type="text" name="country" id="country" value="{{ old('country', $user->country ?? 'Côte d\'Ivoire') }}" maxlength="50"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror">
                @error('country')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                <textarea name="bio" id="bio" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror"
                          placeholder="Décrivez-vous en quelques mots...">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
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
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Compte actif
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_verified" id="is_verified" value="1" 
                               {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_verified" class="ml-2 block text-sm text-gray-900">
                            Utilisateur vérifié
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.users.show', $user->user_id) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection