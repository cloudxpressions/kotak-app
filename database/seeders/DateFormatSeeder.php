<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DateFormatSeeder extends Seeder
{
    public function run(): void
    {
        // Sample date for rendering examples
        $sample = Carbon::create(2019, 5, 17, 0, 0, 0);

        $formats = [

            // User-provided formats
            'jS M, Y',
            'Y-m-d',
            'Y-d-m',
            'd-m-Y',
            'm-d-Y',
            'Y/m/d',
            'Y/d/m',
            'd/m/Y',
            'm/d/Y',
            'l jS \o\f F Y',
            'jS \o\f F Y',
            'g:ia \o\n l jS F Y',
            'F j, Y, g:i a',
            'F j, Y',
            '\i\t \i\s \t\h\e jS \d\a\y',

            // Internationally common
            'd.m.Y',
            'Y.m.d',
            'M d, Y',
            'd M Y',
            'D, M j, Y',
            'l, F j Y',
            'Y F j',
            'd F Y',
        ];

        $insert = [];

        foreach ($formats as $fmt) {
            $insert[] = [
                'format'       => $fmt,
                'normal_view'  => $sample->format($fmt),
                'is_active'    => 1, // all active, or customize below
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        DB::table('date_formats')->insert($insert);
    }
}
