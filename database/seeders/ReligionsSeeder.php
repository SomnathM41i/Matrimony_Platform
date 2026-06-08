<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionsSeeder extends Seeder
{
    public function run(): void
    {
        $religions = [
            ['name' => 'Hinduism',     'sort_order' => 1],
            ['name' => 'Islam',        'sort_order' => 2],
            ['name' => 'Christianity', 'sort_order' => 3],
            ['name' => 'Sikhism',      'sort_order' => 4],
            ['name' => 'Buddhism',     'sort_order' => 5],
            ['name' => 'Jainism',      'sort_order' => 6],
            ['name' => 'Parsi',        'sort_order' => 7],
            ['name' => 'Jewish',       'sort_order' => 8],
            ['name' => 'No Religion',  'sort_order' => 9],
            ['name' => 'Other',        'sort_order' => 10],
        ];

        foreach ($religions as $r) {
            Religion::updateOrCreate(['name' => $r['name']], $r);
        }
    }
}
