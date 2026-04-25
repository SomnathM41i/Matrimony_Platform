<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Country extends Model {
    protected $fillable = ['name', 'code', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function states()   { return $this->hasMany(State::class); }
    public function profiles() { return $this->hasMany(UserProfile::class); }
}