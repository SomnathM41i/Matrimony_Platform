<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FeatureFlag extends Model
{
    protected $fillable = ['key', 'label', 'description', 'is_enabled', 'config'];

    protected $casts = [
        'is_enabled' => 'boolean',
        'config'     => 'array',
    ];

    /**
     * Check if a feature is enabled by key.
     * Cached for performance.
     */
    public static function isEnabled(string $key): bool
    {
        return Cache::rememberForever("feature:{$key}", function () use ($key) {
            return (bool) static::where('key', $key)->value('is_enabled');
        });
    }

    protected static function booted(): void
    {
        static::saved(function (FeatureFlag $flag) {
            Cache::forget("feature:{$flag->key}");
        });
    }
}
