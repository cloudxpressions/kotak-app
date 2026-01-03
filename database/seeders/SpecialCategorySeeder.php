<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sports Quota', 'is_active' => 1],
            ['name' => 'NCC', 'is_active' => 1],
            ['name' => 'NSS', 'is_active' => 1],
            ['name' => 'Ex-Servicemen', 'is_active' => 1],
            ['name' => 'Wards of Ex-Servicemen', 'is_active' => 1],
            ['name' => 'Freedom Fighter', 'is_active' => 1],
            ['name' => 'Kashmiri Migrants', 'is_active' => 1],
            ['name' => 'Children/Widows of Armed Forces Personnel Killed in Action', 'is_active' => 1],
            ['name' => 'None', 'is_active' => 1],
        ];

        $insert = [];

        foreach ($categories as $c) {
            $insert[] = [
                'name'       => $c['name'],
                'is_active'  => $c['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('special_categories')->insert($insert);
    }
}
