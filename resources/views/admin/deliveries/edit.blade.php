@extends('admin.layouts.app')

@section('title', 'Modifier la Livraison #' . $delivery->delivery_id)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier la Livraison #{{ $delivery->delivery_id }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.deliveries.show', $delivery) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-eye mr-2"></i> Voir
            </a>
            <a href="{{ route('admin.deliveries.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900">Modifier les Informations</h6>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.deliveries.update', $delivery) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="package_id" class="block text-sm font-medium text-gray-700 mb-2">Colis <span class="text-red-500">*</span></label>
                                <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('package_id') border-red-300 @enderror" 
                                        id="package_id" name="package_id" required>
                                    <option value="">Sélectionner un colis</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->package_id }}" 
                                                {{ old('package_id', $delivery->package_id) == $package->package_id ? 'selected' : '' }}>
                                            {{ $package->package_number }} - {{ Str::limit($package->description, 50) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="traveler_id" class="block text-sm font-medium text-gray-700 mb-2">Livreur <span class="text-red-500">*</span></label>
                                <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('traveler_id') border-red-300 @enderror" 
                                        id="traveler_id" name="traveler_id" required>
                                    <option value="">Sélectionner un livreur</option>
                                    @foreach($travelers as $traveler)
                                        <option value="{{ $traveler->traveler_id }}" 
                                                {{ old('traveler_id', $delivery->traveler_id) == $traveler->traveler_id ? 'selected' : '' }}>
                                            {{ $traveler->user->name }} ({{ $traveler->vehicle_type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('traveler_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="delivery_type" class="block text-sm font-medium text-gray-700 mb-2">Type de Livraison <span class="text-red-500">*</span></label>
                                <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('delivery_type') border-red-300 @enderror" 
                                        id="delivery_type" name="delivery_type" required>
                                    <option value="">Sélectionner le type</option>
                                    <option value="urban" {{ old('delivery_type', $delivery->delivery_type) == 'urban' ? 'selected' : '' }}>Urbaine</option>
                                    <option value="intercity" {{ old('delivery_type', $delivery->delivery_type) == 'intercity' ? 'selected' : '' }}>Intercité</option>
                                    <option value="international" {{ old('delivery_type', $delivery->delivery_type) == 'international' ? 'selected' : '' }}>Internationale</option>
                                </select>
                                @error('delivery_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror" 
                                        id="status" name="status">
                                    <option value="to_pickup" {{ old('status', $delivery->status) == 'to_pickup' ? 'selected' : '' }}>À récupérer</option>
                                    <option value="in_transit" {{ old('status', $delivery->status) == 'in_transit' ? 'selected' : '' }}>En transit</option>
                                    <option value="delivered" {{ old('status', $delivery->status) == 'delivered' ? 'selected' : '' }}>Livré</option>
                                    <option value="delayed" {{ old('status', $delivery->status) == 'delayed' ? 'selected' : '' }}>Retardé</option>
                                    <option value="canceled" {{ old('status', $delivery->status) == 'canceled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Heure de Début</label>
                                <input type="datetime-local" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-300 @enderror" 
                                       id="start_time" name="start_time" 
                                       value="{{ old('start_time', $delivery->start_time ? $delivery->start_time->format('Y-m-d\TH:i') : '') }}">
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Heure de Fin</label>
                                <input type="datetime-local" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-300 @enderror" 
                                       id="end_time" name="end_time" 
                                       value="{{ old('end_time', $delivery->end_time ? $delivery->end_time->format('Y-m-d\TH:i') : '') }}">
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Récupération <span class="text-red-500">*</span></label>
                                <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('pickup_location') border-red-300 @enderror" 
                                          id="pickup_location" name="pickup_location" rows="3" required>{{ old('pickup_location', $delivery->pickup_location) }}</textarea>
                                @error('pickup_location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="delivery_location" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Livraison <span class="text-red-500">*</span></label>
                                <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('delivery_location') border-red-300 @enderror" 
                                          id="delivery_location" name="delivery_location" rows="3" required>{{ old('delivery_location', $delivery->delivery_location) }}</textarea>
                                @error('delivery_location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="actual_delivery_time" class="block text-sm font-medium text-gray-700 mb-2">Temps de Livraison Réel</label>
                                <input type="text" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('actual_delivery_time') border-red-300 @enderror" 
                                       id="actual_delivery_time" name="actual_delivery_time" 
                                       value="{{ old('actual_delivery_time', $delivery->actual_delivery_time) }}" 
                                       placeholder="ex: 1h 30min">
                                @error('actual_delivery_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="commission_fee" class="block text-sm font-medium text-gray-700 mb-2">Frais de Commission (FCFA)</label>
                                <input type="number" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('commission_fee') border-red-300 @enderror" 
                                       id="commission_fee" name="commission_fee" step="0.01" min="0"
                                       value="{{ old('commission_fee', $delivery->commission_fee) }}">
                                @error('commission_fee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Champs spécifiques pour livraison internationale -->
                        <div id="international_fields" class="mt-8 {{ $delivery->delivery_type === 'international' ? '' : 'hidden' }}">
                            <div class="border-t border-gray-200 pt-6">
                                <h5 class="text-lg font-semibold text-blue-600 mb-4">Informations Internationales</h5>
                                
                                <div class="mb-6">
                                    <label for="customs_declaration" class="block text-sm font-medium text-gray-700 mb-2">Déclaration Douanière</label>
                                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('customs_declaration') border-red-300 @enderror" 
                                              id="customs_declaration" name="customs_declaration" rows="4" 
                                              placeholder="Détails de la déclaration douanière...">{{ old('customs_declaration', $delivery->customs_declaration) }}</textarea>
                                    @error('customs_declaration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="flight_ticket" class="block text-sm font-medium text-gray-700 mb-2">Billet d'Avion</label>
                                    @if($delivery->flight_ticket_path)
                                        <div class="mb-3">
                                            <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                                <i class="fas fa-file text-blue-600"></i> 
                                                Fichier actuel: 
                                                <a href="{{ Storage::url($delivery->flight_ticket_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                                    Voir le billet actuel
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('flight_ticket') border-red-300 @enderror" 
                                           id="flight_ticket" name="flight_ticket" accept=".pdf,.jpg,.jpeg,.png">
                                    <p class="mt-1 text-sm text-gray-500">
                                        Formats acceptés: PDF, JPG, PNG (Max: 2MB)
                                    @if($delivery->flight_ticket_path)
                                        <br>Laissez vide pour conserver le fichier actuel.
                                    @endif
                                    </p>
                                    @error('flight_ticket')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="gps_tracking_data" class="block text-sm font-medium text-gray-700 mb-2">Données de Suivi GPS</label>
                                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('gps_tracking_data') border-red-300 @enderror" 
                                              id="gps_tracking_data" name="gps_tracking_data" rows="3" 
                                              placeholder="Données GPS au format JSON...">{{ old('gps_tracking_data', $delivery->gps_tracking_data) }}</textarea>
                                    @error('gps_tracking_data')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.deliveries.show', $delivery) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <!-- Informations actuelles -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900">Informations Actuelles</h6>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">ID:</span>
                            <span class="text-gray-900">{{ $delivery->delivery_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Type:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $delivery->delivery_type == 'urban' ? 'bg-blue-100 text-blue-800' : ($delivery->delivery_type == 'intercity' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ $delivery->delivery_type_label }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $delivery->status == 'delivered' ? 'bg-green-100 text-green-800' : ($delivery->status == 'in_transit' ? 'bg-blue-100 text-blue-800' : ($delivery->status == 'to_pickup' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $delivery->status_label }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Créée le:</span>
                            <span class="text-gray-900">{{ $delivery->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Modifiée le:</span>
                            <span class="text-gray-900">{{ $delivery->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aide -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900">Aide</h6>
                </div>
                <div class="p-6">
                    <h6 class="text-blue-600 font-semibold mb-3">Types de Livraison:</h6>
                    <ul class="space-y-2 mb-6">
                        <li class="text-sm"><span class="font-medium">Urbaine:</span> Livraison dans la même ville</li>
                        <li class="text-sm"><span class="font-medium">Intercité:</span> Livraison entre villes (Frais: 1000 FCFA)</li>
                        <li class="text-sm"><span class="font-medium">Internationale:</span> Livraison internationale (Frais: 2000 FCFA)</li>
                    </ul>

                    <h6 class="text-blue-600 font-semibold mb-3">Statuts:</h6>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">À récupérer</span>
                            <span>En attente de récupération</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">En transit</span>
                            <span>En cours de livraison</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">Livré</span>
                            <span>Livraison terminée</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">Retardé</span>
                            <span>Livraison retardée</span>
                        </li>
                        <li class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">Annulé</span>
                            <span>Livraison annulée</span>
                        </li>
                    </ul>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Attention:</p>
                                <p class="text-sm text-yellow-700">La modification du type de livraison peut affecter les frais de commission et les champs requis.</p>
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
            $('#international_fields').removeClass('hidden');
        } else {
            $('#international_fields').addClass('hidden');
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
        
        // Ne pas écraser si l'utilisateur a déjà modifié
        const currentFee = parseFloat($('#commission_fee').val()) || 0;
        if (currentFee === 0 || confirm('Voulez-vous mettre à jour les frais de commission automatiquement?')) {
            $('#commission_fee').val(fee + '.00');
        }
    });

    // Vérifier l'état initial
    if ($('#delivery_type').val() === 'international') {
        $('#international_fields').removeClass('hidden');
    }
});
</script>
@endpush
@endsection