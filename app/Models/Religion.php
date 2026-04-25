<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model {
    protected $fillable = ['name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function castes()   { return $this->hasMany(Caste::class); }
    public function profiles() { return $this->hasMany(UserProfile::class); }
}