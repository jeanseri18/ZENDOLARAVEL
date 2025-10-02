<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'message_id';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'package_id',
        'message_type',
        'message_content',
        'attachment_url',
        'attachment_type',
        'attachment_size',
        'location_lat',
        'location_lng',
        'sent_at',
        'delivered_at',
        'read_at',
        'is_read',
        'is_deleted',
        'deleted_at',
        'reply_to_message_id',
        'is_system_message',
        'priority',
        'is_pinned',
        'is_reported',
    ];

    protected $casts = [
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'attachment_size' => 'integer',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_read' => 'boolean',
        'is_deleted' => 'boolean',
        'is_system_message' => 'boolean',
        'is_pinned' => 'boolean',
        'is_reported' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }

    /**
     * Get the package related to this message.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }

    /**
     * Get the message this is replying to.
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id', 'message_id');
    }

    /**
     * Get replies to this message.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to_message_id', 'message_id');
    }

    /**
     * Scope for unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for messages in a conversation.
     */
    public function scopeInConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Scope for non-deleted messages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Scope for system messages.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system_message', true);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark message as delivered.
     */
    public function markAsDelivered()
    {
        if (!$this->delivered_at) {
            $this->update([
                'delivered_at' => now(),
            ]);
        }
    }

    /**
     * Check if message has location data.
     */
    public function hasLocation()
    {
        return !is_null($this->location_lat) && !is_null($this->location_lng);
    }

    /**
     * Check if message has attachment.
     */
    public function hasAttachment()
    {
        return !is_null($this->attachment_url);
    }
}