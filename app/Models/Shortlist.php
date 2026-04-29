<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ──────────────────────────────────────────────────────────────────────────────
// SHORTLIST MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Shortlist extends Model
{
    protected $fillable = ['user_id', 'shortlisted_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shortlistedUser()
    {
        return $this->belongsTo(User::class, 'shortlisted_user_id');
    }
}