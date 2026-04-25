<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id', 'assigned_rm_id',
        'name', 'email', 'phone', 'password',
        'gender', 'date_of_birth', 'profile_photo', 'profile_slug',
        'account_status', 'profile_status',
        'is_premium', 'premium_expires_at',
        'email_verification_token', 'phone_verification_otp', 'phone_verified_at',
        'last_login_ip', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token', 'email_verification_token', 'phone_verification_otp'];

    protected $casts = [
        'email_verified_at'   => 'datetime',
        'phone_verified_at'   => 'datetime',
        'premium_expires_at'  => 'datetime',
        'last_login_at'       => 'datetime',
        'date_of_birth'       => 'date',
        'is_premium'          => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function assignedRm()
    {
        return $this->belongsTo(User::class, 'assigned_rm_id');
    }

    public function assignedUsers()
    {
        return $this->hasMany(User::class, 'assigned_rm_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function photos()
    {
        return $this->hasMany(ProfilePhoto::class)->orderBy('sort_order');
    }

    public function primaryPhoto()
    {
        return $this->hasOne(ProfilePhoto::class)->where('is_primary', true);
    }

    public function partnerPreference()
    {
        return $this->hasOne(PartnerPreference::class);
    }

    public function sentInterests()
    {
        return $this->hasMany(Interest::class, 'sender_id');
    }

    public function receivedInterests()
    {
        return $this->hasMany(Interest::class, 'receiver_id');
    }

    public function shortlists()
    {
        return $this->hasMany(Shortlist::class);
    }

    public function shortlistedBy()
    {
        return $this->hasMany(Shortlist::class, 'shortlisted_user_id');
    }

    public function blockedUsers()
    {
        return $this->hasMany(Block::class, 'blocker_id');
    }

    public function blockedByUsers()
    {
        return $this->hasMany(Block::class, 'blocked_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user_one_id')
                    ->orWhere('user_two_id', $this->id);
    }

    public function notifications_custom()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)
                    ->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function profileViews()
    {
        return $this->hasMany(ProfileView::class, 'viewed_user_id');
    }

    public function viewedProfiles()
    {
        return $this->hasMany(ProfileView::class, 'viewer_id');
    }

    public function compatibilityScores()
    {
        return $this->hasMany(CompatibilityScore::class);
    }

    public function rmAssignments()
    {
        return $this->hasMany(RelationshipManagerAssignment::class);
    }

    public function rmInteractions()
    {
        return $this->hasMany(RmInteraction::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function savedSearches()
    {
        return $this->hasMany(SavedSearch::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role?->name === 'super_admin';
    }

    public function isRelationshipManager(): bool
    {
        return $this->role?->name === 'relationship_manager';
    }

    public function isEndUser(): bool
    {
        return $this->role?->name === 'user';
    }

    public function isPremiumActive(): bool
    {
        return $this->is_premium && $this->premium_expires_at?->isFuture();
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role?->permissions->contains('name', $permission) ?? false;
    }
}
