<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// CONTACT FORM MODEL
// ──────────────────────────────────────────────────────────────────────────────

class ContactForm extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message',
        'status', 'admin_notes', 'replied_at', 'ip_address',
    ];

    protected $casts = ['replied_at' => 'datetime'];
}