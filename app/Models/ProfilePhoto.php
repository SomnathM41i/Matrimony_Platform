<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ──────────────────────────────────────────────────────────────────────────────
// PROFILE PHOTO MODEL
// ──────────────────────────────────────────────────────────────────────────────

class ProfilePhoto extends Model
{
    protected $fillable = [
        'user_id', 'path', 'thumbnail_path',
        'is_primary', 'is_approved', 'sort_order',
    ];

    protected $casts = [
        'is_primary'  => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }
}