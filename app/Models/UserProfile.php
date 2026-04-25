<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'about_me',
        'marital_status', 'no_of_children', 'body_type', 'complexion',
        'height_cm', 'weight_kg', 'blood_group', 'diet', 'smoking', 'drinking',
        'is_differently_abled',
        'religion_id', 'caste_id', 'sub_caste_id', 'gotra_id',
        'community_id', 'mother_tongue_id', 'languages_known',
        'rashi_id', 'nakshatra_id', 'manglik_status', 'birth_time', 'birth_place',
        'education_level_id', 'education_details', 'profession_id',
        'company_name', 'annual_income_range_id',
        'country_id', 'state_id', 'city_id', 'area_id', 'pincode',
        'citizenship', 'residency_status',
        'family_type', 'family_status', 'father_occupation', 'mother_occupation',
        'no_of_brothers', 'no_of_sisters',
        'photo_privacy', 'contact_privacy', 'profile_visibility',
        'completion_percentage',
    ];

    protected $casts = [
        'is_differently_abled' => 'boolean',
        'languages_known'      => 'array',
        'no_of_children'       => 'integer',
        'no_of_brothers'       => 'integer',
        'no_of_sisters'        => 'integer',
        'height_cm'            => 'integer',
        'weight_kg'            => 'float',
        'completion_percentage'=> 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function user()        { return $this->belongsTo(User::class); }
    public function religion()    { return $this->belongsTo(Religion::class); }
    public function caste()       { return $this->belongsTo(Caste::class); }
    public function subCaste()    { return $this->belongsTo(SubCaste::class); }
    public function gotra()       { return $this->belongsTo(Gotra::class); }
    public function community()   { return $this->belongsTo(Community::class); }
    public function motherTongue(){ return $this->belongsTo(MotherTongue::class); }
    public function rashi()       { return $this->belongsTo(Rashi::class); }
    public function nakshatra()   { return $this->belongsTo(Nakshatra::class); }
    public function educationLevel() { return $this->belongsTo(EducationLevel::class); }
    public function profession()  { return $this->belongsTo(Profession::class); }
    public function annualIncomeRange() { return $this->belongsTo(AnnualIncomeRange::class); }
    public function country()     { return $this->belongsTo(Country::class); }
    public function state()       { return $this->belongsTo(State::class); }
    public function city()        { return $this->belongsTo(City::class); }
    public function area()        { return $this->belongsTo(Area::class); }

    // ── Helpers ───────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getHeightFeetAttribute(): ?string
    {
        if (!$this->height_cm) return null;
        $inches = round($this->height_cm / 2.54);
        return floor($inches / 12) . "'" . ($inches % 12) . '"';
    }

    /**
     * Recalculate profile completion percentage.
     * Called after profile updates.
     */
    public function recalculateCompletion(): void
    {
        $fields = [
            'first_name', 'last_name', 'about_me', 'marital_status',
            'height_cm', 'diet', 'religion_id', 'caste_id', 'mother_tongue_id',
            'education_level_id', 'profession_id', 'annual_income_range_id',
            'country_id', 'state_id', 'city_id',
        ];

        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        $this->update(['completion_percentage' => round($filled / count($fields) * 100)]);
    }
}
