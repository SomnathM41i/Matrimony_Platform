<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model {
    protected $fillable = ['name', 'category', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
}