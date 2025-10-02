<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'profile_photo',
        'city',
        'country',
        'bio',
        'reliability_score',
        'role',
        'is_verified',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'two_factor_auth',
        'last_active',
        'wallet_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }

    /**
     * Get the user's packages.
     */
    public function packages()
    {
        return $this->hasMany(Package::class, 'sender_id', 'user_id');
    }

    /**
     * Get the user's transactions as sender.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id', 'user_id');
    }

    /**
     * Get the user's transactions as traveler.
     */
    public function travelerTransactions()
    {
        return $this->hasMany(Transaction::class, 'traveler_id', 'user_id');
    }

    /**
     * Get the user's support tickets.
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'user_id', 'user_id');
    }

    /**
     * Get the user's traveler profile.
     */
    public function traveler()
    {
        return $this->hasOne(Traveler::class, 'user_id', 'user_id');
    }

    /**
     * Get the user's identity documents.
     */
    public function identityDocuments()
    {
        return $this->hasMany(IdentityDocument::class, 'user_id', 'user_id');
    }

    /**
     * Get the user's primary identity document.
     */
    public function primaryIdentityDocument()
    {
        return $this->hasOne(IdentityDocument::class, 'user_id', 'user_id')
                    ->where('is_primary', true);
    }
}
