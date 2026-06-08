<?php

namespace Database\Seeders;

use App\Models\Nakshatra;
use Illuminate\Database\Seeder;

class NakshatrasSeeder extends Seeder
{
    public function run(): void
    {
        $nakshatras = [
            ['name' => 'Ashwini',        'sort_order' => 1],
            ['name' => 'Bharani',        'sort_order' => 2],
            ['name' => 'Krittika',       'sort_order' => 3],
            ['name' => 'Rohini',         'sort_order' => 4],
            ['name' => 'Mrigashira',     'sort_order' => 5],
            ['name' => 'Ardra',          'sort_order' => 6],
            ['name' => 'Punarvasu',      'sort_order' => 7],
            ['name' => 'Pushya',         'sort_order' => 8],
            ['name' => 'Ashlesha',       'sort_order' => 9],
            ['name' => 'Magha',          'sort_order' => 10],
            ['name' => 'Purva Phalguni', 'sort_order' => 11],
            ['name' => 'Uttara Phalguni','sort_order' => 12],
            ['name' => 'Hasta',          'sort_order' => 13],
            ['name' => 'Chitra',         'sort_order' => 14],
            ['name' => 'Swati',          'sort_order' => 15],
            ['name' => 'Vishakha',       'sort_order' => 16],
            ['name' => 'Anuradha',       'sort_order' => 17],
            ['name' => 'Jyeshtha',       'sort_order' => 18],
            ['name' => 'Mula',           'sort_order' => 19],
            ['name' => 'Purva Ashadha',  'sort_order' => 20],
            ['name' => 'Uttara Ashadha', 'sort_order' => 21],
            ['name' => 'Shravana',       'sort_order' => 22],
            ['name' => 'Dhanishta',      'sort_order' => 23],
            ['name' => 'Shatabhisha',    'sort_order' => 24],
            ['name' => 'Purva Bhadrapada','sort_order' => 25],
            ['name' => 'Uttara Bhadrapada','sort_order' => 26],
            ['name' => 'Revati',         'sort_order' => 27],
        ];

        foreach ($nakshatras as $n) {
            Nakshatra::updateOrCreate(['name' => $n['name']], $n);
        }
    }
}
