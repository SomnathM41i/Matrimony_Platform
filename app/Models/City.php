<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class City extends Model {
    protected $fillable = ['state_id', 'name', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean'];
    public function state() { return $this->belongsTo(State::class); }
    public function areas() { return $this->hasMany(Area::class); }
}
