<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// REPORT MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Report extends Model
{
    protected $fillable = [
        'reporter_id', 'reported_user_id',
        'reason', 'description', 'status', 'admin_notes', 'resolved_at',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function reporter()    { return $this->belongsTo(User::class, 'reporter_id'); }
    public function reportedUser(){ return $this->belongsTo(User::class, 'reported_user_id'); }
}
