<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';
    protected $primaryKey = 'setting_id';

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'is_public',
        'updated_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who last updated this setting.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    /**
     * Scope for public settings.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for private settings.
     */
    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    /**
     * Scope by setting key.
     */
    public function scopeByKey($query, $key)
    {
        return $query->where('setting_key', $key);
    }

    /**
     * Get a setting value by key.
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::byKey($key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return static::castValue($setting->setting_value, $setting->setting_type);
    }

    /**
     * Set a setting value.
     */
    public static function setValue($key, $value, $type = 'string', $description = null, $isPublic = false, $updatedBy = null)
    {
        $setting = static::byKey($key)->first();
        
        if ($setting) {
            $setting->update([
                'setting_value' => static::prepareValue($value, $type),
                'setting_type' => $type,
                'description' => $description ?? $setting->description,
                'is_public' => $isPublic,
                'updated_by' => $updatedBy,
            ]);
        } else {
            $setting = static::create([
                'setting_key' => $key,
                'setting_value' => static::prepareValue($value, $type),
                'setting_type' => $type,
                'description' => $description,
                'is_public' => $isPublic,
                'updated_by' => $updatedBy,
            ]);
        }
        
        return $setting;
    }

    /**
     * Get all public settings as key-value pairs.
     */
    public static function getPublicSettings()
    {
        return static::public()->get()->mapWithKeys(function ($setting) {
            return [$setting->setting_key => static::castValue($setting->setting_value, $setting->setting_type)];
        });
    }

    /**
     * Cast value to appropriate type.
     */
    protected static function castValue($value, $type)
    {
        return match($type) {
            'integer' => (int) $value,
            'decimal' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value
        };
    }

    /**
     * Prepare value for storage.
     */
    protected static function prepareValue($value, $type)
    {
        return match($type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value
        };
    }

    /**
     * Get the typed value attribute.
     */
    public function getTypedValueAttribute()
    {
        return static::castValue($this->setting_value, $this->setting_type);
    }

    /**
     * Common system settings keys.
     */
    public const COMMISSION_RATE = 'commission_rate';
    public const MIN_DELIVERY_FEE = 'min_delivery_fee';
    public const MAX_DELIVERY_FEE = 'max_delivery_fee';
    public const DEFAULT_CURRENCY = 'default_currency';
    public const MAINTENANCE_MODE = 'maintenance_mode';
    public const APP_VERSION = 'app_version';
    public const SUPPORT_EMAIL = 'support_email';
    public const SUPPORT_PHONE = 'support_phone';
    public const MAX_PACKAGE_WEIGHT = 'max_package_weight';
    public const VERIFICATION_REQUIRED = 'verification_required';
}