<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

// ──────────────────────────────────────────────────────────────────────────────
// LOCATION MODELS
// ──────────────────────────────────────────────────────────────────────────────

class Country extends Model {
    protected $fillable = ['name', 'code', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function states()   { return $this->hasMany(State::class); }
    public function profiles() { return $this->hasMany(UserProfile::class); }
}

class State extends Model {
    protected $fillable = ['country_id', 'name', 'code', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
    public function country() { return $this->belongsTo(Country::class); }
    public function cities()  { return $this->hasMany(City::class); }
}

class City extends Model {
    protected $fillable = ['state_id', 'name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function state() { return $this->belongsTo(State::class); }
    public function areas() { return $this->hasMany(Area::class); }
}

class Area extends Model {
    protected $fillable = ['city_id', 'name', 'pincode', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
    public function city() { return $this->belongsTo(City::class); }
}

// ──────────────────────────────────────────────────────────────────────────────
// RELIGION / COMMUNITY MODELS
// ──────────────────────────────────────────────────────────────────────────────

class Religion extends Model {
    protected $fillable = ['name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function castes()   { return $this->hasMany(Caste::class); }
    public function profiles() { return $this->hasMany(UserProfile::class); }
}

class Caste extends Model {
    protected $fillable = ['religion_id', 'name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function religion()  { return $this->belongsTo(Religion::class); }
    public function subCastes() { return $this->hasMany(SubCaste::class); }
}

class SubCaste extends Model {
    protected $fillable = ['caste_id', 'name', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
    public function caste() { return $this->belongsTo(Caste::class); }
}

class Gotra extends Model {
    protected $fillable = ['name', 'religion_id', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
    public function religion() { return $this->belongsTo(Religion::class); }
}

class Community extends Model {
    protected $fillable = ['name', 'religion_id', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function religion() { return $this->belongsTo(Religion::class); }
}

class MotherTongue extends Model {
    protected $fillable = ['name', 'code', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
}

class Language extends Model {
    protected $fillable = ['name', 'code', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function users() {
        return $this->belongsToMany(User::class, 'profile_languages');
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// HOROSCOPE MODELS
// ──────────────────────────────────────────────────────────────────────────────

class Rashi extends Model {
    protected $fillable = ['name', 'sanskrit_name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
}

class Nakshatra extends Model {
    protected $fillable = ['name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
}

// ──────────────────────────────────────────────────────────────────────────────
// PROFILE ATTRIBUTE MODELS
// ──────────────────────────────────────────────────────────────────────────────

class EducationLevel extends Model {
    protected $fillable = ['name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
}

class Profession extends Model {
    protected $fillable = ['name', 'category', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
}

class AnnualIncomeRange extends Model {
    protected $fillable = ['label', 'min_value', 'max_value', 'currency', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean', 'min_value' => 'integer', 'max_value' => 'integer'];
}
