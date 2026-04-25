<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Caste extends Model {
    protected $fillable = ['religion_id', 'name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function religion()  { return $this->belongsTo(Religion::class); }
    public function subCastes() { return $this->hasMany(SubCaste::class); }
}