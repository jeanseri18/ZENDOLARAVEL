@extends('admin.layouts.app')

@section('title', 'Nouvelle Livraison')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Nouvelle Livraison</h1>
        <a href="{{ route('admin.deliveries.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informations de la Livraison</h3>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('admin.deliveries.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Colis <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('package_id') border-red-300 @enderror" 
                                            id="package_id" name="package_id" required>
                                        <option value="">Sélectionner un colis</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->package_id }}" 
                                                    {{ old('package_id') == $package->package_id ? 'selected' : '' }}>
                                                {{ $package->package_number }} - {{ Str::limit($package->package_description, 50) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('package_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label for="traveler_id" class="block text-sm font-medium text-gray-700 mb-2">Livreur <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('traveler_id') border-red-300 @enderror" 
                                            id="traveler_id" name="traveler_id" required>
                                        <option value="">Sélectionner un livreur</option>
                                        @foreach($travelers as $traveler)
                                            <option value="{{ $traveler->traveler_id }}" 
                                                    {{ old('traveler_id') == $traveler->traveler_id ? 'selected' : '' }}>
                                                @if($traveler->user)
                                                    {{ $traveler->user->first_name }} {{ $traveler->user->last_name }} ({{ $traveler->vehicle_type }})
                                                @else
                                                    Unknown User ({{ $traveler->vehicle_type }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('traveler_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="delivery_type" class="block text-sm font-medium text-gray-700 mb-2">Type de Livraison <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('delivery_type') border-red-300 @enderror" 
                                            id="delivery_type" name="delivery_type" required>
                                        <option value="">Sélectionner le type</option>
                                        <option value="urban" {{ old('delivery_type') == 'urban' ? 'selected' : '' }}>Urbaine</option>
                                        <option value="intercity" {{ old('delivery_type') == 'intercity' ? 'selected' : '' }}>Intercité</option>
                                        <option value="international" {{ old('delivery_type') == 'international' ? 'selected' : '' }}>Internationale</option>
                                    </select>
                                    @error('delivery_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-300 @enderror" 
                                            id="status" name="status">
                                        <option value="to_pickup" {{ old('status', 'to_pickup') == 'to_pickup' ? 'selected' : '' }}>À récupérer</option>
                                        <option value="in_transit" {{ old('status') == 'in_transit' ? 'selected' : '' }}>En transit</option>
                                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Livré</option>
                                        <option value="delayed" {{ old('status') == 'delayed' ? 'selected' : '' }}>Retardé</option>
                                        <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Heure de Début</label>
                                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('start_time') border-red-300 @enderror" 
                                           id="start_time" name="start_time" value="{{ old('start_time') }}">
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Heure de Fin</label>
                                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('end_time') border-red-300 @enderror" 
                                           id="end_time" name="end_time" value="{{ old('end_time') }}">
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Récupération <span class="text-red-500">*</span></label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('pickup_location') border-red-300 @enderror" 
                                              id="pickup_location" name="pickup_location" rows="3" required>{{ old('pickup_location') }}</textarea>
                                    @error('pickup_location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label for="delivery_location" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Livraison <span class="text-red-500">*</span></label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('delivery_location') border-red-300 @enderror" 
                                              id="delivery_location" name="delivery_location" rows="3" required>{{ old('delivery_location') }}</textarea>
                                    @error('delivery_location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="actual_delivery_time" class="block text-sm font-medium text-gray-700 mb-2">Temps de Livraison Réel</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('actual_delivery_time') border-red-300 @enderror" 
                                           id="actual_delivery_time" name="actual_delivery_time" 
                                           value="{{ old('actual_delivery_time') }}" placeholder="ex: 1h 30min">
                                    @error('actual_delivery_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label for="commission_fee" class="block text-sm font-medium text-gray-700 mb-2">Frais de Commission (XOF)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('commission_fee') border-red-300 @enderror" 
                                           id="commission_fee" name="commission_fee" step="0.01" min="0"
                                           value="{{ old('commission_fee', '0.00') }}">
                                    @error('commission_fee')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques pour livraison internationale -->
                        <div id="international_fields" style="display: none;">
                            <hr class="my-6 border-gray-300">
                            <h5 class="text-lg font-semibold text-indigo-600 mb-4">Informations Internationales</h5>
                            
                            <div class="mb-4">
                                <label for="customs_declaration" class="block text-sm font-medium text-gray-700 mb-2">Déclaration Douanière</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('customs_declaration') border-red-300 @enderror" 
                                          id="customs_declaration" name="customs_declaration" rows="4" 
                                          placeholder="Détails de la déclaration douanière...">{{ old('customs_declaration') }}</textarea>
                                @error('customs_declaration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="flight_ticket" class="block text-sm font-medium text-gray-700 mb-2">Billet d'Avion</label>
                                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('flight_ticket') border-red-300 @enderror" 
                                       id="flight_ticket" name="flight_ticket" accept=".pdf,.jpg,.jpeg,.png">
                                <p class="mt-1 text-sm text-gray-500">Formats acceptés: PDF, JPG, PNG (Max: 2MB)</p>
                                @error('flight_ticket')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="gps_tracking_data" class="block text-sm font-medium text-gray-700 mb-2">Données de Suivi GPS</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('gps_tracking_data') border-red-300 @enderror" 
                                      id="gps_tracking_data" name="gps_tracking_data" rows="3" 
                                      placeholder="Données GPS au format JSON...">{{ old('gps_tracking_data') }}</textarea>
                            @error('gps_tracking_data')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-6">
                            <a href="{{ route('admin.deliveries.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-save mr-2"></i> Créer la Livraison
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aide</h3>
                    
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-indigo-600 mb-3">Types de Livraison:</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><span class="font-medium">Urbaine:</span> Livraison dans la même ville</li>
                            <li><span class="font-medium">Intercité:</span> Livraison entre villes (Frais: 1000 XOF)</li>
                            <li><span class="font-medium">Internationale:</span> Livraison internationale (Frais: 2000 XOF)</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-indigo-600 mb-3">Statuts:</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">À récupérer</span> En attente de récupération</li>
                            <li class="flex items-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">En transit</span> En cours de livraison</li>
                            <li class="flex items-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">Livré</span> Livraison terminée</li>
                            <li class="flex items-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">Retardé</span> Livraison retardée</li>
                            <li class="flex items-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">Annulé</span> Livraison annulée</li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <span class="font-medium">Note:</span> Les champs spécifiques aux livraisons internationales 
                                    apparaîtront automatiquement lors de la sélection du type "Internationale".
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Afficher/masquer les champs internationaux
    $('#delivery_type').change(function() {
        if ($(this).val() === 'international') {
            $('#international_fields').show();
        } else {
            $('#international_fields').hide();
        }
    });

    // Définir automatiquement les frais de commission
    $('#delivery_type').change(function() {
        const type = $(this).val();
        let fee = 0;
        
        if (type === 'intercity') {
            fee = 1000;
        } else if (type === 'international') {
            fee = 2000;
        }
        
        $('#commission_fee').val(fee + '.00');
    });

    // Vérifier l'état initial
    if ($('#delivery_type').val() === 'international') {
        $('#international_fields').show();
    }
});
</script>
@endpush
@endsection