<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ──────────────────────────────────────────────────────────────────────────────
// PARTNER PREFERENCE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class PartnerPreference extends Model
{
    protected $fillable = [
        'user_id',
        'age_min', 'age_max', 'height_min_cm', 'height_max_cm',
        'marital_status',
        'religion_ids', 'caste_ids', 'sub_caste_ids', 'mother_tongue_ids', 'caste_no_bar',
        'country_ids', 'state_ids', 'city_ids', 'residency_status_pref',
        'education_level_ids', 'profession_ids', 'annual_income_range_id_min',
        'diet', 'smoking', 'drinking',
        'rashi_ids', 'manglik_pref',
        'about_partner',
    ];

    protected $casts = [
        'marital_status'        => 'array',
        'religion_ids'          => 'array',
        'caste_ids'             => 'array',
        'sub_caste_ids'         => 'array',
        'mother_tongue_ids'     => 'array',
        'country_ids'           => 'array',
        'state_ids'             => 'array',
        'city_ids'              => 'array',
        'education_level_ids'   => 'array',
        'profession_ids'        => 'array',
        'rashi_ids'             => 'array',
        'diet'                  => 'array',
        'smoking'               => 'array',
        'drinking'              => 'array',
        'caste_no_bar'          => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// INTEREST MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Interest extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'status', 'message', 'responded_at'];

    protected $casts = ['responded_at' => 'datetime'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function isAccepted(): bool { return $this->status === 'accepted'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
}

// ──────────────────────────────────────────────────────────────────────────────
// SHORTLIST MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Shortlist extends Model
{
    protected $fillable = ['user_id', 'shortlisted_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shortlistedUser()
    {
        return $this->belongsTo(User::class, 'shortlisted_user_id');
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// BLOCK MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Block extends Model
{
    protected $fillable = ['blocker_id', 'blocked_id', 'reason'];

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocker_id');
    }

    public function blocked()
    {
        return $this->belongsTo(User::class, 'blocked_id');
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// COMPATIBILITY SCORE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class CompatibilityScore extends Model
{
    protected $fillable = [
        'user_id', 'match_user_id',
        'overall_score', 'preference_score', 'religious_score',
        'lifestyle_score', 'location_score', 'education_score',
        'computed_at',
    ];

    protected $casts = ['computed_at' => 'datetime'];

    public function user()       { return $this->belongsTo(User::class); }
    public function matchUser()  { return $this->belongsTo(User::class, 'match_user_id'); }
}

// ──────────────────────────────────────────────────────────────────────────────
// PROFILE VIEW MODEL
// ──────────────────────────────────────────────────────────────────────────────

class ProfileView extends Model
{
    protected $fillable = ['viewer_id', 'viewed_user_id', 'viewed_at'];

    protected $casts = ['viewed_at' => 'datetime'];

    public function viewer()     { return $this->belongsTo(User::class, 'viewer_id'); }
    public function viewedUser() { return $this->belongsTo(User::class, 'viewed_user_id'); }
}

// ──────────────────────────────────────────────────────────────────────────────
// PROFILE PHOTO MODEL
// ──────────────────────────────────────────────────────────────────────────────

class ProfilePhoto extends Model
{
    protected $fillable = [
        'user_id', 'path', 'thumbnail_path',
        'is_primary', 'is_approved', 'sort_order',
    ];

    protected $casts = [
        'is_primary'  => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// SAVED SEARCH MODEL (NEW)
// ──────────────────────────────────────────────────────────────────────────────

class SavedSearch extends Model
{
    protected $fillable = [
        'user_id', 'name', 'search_type', 'filters', 'keyword',
        'notify_new_matches', 'last_run_at',
    ];

    protected $casts = [
        'filters'             => 'array',
        'notify_new_matches'  => 'boolean',
        'last_run_at'         => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
