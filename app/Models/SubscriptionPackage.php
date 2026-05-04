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