<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AnnualIncomeRange extends Model {
    protected $fillable = ['label', 'min_amount', 'max_amount', 'currency', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean', 'min_amount' => 'float', 'max_amount' => 'float'];
}
