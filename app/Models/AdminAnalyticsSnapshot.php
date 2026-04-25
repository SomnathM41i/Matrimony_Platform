<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// ADMIN ANALYTICS SNAPSHOT MODEL
// ──────────────────────────────────────────────────────────────────────────────

class AdminAnalyticsSnapshot extends Model
{
    protected $fillable = [
        'snapshot_date',
        'total_users', 'new_registrations', 'active_subscriptions',
        'interests_sent', 'interests_accepted', 'messages_sent',
        'revenue', 'extra_data',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'revenue'       => 'float',
        'extra_data'    => 'array',
    ];
}