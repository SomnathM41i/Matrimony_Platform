<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ──────────────────────────────────────────────────────────────────────────────
// PROFILE VIEW MODEL
// ──────────────────────────────────────────────────────────────────────────────

class ProfileView extends Model
{
    protected $fillable = ['viewer_id', 'viewed_user_id', 'viewed_at'];

    protected $casts = ['viewed_at' => 'datetime'];

    public function viewer()     { return $this->belongsTo(User::class, 'viewer_id'); }
    public function viewedUser() { return $this->belongsTo(User::class, 'viewed_user_id'); }
}
