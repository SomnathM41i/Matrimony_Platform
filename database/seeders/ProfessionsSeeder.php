<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Seeder;

class ProfessionsSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            ['name' => 'Software Engineer / Developer', 'sort_order' => 1],
            ['name' => 'Doctor / Medical Professional', 'sort_order' => 2],
            ['name' => 'Engineer (Non-IT)',             'sort_order' => 3],
            ['name' => 'Teacher / Professor',           'sort_order' => 4],
            ['name' => 'Banking / Finance Professional','sort_order' => 5],
            ['name' => 'Business Owner / Entrepreneur', 'sort_order' => 6],
            ['name' => 'Lawyer / Legal Professional',   'sort_order' => 7],
            ['name' => 'Government Employee',           'sort_order' => 8],
            ['name' => 'CA / Accountant / Auditor',     'sort_order' => 9],
            ['name' => 'Marketing / Sales Professional','sort_order' => 10],
            ['name' => 'Nurse / Healthcare Worker',     'sort_order' => 11],
            ['name' => 'Civil Services (IAS, IPS, etc.)','sort_order' => 12],
            ['name' => 'Architect / Interior Designer', 'sort_order' => 13],
            ['name' => 'Media / Journalism',            'sort_order' => 14],
            ['name' => 'Artist / Designer / Creative',  'sort_order' => 15],
            ['name' => 'Fitness / Sports Professional', 'sort_order' => 16],
            ['name' => 'Hotel / Hospitality Management','sort_order' => 17],
            ['name' => 'Pilot / Aviation',              'sort_order' => 18],
            ['name' => 'Defence / Armed Forces',        'sort_order' => 19],
            ['name' => 'Self Employed / Freelancer',    'sort_order' => 20],
            ['name' => 'Homemaker',                     'sort_order' => 21],
            ['name' => 'Student',                       'sort_order' => 22],
            ['name' => 'Not Working',                   'sort_order' => 23],
            ['name' => 'Other',                         'sort_order' => 24],
        ];

        foreach ($professions as $p) {
            Profession::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
