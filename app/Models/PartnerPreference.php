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
