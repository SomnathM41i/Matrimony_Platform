<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ──────────────────────────────────────────────────────────────────────────────
// COMPATIBILITY SCORE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class CompatibilityScore extends Model
{
    protected $fillable = [
        'user_id', 'match_user_id',
        'overall_score', 'preference_score', 'religious_score',
        'lifestyle_score', 'location_score', 'education_score',
        'computed_at',
    ];

    protected $casts = ['computed_at' => 'datetime'];

    public function user()       { return $this->belongsTo(User::class); }
    public function matchUser()  { return $this->belongsTo(User::class, 'match_user_id'); }
}