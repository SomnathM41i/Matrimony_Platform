<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// SUBSCRIPTION PACKAGE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class SubscriptionPackage extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'currency',
        'duration_days', 'trial_days',
        'is_featured', 'is_active', 'sort_order',
        'contact_views', 'interests_per_day', 'messages_per_day', 'photo_gallery_limit',
        'can_see_contact', 'can_see_full_horoscope', 'highlight_profile',
        'priority_in_search', 'whatsapp_support', 'rm_assistance',
        'extra_features',
    ];

    protected $casts = [
        'is_featured'           => 'boolean',
        'is_active'             => 'boolean',
        'can_see_contact'       => 'boolean',
        'can_see_full_horoscope'=> 'boolean',
        'highlight_profile'     => 'boolean',
        'priority_in_search'    => 'boolean',
        'whatsapp_support'      => 'boolean',
        'rm_assistance'         => 'boolean',
        'extra_features'        => 'array',
        'price'                 => 'float',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function isFree(): bool
    {
        return $this->price == 0;
    }
}



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


