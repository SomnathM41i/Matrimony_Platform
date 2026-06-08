<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelsSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'High School',          'sort_order' => 1],
            ['name' => 'Intermediate / 12th',  'sort_order' => 2],
            ['name' => 'Diploma',              'sort_order' => 3],
            ['name' => 'Bachelor\'s Degree',   'sort_order' => 4],
            ['name' => 'Master\'s Degree',     'sort_order' => 5],
            ['name' => 'M.Phil.',              'sort_order' => 6],
            ['name' => 'PhD / Doctorate',      'sort_order' => 7],
            ['name' => 'Medical Degree (MBBS, MD, etc.)', 'sort_order' => 8],
            ['name' => 'Law Degree (LLB, LLM)', 'sort_order' => 9],
            ['name' => 'CA / CS / CFA',        'sort_order' => 10],
            ['name' => 'Other',                'sort_order' => 11],
        ];

        foreach ($levels as $l) {
            EducationLevel::updateOrCreate(['name' => $l['name']], $l);
        }
    }
}
