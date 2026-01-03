<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $communities = [

            ['name' => 'General / Open Category (GEN / OC)', 'description' => 'General or Open Category including all non-reserved categories.', 'is_active' => 1],
            ['name' => 'Economically Weaker Section (EWS)', 'description' => 'General category candidates who fall under the EWS criteria.', 'is_active' => 1],

            ['name' => 'Other Backward Class (OBC)', 'description' => 'Central OBC list used for education and employment.', 'is_active' => 1],
            ['name' => 'Backward Class (BC)', 'description' => 'State-specific Backward Class category (commonly used in South Indian states).', 'is_active' => 1],
            ['name' => 'Most Backward Class (MBC)', 'description' => 'Most Backward Classes requiring special reservation.', 'is_active' => 1],

            ['name' => 'Scheduled Caste (SC)', 'description' => 'Communities listed under Scheduled Castes as per Indian Constitution.', 'is_active' => 1],
            ['name' => 'Scheduled Tribe (ST)', 'description' => 'Communities listed under Scheduled Tribes as per Indian Constitution.', 'is_active' => 1],

            // Additional widely used categories for enterprise apps
            ['name' => 'Denotified Communities (DNC)', 'description' => 'Communities formerly classified under Denotified Tribes.', 'is_active' => 1],
            ['name' => 'Minority Communities', 'description' => 'Minority groups such as Muslim, Christian, Sikh, Buddhist, Jain, Parsi.', 'is_active' => 1],
            ['name' => 'Others', 'description' => 'For applicants who do not fall into any predefined community.', 'is_active' => 1],
        ];

        $insert = [];

        foreach ($communities as $c) {
            $insert[] = [
                'name'        => $c['name'],
                'description' => $c['description'],
                'is_active'   => $c['is_active'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('communities')->insert($insert);
    }
}
