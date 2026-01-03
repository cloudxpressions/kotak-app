<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('database/seeders/data/countries.csv');

        if (!file_exists($path)) {
            dd("countries.csv not found at: " . $path);
        }

        Schema::disableForeignKeyConstraints();
        DB::table('countries')->truncate();
        Schema::enableForeignKeyConstraints();

        $rows = array_map('str_getcsv', file($path));
        $header = array_shift($rows); // remove first row

        $countries = [];

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            $countries[] = [
                'name'        => $data['name'],
                'native_name' => $data['name'],     // CSV has no native name
                'iso3'        => $data['iso3'],
                'iso2'        => $data['iso2'],
                'phonecode'   => $data['phonecode'],
                'currency'    => $data['currency'],
                'capital'     => $data['capital'],
                'continent'   => $data['region'],   // Region = continent
                'emoji_flag'  => $data['emoji'],
                'is_active'   => $data['name'] === 'India',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('countries')->insert($countries);
    }
}
