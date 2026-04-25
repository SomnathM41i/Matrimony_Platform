<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// PAYMENT TRANSACTION MODEL (NEW)
// ──────────────────────────────────────────────────────────────────────────────

class PaymentTransaction extends Model
{
    protected $fillable = [
        'user_id', 'user_subscription_id',
        'transaction_id', 'gateway_transaction_id', 'gateway',
        'amount', 'currency', 'gateway_fee', 'tax_amount',
        'type', 'status', 'failure_reason',
        'gateway_request', 'gateway_response',
        'refund_of_transaction_id', 'paid_at',
    ];

    protected $casts = [
        'amount'            => 'float',
        'gateway_fee'       => 'float',
        'tax_amount'        => 'float',
        'paid_at'           => 'datetime',
        'gateway_request'   => 'array',
        'gateway_response'  => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function refundOf()
    {
        return $this->belongsTo(PaymentTransaction::class, 'refund_of_transaction_id');
    }

    public function refunds()
    {
        return $this->hasMany(PaymentTransaction::class, 'refund_of_transaction_id');
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }
}