<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $religions = [
            ['name' => 'Hindu',      'is_active' => 1],
            ['name' => 'Muslim',     'is_active' => 1],
            ['name' => 'Christian',  'is_active' => 1],
            ['name' => 'Sikh',       'is_active' => 1],
            ['name' => 'Buddhist',   'is_active' => 1],
            ['name' => 'Jain',       'is_active' => 1],
            ['name' => 'Parsi',      'is_active' => 1],   // Zoroastrian

            // Widely used in Indian academic/govt systems
            ['name' => 'Jewish',     'is_active' => 1],
            ['name' => 'Tribal',     'is_active' => 1],

            // Generic catch-all
            ['name' => 'Other',      'is_active' => 1],
            ['name' => 'Prefer Not to Say', 'is_active' => 1],
        ];

        $insert = [];

        foreach ($religions as $r) {
            $insert[] = [
                'name'       => $r['name'],
                'is_active'  => $r['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('religions')->insert($insert);
    }
}
