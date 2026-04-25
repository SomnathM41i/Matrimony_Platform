<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// USER SUBSCRIPTION MODEL
// ──────────────────────────────────────────────────────────────────────────────

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id', 'subscription_package_id',
        'amount_paid', 'currency',
        'payment_status', 'payment_gateway', 'transaction_id', 'gateway_response',
        'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'starts_at'        => 'datetime',
        'expires_at'       => 'datetime',
        'is_active'        => 'boolean',
        'amount_paid'      => 'float',
        'gateway_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'subscription_package_id');
    }

    public function usages()
    {
        return $this->hasMany(SubscriptionUsage::class);
    }

    public function paymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at?->isPast() ?? true;
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired() && $this->payment_status === 'completed';
    }
}