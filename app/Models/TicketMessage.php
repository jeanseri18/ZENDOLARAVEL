<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_admin_reply',
        'is_internal_note',
        'attachments',
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
        'is_internal_note' => 'boolean',
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the ticket that owns the message.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /**
     * Get the user who sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for admin replies only.
     */
    public function scopeAdminReplies($query)
    {
        return $query->where('is_admin_reply', true);
    }

    /**
     * Scope for user messages only.
     */
    public function scopeUserMessages($query)
    {
        return $query->where('is_admin_reply', false)->where('is_internal_note', false);
    }

    /**
     * Scope for internal notes only.
     */
    public function scopeInternalNotes($query)
    {
        return $query->where('is_internal_note', true);
    }

    /**
     * Scope for public messages (visible to user).
     */
    public function scopePublicMessages($query)
    {
        return $query->where('is_internal_note', false);
    }

    /**
     * Check if message has attachments.
     */
    public function hasAttachments(): bool
    {
        return !empty($this->attachments);
    }

    /**
     * Get formatted message type.
     */
    public function getMessageTypeAttribute(): string
    {
        if ($this->is_internal_note) {
            return 'Note interne';
        }
        
        return $this->is_admin_reply ? 'Réponse admin' : 'Message utilisateur';
    }

    /**
     * Get message type color for UI.
     */
    public function getMessageTypeColorAttribute(): string
    {
        if ($this->is_internal_note) {
            return 'bg-purple-100 text-purple-800';
        }
        
        return $this->is_admin_reply ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
    }
}