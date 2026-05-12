<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;





// ──────────────────────────────────────────────────────────────────────────────
// SUBSCRIPTION USAGE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class SubscriptionUsage extends Model
{
    protected $fillable = [
        'user_id', 'user_subscription_id', 'feature_key', 'used',
    ];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    /**
     * Increment total usage for a feature within this subscription.
     * One row per (user_subscription_id, feature_key) — no date partitioning.
     */
    public static function increment(int $userId, int $subscriptionId, string $featureKey): void
    {
        static::updateOrCreate(
            [
                'user_id'              => $userId,
                'user_subscription_id' => $subscriptionId,
                'feature_key'          => $featureKey,
            ],
            ['used' => \DB::raw('used + 1')]
        );
    }

    /**
     * Get the total used count for a feature within a subscription.
     */
    public static function getUsed(int $subscriptionId, string $featureKey): int
    {
        return (int) static::where('user_subscription_id', $subscriptionId)
            ->where('feature_key', $featureKey)
            ->value('used');
    }
}