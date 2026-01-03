<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('database/seeders/data/cities.csv');

        if (!file_exists($path)) {
            dd("cities.csv not found at: " . $path);
        }

        Schema::disableForeignKeyConstraints();
        DB::table('cities')->truncate();
        Schema::enableForeignKeyConstraints();

        // Load CSV rows
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        // Fast lookup maps
        $countryMap = Country::pluck('id', 'iso2')->toArray();
        
        // Create state map keyed by "CountryID-StateCode" to avoid collisions (e.g. TN for Tamil Nadu vs Tennessee)
        $states = State::select('id', 'code', 'country_id')->get();
        $stateMap = [];
        foreach ($states as $state) {
            $stateMap[$state->country_id . '-' . $state->code] = $state->id;
        }

        $insertData = [];
        $chunkSize = 1000;
        $processedCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $countryCode = $data['country_code'] ?? null; // IN, US, etc.
            $stateCode   = $data['state_code'] ?? null;

            if (!$countryCode || !$stateCode) {
                continue;
            }

            if (!isset($countryMap[$countryCode])) {
                continue; // No matching country
            }

            $countryId = $countryMap[$countryCode];
            $stateKey = $countryId . '-' . $stateCode;

            if (!isset($stateMap[$stateKey])) {
                continue; // No matching state
            }

            $stateId = $stateMap[$stateKey];

            $insertData[] = [
                'name'          => $data['name'],
                'state_id'      => $stateId,
                'country_id'    => $countryId,
                'latitude'      => $data['latitude'] ?: null,
                'longitude'     => $data['longitude'] ?: null,
                'is_district_hq'=> ($data['is_state_capital'] ?? 0) == 1 ? 1 : 0,
                'is_active'     => ($countryCode === 'IN') ? 1 : 0, // India only active
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            // Insert in chunks
            if (count($insertData) >= $chunkSize) {
                DB::table('cities')->insert($insertData);
                $processedCount += count($insertData);
                $insertData = [];
                echo "Processed {$processedCount} cities...\n";
            }
        }

        // Insert remaining records
        if (!empty($insertData)) {
            DB::table('cities')->insert($insertData);
            $processedCount += count($insertData);
        }

        fclose($handle);
        echo "Total cities processed: {$processedCount}\n";
    }
}
