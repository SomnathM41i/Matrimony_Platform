<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// NOTIFICATION TEMPLATE MODEL
// Admin-controlled templates for email, SMS, push, WhatsApp
// ──────────────────────────────────────────────────────────────────────────────

class NotificationTemplate extends Model
{
    protected $fillable = [
        'event_key', 'subject',
        'email_body', 'sms_body', 'push_body', 'whatsapp_body',
        'email_enabled', 'sms_enabled', 'push_enabled', 'whatsapp_enabled',
    ];

    protected $casts = [
        'email_enabled'     => 'boolean',
        'sms_enabled'       => 'boolean',
        'push_enabled'      => 'boolean',
        'whatsapp_enabled'  => 'boolean',
    ];

    /**
     * Replace template variables like {{user_name}} with actual values.
     */
    public function render(string $channel, array $data): string
    {
        $field = match ($channel) {
            'email'     => 'email_body',
            'sms'       => 'sms_body',
            'push'      => 'push_body',
            'whatsapp'  => 'whatsapp_body',
            default     => 'email_body',
        };

        $body = $this->$field ?? '';

        foreach ($data as $key => $value) {
            $body = str_replace("{{$key}}", $value, $body);
        }

        return $body;
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// USER NOTIFICATION MODEL (renamed from 'notifications' — see audit fix #4)
// ──────────────────────────────────────────────────────────────────────────────

class UserNotification extends Model
{
    protected $fillable = [
        'user_id', 'type', 'notifiable_type', 'notifiable_id',
        'data', 'title', 'body', 'channel', 'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// EMAIL LOG MODEL (NEW)
// ──────────────────────────────────────────────────────────────────────────────

class EmailLog extends Model
{
    protected $fillable = [
        'user_id', 'to_email', 'to_name', 'from_email', 'from_name',
        'subject', 'mailable_class', 'template_key',
        'status', 'message_id', 'error_message', 'metadata',
        'sent_at', 'opened_at', 'clicked_at', 'bounced_at',
    ];

    protected $casts = [
        'sent_at'    => 'datetime',
        'opened_at'  => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'metadata'   => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// WHATSAPP LOG MODEL (NEW)
// ──────────────────────────────────────────────────────────────────────────────

class WhatsappLog extends Model
{
    protected $fillable = [
        'user_id', 'to_phone', 'template_name', 'message_body', 'template_params',
        'provider', 'provider_message_id',
        'status', 'error_code', 'error_message',
        'sent_at', 'delivered_at', 'read_at',
    ];

    protected $casts = [
        'template_params' => 'array',
        'sent_at'         => 'datetime',
        'delivered_at'    => 'datetime',
        'read_at'         => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
