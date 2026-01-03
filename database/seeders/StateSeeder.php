<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('database/seeders/data/states.csv');

        if (!file_exists($path)) {
            dd("states.csv not found at: " . $path);
        }

        Schema::disableForeignKeyConstraints();
        DB::table('states')->truncate();
        Schema::enableForeignKeyConstraints();

        // Load CSV data
        $rows = array_map('str_getcsv', file($path));
        $header = array_shift($rows);

        // Build ISO2 → country_id map
        $countryMap = Country::pluck('id', 'iso2')->toArray();

        $insertData = [];

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            $iso2 = $data['country_code']; // Country code from CSV

            if (!isset($countryMap[$iso2])) {
                continue; // Skip if country doesn't exist in DB
            }

            $insertData[] = [
                'name'        => $data['name'],
                'code'        => $data['iso2'] ?: null,
                'type'        => $data['type'] ?: null,
                'country_id'  => $countryMap[$iso2],
                'is_active'   => ($iso2 === 'IN') ? 1 : 0, // Only India active
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('states')->insert($insertData);
    }
}
