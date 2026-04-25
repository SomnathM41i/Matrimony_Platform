<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gotra extends Model {
    protected $fillable = ['name', 'religion_id', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
    public function religion() { return $this->belongsTo(Religion::class); }
}