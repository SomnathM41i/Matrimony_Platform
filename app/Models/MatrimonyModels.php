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




// ──────────────────────────────────────────────────────────────────────────────
// SAVED SEARCH MODEL (NEW)
// ──────────────────────────────────────────────────────────────────────────────

class SavedSearch extends Model
{
    protected $fillable = [
        'user_id', 'name', 'search_type', 'filters', 'keyword',
        'notify_new_matches', 'last_run_at',
    ];

    protected $casts = [
        'filters'             => 'array',
        'notify_new_matches'  => 'boolean',
        'last_run_at'         => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
