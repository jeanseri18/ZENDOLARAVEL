<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'transaction_id';
    
    protected $fillable = [
        'transaction_reference',
        'package_id',
        'sender_id',
        'traveler_id',
        'transaction_type',
        'amount',
        'currency',
        'commission_rate',
        'commission_amount',
        'net_amount',
        'payment_method',
        'payment_provider',
        'transaction_status',
        'failure_reason',
        'processed_at',
        'completed_at',
        'payment_reference',
        'external_reference',
        'receipt_url',
        'notes',
        'processed_by',
        'promo_code_id',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    // Accessor to map transaction_status to status
    public function getStatusAttribute()
    {
        return $this->transaction_status;
    }
    
    // Mutator to map status to transaction_status
    public function setStatusAttribute($value)
    {
        $this->attributes['transaction_status'] = $value;
    }
    
    // Relationships
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }
    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
    
    public function traveler()
    {
        return $this->belongsTo(User::class, 'traveler_id', 'user_id');
    }
    
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'user_id');
    }
    
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id', 'promo_id');
    }
}
