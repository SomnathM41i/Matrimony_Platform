<?php

namespace Database\Seeders;

use App\Models\MotherTongue;
use Illuminate\Database\Seeder;

class MotherTonguesSeeder extends Seeder
{
    public function run(): void
    {
        $tongues = [
            ['name' => 'Hindi',        'sort_order' => 1],
            ['name' => 'English',      'sort_order' => 2],
            ['name' => 'Gujarati',     'sort_order' => 3],
            ['name' => 'Marathi',      'sort_order' => 4],
            ['name' => 'Bengali',      'sort_order' => 5],
            ['name' => 'Tamil',        'sort_order' => 6],
            ['name' => 'Telugu',       'sort_order' => 7],
            ['name' => 'Kannada',      'sort_order' => 8],
            ['name' => 'Malayalam',    'sort_order' => 9],
            ['name' => 'Punjabi',      'sort_order' => 10],
            ['name' => 'Odia',         'sort_order' => 11],
            ['name' => 'Assamese',     'sort_order' => 12],
            ['name' => 'Urdu',         'sort_order' => 13],
            ['name' => 'Sindhi',       'sort_order' => 14],
            ['name' => 'Nepali',       'sort_order' => 15],
            ['name' => 'Konkani',      'sort_order' => 16],
            ['name' => 'Bhojpuri',     'sort_order' => 17],
            ['name' => 'Maithili',     'sort_order' => 18],
            ['name' => 'Rajasthani',   'sort_order' => 19],
            ['name' => 'Haryanvi',     'sort_order' => 20],
            ['name' => 'Other',        'sort_order' => 21],
        ];

        foreach ($tongues as $t) {
            MotherTongue::updateOrCreate(['name' => $t['name']], $t);
        }
    }
}
