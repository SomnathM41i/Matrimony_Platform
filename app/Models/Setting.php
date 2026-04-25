<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'label', 'is_public'];

    protected $casts = ['is_public' => 'boolean'];

    /**
     * Get a setting value by key, with optional default.
     * Cached for performance.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            if (!$setting) return $default;

            return match ($setting->type) {
                'boolean' => (bool) $setting->value,
                'json'    => json_decode($setting->value, true),
                'number'  => (float) $setting->value,
                default   => $setting->value,
            };
        });
    }

    /**
     * Set a setting value and bust cache.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], [
            'value' => is_array($value) ? json_encode($value) : (string) $value,
        ]);
        Cache::forget("setting:{$key}");
    }

    /**
     * Get all settings in a group as key => value array.
     */
    public static function group(string $group): array
    {
        return Cache::rememberForever("settings_group:{$group}", function () use ($group) {
            return static::where('group', $group)
                         ->pluck('value', 'key')
                         ->toArray();
        });
    }

    protected static function booted(): void
    {
        static::saved(function (Setting $setting) {
            Cache::forget("setting:{$setting->key}");
            Cache::forget("settings_group:{$setting->group}");
        });

        static::deleted(function (Setting $setting) {
            Cache::forget("setting:{$setting->key}");
            Cache::forget("settings_group:{$setting->group}");
        });
    }
}
