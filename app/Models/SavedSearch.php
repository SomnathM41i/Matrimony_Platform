<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;













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
