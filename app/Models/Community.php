<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Community extends Model {
    protected $fillable = ['name', 'religion_id', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function religion() { return $this->belongsTo(Religion::class); }
}