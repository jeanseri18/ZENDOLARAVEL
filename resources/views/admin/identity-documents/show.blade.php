@extends('admin.layouts.app')

@section('title', 'Détails du Document')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Détails du Document d'Identité</h1>
        <a href="{{ route('admin.identity-documents.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations du document -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-id-card mr-2"></i>Informations du Document
                        @if($document->is_primary)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-2">Document Principal</span>
                        @endif
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Type de document:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $document->document_type_label }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Numéro:</span>
                                <span class="font-mono text-sm text-gray-900">{{ $document->document_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Pays d'émission:</span>
                                <span class="text-gray-900">{{ $document->issuing_country }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Date d'expiration:</span>
                                <div>
                                    @if($document->expiry_date)
                                        <span class="{{ $document->isExpired() ? 'text-red-600' : ($document->expiry_date->diffInDays() < 30 ? 'text-yellow-600' : 'text-green-600') }}">
                                                {{ $document->expiry_date->format('d/m/Y') }}
                                            </span>
                                            @if($document->isExpired())
                                                <span class="badge bg-danger ms-2">Expiré</span>
                                            @elseif($document->expiry_date->diffInDays() < 30)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 ml-2">Expire bientôt</span>
                                            @endif
                                        @else
                                            <span class="text-gray-500">Non spécifiée</span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Statut de vérification:</span>
                                <div>
                                    @switch($document->verification_status)
                                        @case('pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $document->status_label }}</span>
                                            @break
                                        @case('verified')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $document->status_label }}</span>
                                            @break
                                        @case('rejected')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $document->status_label }}</span>
                                            @break
                                        @case('expired')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $document->status_label }}</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-700">Téléchargé le:</span>
                                <span class="text-gray-900">{{ $document->uploaded_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @if($document->verified_at)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Vérifié le:</span>
                                    <span class="text-gray-900">{{ $document->verified_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            @endif
                            @if($document->verifiedBy)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700">Vérifié par:</span>
                                    <span class="text-gray-900">{{ $document->verifiedBy->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($document->rejection_reason)
                        <div class="bg-red-50 border border-red-200 rounded-md p-4 mt-6">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-red-400 mr-2 mt-1"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Raison du rejet:</h3>
                                    <p class="text-sm text-red-700 mt-1">{{ $document->rejection_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-6">
                        <div class="flex space-x-3">
                            @if($document->verification_status === 'pending')
                                <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium" onclick="openModal('verifyModal')">
                                    <i class="fas fa-check mr-2"></i>Vérifier
                                </button>
                                <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium" onclick="openModal('rejectModal')">
                                    <i class="fas fa-times mr-2"></i>Rejeter
                                </button>
                            @endif
                            @if(!$document->is_primary && $document->verification_status === 'verified')
                                <form action="{{ route('admin.identity-documents.set-primary', $document->document_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-star mr-2"></i>Définir comme principal
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.identity-documents.destroy', $document->document_id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-red-300 text-red-700 hover:bg-red-50 px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de l'utilisateur -->
        <div class="lg:w-1/3">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-user mr-2"></i>Propriétaire</h3>
                </div>
                <div class="px-6 py-4 text-center">
                    @if($document->user->profile_photo)
                        <img src="{{ asset('storage/' . $document->user->profile_photo) }}" alt="Photo de profil" class="w-20 h-20 rounded-full mx-auto mb-4">
                    @else
                        <div class="w-20 h-20 bg-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-2xl text-white"></i>
                        </div>
                    @endif
                    
                    <h4 class="text-lg font-medium text-gray-900">{{ $document->user->name }}</h4>
                    <p class="text-gray-500 mb-4">{{ $document->user->email }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="border-r border-gray-200">
                            <div class="text-sm font-medium text-gray-900">{{ $document->user->role }}</div>
                            <div class="text-xs text-gray-500">Rôle</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium">
                                @if($document->user->is_verified)
                                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Vérifié</span>
                                @else
                                    <span class="text-yellow-600"><i class="fas fa-clock mr-1"></i>Non vérifié</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">Statut</div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.users.show', $document->user->user_id) }}" class="border border-blue-300 text-blue-700 hover:bg-blue-50 px-3 py-1 rounded text-sm">
                            <i class="fas fa-eye mr-1"></i>Voir le profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photos du document -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><i class="fas fa-images"></i> Photos du Document</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Photo recto -->
                        <div class="col-md-6">
                            <h6>Recto</h6>
                            @if($document->document_photo)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $document->document_photo) }}" alt="Document recto" class="img-fluid border rounded" style="max-height: 300px;">
                                    <div class="mt-2">
                                        <a href="{{ route('admin.identity-documents.download-photo', [$document->document_id, 'front']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <p>Aucune photo recto disponible</p>
                                </div>
                            @endif
                        </div>

                        <!-- Photo verso -->
                        <div class="col-md-6">
                            <h6>Verso</h6>
                            @if($document->document_photo_back)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $document->document_photo_back) }}" alt="Document verso" class="img-fluid border rounded" style="max-height: 300px;">
                                    <div class="mt-2">
                                        <a href="{{ route('admin.identity-documents.download-photo', [$document->document_id, 'back']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <p>Aucune photo verso disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de vérification -->
@if($document->verification_status === 'pending')
    <div class="modal fade" id="verifyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vérifier le document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.identity-documents.verify', $document->document_id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="verify">
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Vous êtes sur le point de vérifier ce document d'identité. Cette action confirmera que le document est authentique et valide.
                        </div>
                        <p><strong>Utilisateur:</strong> {{ $document->user->name }}</p>
                        <p><strong>Type:</strong> {{ $document->document_type_label }}</p>
                        <p><strong>Numéro:</strong> {{ $document->document_number }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Vérifier le document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rejeter le document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.identity-documents.verify', $document->document_id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="reject">
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Vous êtes sur le point de rejeter ce document d'identité. Veuillez expliquer la raison du rejet.
                        </div>
                        <p><strong>Utilisateur:</strong> {{ $document->user->name }}</p>
                        <p><strong>Type:</strong> {{ $document->document_type_label }}</p>
                        <p><strong>Numéro:</strong> {{ $document->document_number }}</p>
                        
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Raison du rejet <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="4" placeholder="Expliquez pourquoi ce document est rejeté..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Rejeter le document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection