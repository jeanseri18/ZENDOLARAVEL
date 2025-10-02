<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $primaryKey = 'package_id';
    
    protected $fillable = [
        'tracking_number',
        'sender_id',
        'traveler_id',
        'recipient_name',
        'recipient_phone',
        'recipient_email',
        'recipient_address',
        'package_description',
        'category',
        'weight',
        'dimensions_length',
        'dimensions_width',
        'dimensions_height',
        'declared_value',
        'insurance_value',
        'fragile',
        'requires_signature',
        'pickup_address',
        'delivery_address',
        'pickup_city',
        'delivery_city',
        'estimated_delivery_fee',
        'final_delivery_fee',
        'status',
        'priority'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'dimensions_length' => 'decimal:2',
        'dimensions_width' => 'decimal:2',
        'dimensions_height' => 'decimal:2',
        'declared_value' => 'decimal:2',
        'insurance_value' => 'decimal:2',
        'estimated_delivery_fee' => 'decimal:2',
        'final_delivery_fee' => 'decimal:2',
        'fragile' => 'boolean',
        'requires_signature' => 'boolean',
    ];

    /**
     * Relation avec l'expéditeur (User)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    /**
     * Relation avec le destinataire (User) - optionnel
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traveler_id', 'user_id');
    }

    /**
     * Relation avec le voyageur/livreur (Traveler)
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Traveler::class, 'traveler_id', 'user_id');
    }

    /**
     * Relation avec la livraison (Delivery)
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'package_id', 'package_id');
    }

    /**
     * Relation avec les messages
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'package_id', 'package_id');
    }

    /**
     * Relation avec les évaluations
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'package_id', 'package_id');
    }

    /**
     * Relation avec l'historique
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'package_id', 'package_id');
    }

    /**
     * Scope pour les packages confirmés
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope pour les packages sans livraison assignée
     */
    public function scopeWithoutDelivery($query)
    {
        return $query->whereDoesntHave('delivery');
    }

    /**
     * Accessor pour le nom complet du destinataire
     */
    public function getRecipientFullNameAttribute()
    {
        return $this->recipient_name;
    }

    /**
     * Accessor pour les dimensions formatées
     */
    public function getDimensionsAttribute()
    {
        if ($this->dimensions_length && $this->dimensions_width && $this->dimensions_height) {
            return $this->dimensions_length . ' x ' . $this->dimensions_width . ' x ' . $this->dimensions_height . ' cm';
        }
        return null;
    }

    /**
     * Accessor pour le statut formaté
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'En attente',
            'accepted' => 'Accepté',
            'picked_up' => 'Récupéré',
            'in_transit' => 'En transit',
            'arrived' => 'Arrivé',
            'out_for_delivery' => 'En cours de livraison',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé',
            'returned' => 'Retourné',
            'confirmed' => 'Confirmé'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }
}
