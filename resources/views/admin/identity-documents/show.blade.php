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
                                                <span class="badge bg-warning ms-2">Expire bientôt</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Non spécifiée</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Statut de vérification:</td>
                                    <td>
                                        @switch($document->verification_status)
                                            @case('pending')
                                                <span class="badge bg-warning">{{ $document->status_label }}</span>
                                                @break
                                            @case('verified')
                                                <span class="badge bg-success">{{ $document->status_label }}</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">{{ $document->status_label }}</span>
                                                @break
                                            @case('expired')
                                                <span class="badge bg-secondary">{{ $document->status_label }}</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Téléchargé le:</td>
                                    <td>{{ $document->uploaded_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                @if($document->verified_at)
                                    <tr>
                                        <td class="fw-bold">Vérifié le:</td>
                                        <td>{{ $document->verified_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                @endif
                                @if($document->verifiedBy)
                                    <tr>
                                        <td class="fw-bold">Vérifié par:</td>
                                        <td>{{ $document->verifiedBy->name }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($document->rejection_reason)
                        <div class="alert alert-danger mt-3">
                            <h6><i class="fas fa-exclamation-triangle"></i> Raison du rejet:</h6>
                            <p class="mb-0">{{ $document->rejection_reason }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-4">
                        <div class="btn-group" role="group">
                            @if($document->verification_status === 'pending')
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal">
                                    <i class="fas fa-check"></i> Vérifier
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Rejeter
                                </button>
                            @endif
                            @if(!$document->is_primary && $document->verification_status === 'verified')
                                <form action="{{ route('admin.identity-documents.set-primary', $document->document_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-star"></i> Définir comme principal
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.identity-documents.destroy', $document->document_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de l'utilisateur -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><i class="fas fa-user"></i> Propriétaire</h4>
                </div>
                <div class="card-body text-center">
                    @if($document->user->profile_photo)
                        <img src="{{ asset('storage/' . $document->user->profile_photo) }}" alt="Photo de profil" class="rounded-circle mb-3" width="80" height="80">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                    @endif
                    
                    <h5>{{ $document->user->name }}</h5>
                    <p class="text-muted">{{ $document->user->email }}</p>
                    
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="mb-1">{{ $document->user->role }}</h6>
                                <small class="text-muted">Rôle</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-1">
                                @if($document->user->is_verified)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Vérifié</span>
                                @else
                                    <span class="text-warning"><i class="fas fa-clock"></i> Non vérifié</span>
                                @endif
                            </h6>
                            <small class="text-muted">Statut</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.users.show', $document->user->user_id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Voir le profil
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