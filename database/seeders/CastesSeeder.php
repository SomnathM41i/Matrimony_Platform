<?php

namespace Database\Seeders;

use App\Models\Religion;
use App\Models\Caste;
use Illuminate\Database\Seeder;

class CastesSeeder extends Seeder
{
    public function run(): void
    {
        $hindu = Religion::where('name', 'Hinduism')->first();
        $muslim = Religion::where('name', 'Islam')->first();
        $christian = Religion::where('name', 'Christianity')->first();
        $sikh = Religion::where('name', 'Sikhism')->first();
        $jain = Religion::where('name', 'Jainism')->first();
        $buddhist = Religion::where('name', 'Buddhism')->first();

        $castes = [
            ['religion_id' => $hindu?->id, 'name' => 'Brahmin',          'sort_order' => 1],
            ['religion_id' => $hindu?->id, 'name' => 'Rajput',           'sort_order' => 2],
            ['religion_id' => $hindu?->id, 'name' => 'Vaishya',          'sort_order' => 3],
            ['religion_id' => $hindu?->id, 'name' => 'Kayastha',         'sort_order' => 4],
            ['religion_id' => $hindu?->id, 'name' => 'Kshatriya',        'sort_order' => 5],
            ['religion_id' => $hindu?->id, 'name' => 'Maratha',          'sort_order' => 6],
            ['religion_id' => $hindu?->id, 'name' => 'Patel / Patidar',  'sort_order' => 7],
            ['religion_id' => $hindu?->id, 'name' => 'Jat',              'sort_order' => 8],
            ['religion_id' => $hindu?->id, 'name' => 'Gurjar',           'sort_order' => 9],
            ['religion_id' => $hindu?->id, 'name' => 'Yadav',            'sort_order' => 10],
            ['religion_id' => $hindu?->id, 'name' => 'Kurmi',            'sort_order' => 11],
            ['religion_id' => $hindu?->id, 'name' => 'Sharma',           'sort_order' => 12],
            ['religion_id' => $hindu?->id, 'name' => 'Agarwal',          'sort_order' => 13],
            ['religion_id' => $hindu?->id, 'name' => 'Gupta',            'sort_order' => 14],
            ['religion_id' => $hindu?->id, 'name' => 'Reddy',            'sort_order' => 15],
            ['religion_id' => $hindu?->id, 'name' => 'Nair',             'sort_order' => 16],
            ['religion_id' => $hindu?->id, 'name' => 'Iyer',             'sort_order' => 17],
            ['religion_id' => $hindu?->id, 'name' => 'Iyengar',          'sort_order' => 18],
            ['religion_id' => $hindu?->id, 'name' => 'Vokkaliga',        'sort_order' => 19],
            ['religion_id' => $hindu?->id, 'name' => 'Lingayat',         'sort_order' => 20],
            ['religion_id' => $hindu?->id, 'name' => 'SC / ST',          'sort_order' => 21],
            ['religion_id' => $hindu?->id, 'name' => 'OBC',              'sort_order' => 22],
            ['religion_id' => $hindu?->id, 'name' => 'Other',            'sort_order' => 23],

            ['religion_id' => $muslim?->id, 'name' => 'Sunni',           'sort_order' => 1],
            ['religion_id' => $muslim?->id, 'name' => 'Shia',            'sort_order' => 2],
            ['religion_id' => $muslim?->id, 'name' => 'Mughal',          'sort_order' => 3],
            ['religion_id' => $muslim?->id, 'name' => 'Pathan',          'sort_order' => 4],
            ['religion_id' => $muslim?->id, 'name' => 'Sheikh',          'sort_order' => 5],
            ['religion_id' => $muslim?->id, 'name' => 'Syed',            'sort_order' => 6],
            ['religion_id' => $muslim?->id, 'name' => 'Ansari',          'sort_order' => 7],
            ['religion_id' => $muslim?->id, 'name' => 'Qureshi',         'sort_order' => 8],
            ['religion_id' => $muslim?->id, 'name' => 'Other',           'sort_order' => 9],

            ['religion_id' => $christian?->id, 'name' => 'Catholic',     'sort_order' => 1],
            ['religion_id' => $christian?->id, 'name' => 'Protestant',   'sort_order' => 2],
            ['religion_id' => $christian?->id, 'name' => 'Syrian Christian', 'sort_order' => 3],
            ['religion_id' => $christian?->id, 'name' => 'Marthoma',     'sort_order' => 4],
            ['religion_id' => $christian?->id, 'name' => 'CSI',          'sort_order' => 5],
            ['religion_id' => $christian?->id, 'name' => 'Born Again',   'sort_order' => 6],
            ['religion_id' => $christian?->id, 'name' => 'Evangelical',  'sort_order' => 7],
            ['religion_id' => $christian?->id, 'name' => 'Other',        'sort_order' => 8],

            ['religion_id' => $sikh?->id, 'name' => 'Jatt',              'sort_order' => 1],
            ['religion_id' => $sikh?->id, 'name' => 'Khatri',            'sort_order' => 2],
            ['religion_id' => $sikh?->id, 'name' => 'Arora',             'sort_order' => 3],
            ['religion_id' => $sikh?->id, 'name' => 'Ramgarhia',         'sort_order' => 4],
            ['religion_id' => $sikh?->id, 'name' => 'Mazhabi',           'sort_order' => 5],
            ['religion_id' => $sikh?->id, 'name' => 'Saini',             'sort_order' => 6],
            ['religion_id' => $sikh?->id, 'name' => 'Other',             'sort_order' => 7],

            ['religion_id' => $jain?->id, 'name' => 'Digambara',         'sort_order' => 1],
            ['religion_id' => $jain?->id, 'name' => 'Shwetambara',       'sort_order' => 2],
            ['religion_id' => $jain?->id, 'name' => 'Oswal',             'sort_order' => 3],
            ['religion_id' => $jain?->id, 'name' => 'Shrimal',           'sort_order' => 4],
            ['religion_id' => $jain?->id, 'name' => 'Porwal',            'sort_order' => 5],
            ['religion_id' => $jain?->id, 'name' => 'Other',             'sort_order' => 6],

            ['religion_id' => $buddhist?->id, 'name' => 'Mahayana',      'sort_order' => 1],
            ['religion_id' => $buddhist?->id, 'name' => 'Theravada',     'sort_order' => 2],
            ['religion_id' => $buddhist?->id, 'name' => 'Navayana',      'sort_order' => 3],
            ['religion_id' => $buddhist?->id, 'name' => 'Vajrayana',     'sort_order' => 4],
            ['religion_id' => $buddhist?->id, 'name' => 'Other',         'sort_order' => 5],
        ];

        foreach ($castes as $c) {
            if ($c['religion_id']) {
                Caste::updateOrCreate(
                    ['religion_id' => $c['religion_id'], 'name' => $c['name']],
                    $c
                );
            }
        }
    }
}
