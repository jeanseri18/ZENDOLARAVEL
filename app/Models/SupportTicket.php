<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets';
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'package_id',
        'transaction_id',
        'category',
        'priority',
        'status',
        'subject',
        'description',
        'resolution',
        'resolved_at',
        'assigned_to',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the package related to this ticket.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }

    /**
     * Get the transaction related to this ticket.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Get the admin assigned to this ticket.
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');
    }

    /**
     * Scope for open tickets.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for closed tickets.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope for resolved tickets.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope for tickets by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for tickets by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for tickets assigned to specific admin.
     */
    public function scopeAssignedTo($query, $adminId)
    {
        return $query->where('assigned_to', $adminId);
    }

    /**
     * Scope for unassigned tickets.
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    /**
     * Check if ticket is open.
     */
    public function isOpen()
    {
        return in_array($this->status, ['open', 'in_progress', 'waiting_response']);
    }

    /**
     * Check if ticket is closed.
     */
    public function isClosed()
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    /**
     * Assign ticket to admin.
     */
    public function assignTo($adminId)
    {
        $this->update([
            'assigned_to' => $adminId,
            'status' => $this->status === 'open' ? 'in_progress' : $this->status,
        ]);
    }

    /**
     * Resolve the ticket.
     */
    public function resolve($resolution)
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Close the ticket.
     */
    public function close()
    {
        $this->update([
            'status' => 'closed',
        ]);
    }

    /**
     * Reopen the ticket.
     */
    public function reopen()
    {
        $this->update([
            'status' => 'open',
            'resolution' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Get priority color for display.
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'green',
            'normal' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get status color for display.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'red',
            'in_progress' => 'yellow',
            'waiting_response' => 'orange',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get formatted category.
     */
    public function getFormattedCategoryAttribute()
    {
        return match($this->category) {
            'delivery_issue' => 'Problème de livraison',
            'payment_issue' => 'Problème de paiement',
            'account_issue' => 'Problème de compte',
            'technical_issue' => 'Problème technique',
            'other' => 'Autre',
            default => ucfirst($this->category)
        };
    }

    /**
     * Generate unique ticket number.
     */
    public static function generateTicketNumber()
    {
        do {
            $number = 'TK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('ticket_number', $number)->exists());
        
        return $number;
    }

    /**
     * Boot method to generate ticket number automatically.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->ticket_number) {
                $model->ticket_number = static::generateTicketNumber();
            }
        });
    }
}