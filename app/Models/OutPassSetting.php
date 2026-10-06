<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutPassSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get setting value with proper type casting.
     */
    public function getTypedValueAttribute()
    {
        return match($this->type) {
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get setting by key with caching.
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->typed_value;
    }

    /**
     * Set setting value.
     */
    public static function set(string $key, $value, array $attributes = []): void
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        $setting->value = is_array($value) ? json_encode($value) : $value;
        $setting->type = $attributes['type'] ?? self::detectType($value);
        $setting->group = $attributes['group'] ?? 'general';
        $setting->description = $attributes['description'] ?? null;
        $setting->is_public = $attributes['is_public'] ?? false;
        
        $setting->save();
    }

    /**
     * Detect value type.
     */
    protected static function detectType($value): string
    {
        if (is_int($value)) return 'integer';
        if (is_float($value)) return 'float';
        if (is_bool($value)) return 'boolean';
        if (is_array($value)) return 'json';
        return 'string';
    }

    /**
     * Scope a query to only include settings from a group.
     */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope a query to only include public settings.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}