<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;





// ──────────────────────────────────────────────────────────────────────────────
// SUBSCRIPTION USAGE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class SubscriptionUsage extends Model
{
    protected $fillable = [
        'user_id', 'user_subscription_id', 'feature_key', 'used', 'usage_date',
    ];

    protected $casts = ['usage_date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    /**
     * Increment usage for a feature today.
     */
    public static function increment(int $userId, int $subscriptionId, string $featureKey): void
    {
        static::updateOrCreate(
            [
                'user_id'             => $userId,
                'user_subscription_id'=> $subscriptionId,
                'feature_key'         => $featureKey,
                'usage_date'          => today(),
            ],
            ['used' => \DB::raw('used + 1')]
        );
    }
}


