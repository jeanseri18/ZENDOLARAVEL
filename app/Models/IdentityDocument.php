<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityDocument extends Model
{
    use HasFactory;

    protected $table = 'identity_documents';
    protected $primaryKey = 'document_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'document_photo',
        'document_photo_back',
        'expiry_date',
        'issuing_country',
        'verification_status',
        'rejection_reason',
        'uploaded_at',
        'verified_at',
        'verified_by',
        'is_primary'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_primary' => 'boolean'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relation avec l'admin qui a vérifié le document
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by', 'user_id');
    }

    /**
     * Scope pour les documents vérifiés
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope pour les documents en attente
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Scope pour les documents expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    /**
     * Vérifier si le document est expiré
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->verification_status) {
            'pending' => 'En attente',
            'verified' => 'Vérifié',
            'rejected' => 'Rejeté',
            'expired' => 'Expiré',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir le type de document formaté
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'passport' => 'Passeport',
            'national_id' => 'Carte d\'identité nationale',
            'driver_license' => 'Permis de conduire',
            'residence_permit' => 'Titre de séjour',
            default => 'Inconnu'
        };
    }
}
