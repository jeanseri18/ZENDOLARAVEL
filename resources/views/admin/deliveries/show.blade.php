@extends('admin.layouts.app')

@section('title', 'Détails de la Livraison #' . $delivery->delivery_id)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Livraison #{{ $delivery->delivery_id }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.deliveries.edit', $delivery->delivery_id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="{{ route('admin.deliveries.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de la Livraison</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $delivery->status == 'delivered' ? 'bg-green-100 text-green-800' : ($delivery->status == 'in_transit' ? 'bg-blue-100 text-blue-800' : ($delivery->status == 'to_pickup' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                        {{ $delivery->status_label }}
                    </span>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-indigo-600 mb-4">Informations Générales</h4>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">ID Livraison:</dt>
                                    <dd class="text-sm text-gray-900">{{ $delivery->delivery_id }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Type:</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $delivery->delivery_type == 'urban' ? 'bg-blue-100 text-blue-800' : ($delivery->delivery_type == 'intercity' ? 'bg-yellow-100 text-yellow-800' : 'bg-indigo-100 text-indigo-800') }}">
                                            {{ $delivery->delivery_type_label }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Statut:</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $delivery->status == 'delivered' ? 'bg-green-100 text-green-800' : ($delivery->status == 'in_transit' ? 'bg-blue-100 text-blue-800' : ($delivery->status == 'to_pickup' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ $delivery->status_label }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Commission:</dt>
                                    <dd class="text-sm font-semibold text-green-600">
                                        {{ number_format($delivery->commission_fee, 0, ',', ' ') }} XOF
                                    </dd>
                                </div>
                                @if($delivery->actual_delivery_time)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Temps de livraison:</dt>
                                    <dd class="text-sm text-gray-900">{{ $delivery->actual_delivery_time }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-indigo-600 mb-4">Horaires</h4>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Créée le:</dt>
                                    <dd class="text-sm text-gray-900">{{ $delivery->created_at->format('d/m/Y à H:i') }}</dd>
                                </div>
                                @if($delivery->start_time)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Début:</dt>
                                    <dd class="text-sm text-gray-900">{{ $delivery->start_time->format('d/m/Y à H:i') }}</dd>
                                </div>
                                @endif
                                @if($delivery->end_time)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Fin:</dt>
                                    <dd class="text-sm text-gray-900">{{ $delivery->end_time->format('d/m/Y à H:i') }}</dd>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Dernière MAJ:</dt>
                                    <dd class="text-sm text-gray-900">{{ $delivery->updated_at->format('d/m/Y à H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-300">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-indigo-600 mb-3">Lieu de Récupération</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                                {{ $delivery->pickup_location }}
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-indigo-600 mb-3">Lieu de Livraison</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                                {{ $delivery->delivery_location }}
                            </div>
                        </div>
                    </div>

                    @if($delivery->isInternational() && ($delivery->customs_declaration || $delivery->flight_ticket_path))
                    <hr class="my-6 border-gray-300">
                    <h4 class="text-sm font-medium text-indigo-600 mb-4">Informations Internationales</h4>
                    
                    @if($delivery->customs_declaration)
                    <div class="mb-6">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Déclaration Douanière:</h5>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            {{ $delivery->customs_declaration }}
                        </div>
                    </div>
                    @endif

                    @if($delivery->flight_ticket_path)
                    <div class="mb-6">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Billet d'Avion:</h5>
                        <div>
                            <a href="{{ Storage::url($delivery->flight_ticket_path) }}" 
                               target="_blank" class="inline-flex items-center px-3 py-2 border border-indigo-300 text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-download mr-2"></i> Télécharger le billet
                            </a>
                        </div>
                    </div>
                    @endif
                    @endif

                    @if($delivery->gps_tracking_data)
                    <hr class="my-6 border-gray-300">
                    <h4 class="text-sm font-medium text-indigo-600 mb-3">Données de Suivi GPS</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ $delivery->gps_tracking_data }}</pre>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Actions Rapides</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('admin.deliveries.update-status', $delivery) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Changer le statut:</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="to_pickup" {{ $delivery->status == 'to_pickup' ? 'selected' : '' }}>À récupérer</option>
                                    <option value="in_transit" {{ $delivery->status == 'in_transit' ? 'selected' : '' }}>En transit</option>
                                    <option value="delivered" {{ $delivery->status == 'delivered' ? 'selected' : '' }}>Livré</option>
                                    <option value="delayed" {{ $delivery->status == 'delayed' ? 'selected' : '' }}>Retardé</option>
                                    <option value="canceled" {{ $delivery->status == 'canceled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-sync mr-2"></i> Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Informations du colis -->
            @if($delivery->package)
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Colis Associé</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="text-center mb-6">
                        <i class="fas fa-box text-4xl text-indigo-600"></i>
                    </div>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Numéro:</dt>
                            <dd class="text-sm text-gray-900">{{ $delivery->package->package_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Description:</dt>
                            <dd class="text-sm text-gray-900">{{ Str::limit($delivery->package->package_description, 50) }}</dd>
                        </div>
                        @if(isset($delivery->package->weight))
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Poids:</dt>
                            <dd class="text-sm text-gray-900">{{ $delivery->package->weight }} kg</dd>
                        </div>
                        @endif
                        @if(isset($delivery->package->price))
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Prix:</dt>
                            <dd class="text-sm font-semibold text-green-600">
                                {{ number_format($delivery->package->price, 0, ',', ' ') }} XOF
                            </dd>
                        </div>
                        @endif
                    </dl>
                    <div class="mt-6">
                        <a href="#" class="w-full inline-flex justify-center items-center px-4 py-2 border border-indigo-300 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-eye mr-2"></i> Voir le colis
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Informations du livreur -->
            @if($delivery->traveler)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900">Livreur</h6>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle text-4xl text-blue-600"></i>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Nom:</span>
                            <span class="text-gray-900">{{ $delivery->traveler->user->first_name }} {{ $delivery->traveler->user->last_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Email:</span>
                            <span class="text-gray-900">{{ $delivery->traveler->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Véhicule:</span>
                            <span class="text-gray-900">{{ $delivery->traveler->vehicle_type }}</span>
                        </div>
                        @if($delivery->traveler->license_plate)
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Plaque:</span>
                            <span class="text-gray-900">{{ $delivery->traveler->license_plate }}</span>
                        </div>
                        @endif
                    </div>
                    <a href="#" class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-eye mr-2"></i> Voir le profil
                    </a>
                </div>
            </div>
            @endif

            <!-- Timeline de statut -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900">Timeline</h6>
                </div>
                <div class="p-6">
                    <div class="relative pl-8">
                        <!-- Timeline line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        <!-- Timeline items -->
                        <div class="relative mb-6">
                            <div class="absolute left-[-22px] top-0 w-3 h-3 rounded-full border-2 border-white {{ $delivery->status == 'to_pickup' ? 'bg-yellow-400' : ($delivery->isCompleted() || in_array($delivery->status, ['in_transit', 'delivered']) ? 'bg-green-500' : 'bg-gray-300') }}"></div>
                            <div class="ml-2">
                                <h6 class="text-sm font-semibold text-gray-900 mb-1">À récupérer</h6>
                                <p class="text-xs text-gray-500">En attente de récupération</p>
                            </div>
                        </div>
                        
                        <div class="relative mb-6">
                            <div class="absolute left-[-22px] top-0 w-3 h-3 rounded-full border-2 border-white {{ $delivery->status == 'in_transit' ? 'bg-yellow-400' : ($delivery->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300') }}"></div>
                            <div class="ml-2">
                                <h6 class="text-sm font-semibold text-gray-900 mb-1">En transit</h6>
                                <p class="text-xs text-gray-500">Livraison en cours</p>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <div class="absolute left-[-22px] top-0 w-3 h-3 rounded-full border-2 border-white {{ $delivery->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                            <div class="ml-2">
                                <h6 class="text-sm font-semibold text-gray-900 mb-1">Livré</h6>
                                <p class="text-xs text-gray-500">Livraison terminée</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection