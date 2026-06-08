<?php

namespace Database\Seeders;

use App\Models\Rashi;
use Illuminate\Database\Seeder;

class RashisSeeder extends Seeder
{
    public function run(): void
    {
        $rashis = [
            ['name' => 'Mesha',        'english_name' => 'Aries',      'sort_order' => 1],
            ['name' => 'Vrishabha',    'english_name' => 'Taurus',     'sort_order' => 2],
            ['name' => 'Mithuna',      'english_name' => 'Gemini',     'sort_order' => 3],
            ['name' => 'Karka',        'english_name' => 'Cancer',     'sort_order' => 4],
            ['name' => 'Simha',        'english_name' => 'Leo',        'sort_order' => 5],
            ['name' => 'Kanya',        'english_name' => 'Virgo',      'sort_order' => 6],
            ['name' => 'Tula',         'english_name' => 'Libra',      'sort_order' => 7],
            ['name' => 'Vrishchika',   'english_name' => 'Scorpio',    'sort_order' => 8],
            ['name' => 'Dhanu',        'english_name' => 'Sagittarius','sort_order' => 9],
            ['name' => 'Makara',       'english_name' => 'Capricorn',  'sort_order' => 10],
            ['name' => 'Kumbha',       'english_name' => 'Aquarius',   'sort_order' => 11],
            ['name' => 'Meena',        'english_name' => 'Pisces',     'sort_order' => 12],
        ];

        foreach ($rashis as $r) {
            Rashi::updateOrCreate(['name' => $r['name']], $r);
        }
    }
}
