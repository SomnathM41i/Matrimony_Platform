<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Language extends Model {
    protected $fillable = ['name', 'code', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function users() {
        return $this->belongsToMany(User::class, 'profile_languages');
    }
}