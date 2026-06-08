<?php

namespace Database\Seeders;

use App\Models\AnnualIncomeRange;
use Illuminate\Database\Seeder;

class AnnualIncomeRangesSeeder extends Seeder
{
    public function run(): void
    {
        $ranges = [
            ['label' => 'Below ₹1 Lakh',        'min_value' => 0,      'max_value' => 100000,       'sort_order' => 1],
            ['label' => '₹1 - 2 Lakh',          'min_value' => 100001, 'max_value' => 200000,       'sort_order' => 2],
            ['label' => '₹2 - 3 Lakh',          'min_value' => 200001, 'max_value' => 300000,       'sort_order' => 3],
            ['label' => '₹3 - 5 Lakh',          'min_value' => 300001, 'max_value' => 500000,       'sort_order' => 4],
            ['label' => '₹5 - 7 Lakh',          'min_value' => 500001, 'max_value' => 700000,       'sort_order' => 5],
            ['label' => '₹7 - 10 Lakh',         'min_value' => 700001, 'max_value' => 1000000,      'sort_order' => 6],
            ['label' => '₹10 - 15 Lakh',        'min_value' => 1000001,'max_value' => 1500000,      'sort_order' => 7],
            ['label' => '₹15 - 20 Lakh',        'min_value' => 1500001,'max_value' => 2000000,      'sort_order' => 8],
            ['label' => '₹20 - 30 Lakh',        'min_value' => 2000001,'max_value' => 3000000,      'sort_order' => 9],
            ['label' => '₹30 - 50 Lakh',        'min_value' => 3000001,'max_value' => 5000000,      'sort_order' => 10],
            ['label' => '₹50 Lakh - 1 Crore',   'min_value' => 5000001,'max_value' => 10000000,     'sort_order' => 11],
            ['label' => 'Above ₹1 Crore',       'min_value' => 10000001,'max_value' => null,        'sort_order' => 12],
        ];

        foreach ($ranges as $r) {
            AnnualIncomeRange::updateOrCreate(
                ['label' => $r['label']],
                $r
            );
        }
    }
}
