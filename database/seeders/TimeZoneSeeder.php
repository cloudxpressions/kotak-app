<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTimeZone;
use DateTime;

class TimeZoneSeeder extends Seeder
{
    public function run(): void
    {
        $timezones = DateTimeZone::listIdentifiers(); // All IANA zones

        $insert = [];

        foreach ($timezones as $tzName) {

            $dateTime = new DateTime('now', new DateTimeZone($tzName));
            $offsetInSeconds = $dateTime->getOffset();       // Example: 19800
            $offsetMinutes = $offsetInSeconds / 60;          // 330

            // Format readable offset: +05:30
            $offsetFormatted = sprintf(
                "%+03d:%02d",
                floor($offsetMinutes / 60),
                abs($offsetMinutes % 60)
            );

            $insert[] = [
                'name'                 => $tzName,                   // Asia/Kolkata
                'timezone'             => $dateTime->format('T'),    // IST
                'offset'               => $offsetFormatted,           // +05:30
                'utc_offset_minutes'   => $offsetMinutes,             // 330
                'is_active'            => ($tzName === 'Asia/Kolkata') ? 1 : 0, // India only active
                'created_at'           => now(),
                'updated_at'           => now(),
            ];
        }

        DB::table('time_zones')->insert($insert);
    }
}
