<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AnnualIncomeRange extends Model {
    protected $fillable = ['label', 'min_value', 'max_value', 'currency', 'is_active', 'sort_order'];
    protected $casts    = ['is_active' => 'boolean', 'min_value' => 'integer', 'max_value' => 'integer'];

    public function getDisplayLabelAttribute(): string
    {
        $currency = $this->currency ?: 'INR';
        $min = $this->formatAmount((int) ($this->min_value ?? 0), $currency);
        $range = $this->max_value === null
            ? $min . ' and above'
            : $min . ' - ' . $this->formatAmount((int) $this->max_value, $currency);

        if (!filled($this->label)) {
            return $range;
        }

        return $this->label . ' (' . $range . ')';
    }

    private function formatAmount(int $amount, string $currency): string
    {
        $symbol = match ($currency) {
            'INR' => 'Rs. ',
            'USD' => '$',
            'GBP' => 'GBP ',
            'EUR' => 'EUR ',
            'AED' => 'AED ',
            'CAD' => 'CAD ',
            'AUD' => 'AUD ',
            default => $currency . ' ',
        };

        if ($currency === 'INR') {
            if ($amount >= 10000000) {
                return $symbol . rtrim(rtrim(number_format($amount / 10000000, 2), '0'), '.') . ' Cr';
            }

            if ($amount >= 100000) {
                return $symbol . rtrim(rtrim(number_format($amount / 100000, 2), '0'), '.') . ' L';
            }
        }

        return $symbol . number_format($amount);
    }
}
