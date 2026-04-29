<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ──────────────────────────────────────────────────────────────────────────────
// INTEREST MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Interest extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'status', 'message', 'responded_at'];

    protected $casts = ['responded_at' => 'datetime'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function isAccepted(): bool { return $this->status === 'accepted'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
}