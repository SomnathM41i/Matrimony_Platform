<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubCaste extends Model {
    protected $fillable = ['caste_id', 'name', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
    public function caste() { return $this->belongsTo(Caste::class); }
}