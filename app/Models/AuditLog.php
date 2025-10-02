<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    protected $primaryKey = 'log_id';

    public $timestamps = false; // Only has created_at

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'record_id' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope for specific actions.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific table.
     */
    public function scopeForTable($query, $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    /**
     * Scope for specific record.
     */
    public function scopeForRecord($query, $tableName, $recordId)
    {
        return $query->where('table_name', $tableName)
                    ->where('record_id', $recordId);
    }

    /**
     * Scope for specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Create an audit log entry.
     */
    public static function logAction($userId, $action, $tableName, $recordId = null, $oldValues = null, $newValues = null, $ipAddress = null, $userAgent = null)
    {
        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Log a create action.
     */
    public static function logCreate($userId, $tableName, $recordId, $newValues)
    {
        return static::logAction($userId, 'create', $tableName, $recordId, null, $newValues);
    }

    /**
     * Log an update action.
     */
    public static function logUpdate($userId, $tableName, $recordId, $oldValues, $newValues)
    {
        return static::logAction($userId, 'update', $tableName, $recordId, $oldValues, $newValues);
    }

    /**
     * Log a delete action.
     */
    public static function logDelete($userId, $tableName, $recordId, $oldValues)
    {
        return static::logAction($userId, 'delete', $tableName, $recordId, $oldValues, null);
    }

    /**
     * Get formatted action description.
     */
    public function getActionDescriptionAttribute()
    {
        return match($this->action) {
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'view' => 'Consultation',
            default => ucfirst($this->action)
        };
    }

    /**
     * Get changes summary.
     */
    public function getChangesSummaryAttribute()
    {
        if ($this->action === 'create') {
            return 'Nouvel enregistrement créé';
        }
        
        if ($this->action === 'delete') {
            return 'Enregistrement supprimé';
        }
        
        if ($this->action === 'update' && $this->old_values && $this->new_values) {
            $changes = [];
            foreach ($this->new_values as $field => $newValue) {
                $oldValue = $this->old_values[$field] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[] = "{$field}: {$oldValue} → {$newValue}";
                }
            }
            return implode(', ', $changes);
        }
        
        return 'Aucun détail disponible';
    }
}