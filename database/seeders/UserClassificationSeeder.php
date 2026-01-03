<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $classifications = [
            [
                'name' => 'Student',
                'type' => 'education',
                'icon' => 'user-graduate',
                'description' => 'Currently enrolled in an educational institution',
                'is_active' => 1
            ],
            [
                'name' => 'Working Professional',
                'type' => 'employment',
                'icon' => 'briefcase',
                'description' => 'Currently employed in a job or business',
                'is_active' => 1
            ],
            [
                'name' => 'Job Seeker',
                'type' => 'employment',
                'icon' => 'search',
                'description' => 'Actively looking for employment opportunities',
                'is_active' => 1
            ],
            [
                'name' => 'Competitive Exam Aspirant',
                'type' => 'education',
                'icon' => 'book-open',
                'description' => 'Preparing for competitive examinations',
                'is_active' => 1
            ],
            [
                'name' => 'Graduate',
                'type' => 'education',
                'icon' => 'graduation-cap',
                'description' => 'Completed graduation degree',
                'is_active' => 1
            ],
            [
                'name' => 'Post Graduate',
                'type' => 'education',
                'icon' => 'user-check',
                'description' => 'Completed post-graduation degree',
                'is_active' => 1
            ],
            [
                'name' => 'Other',
                'type' => null,
                'icon' => 'users',
                'description' => 'Other classification not listed above',
                'is_active' => 1
            ],
        ];

        $insert = [];

        foreach ($classifications as $c) {
            $insert[] = [
                'name'        => $c['name'],
                'type'        => $c['type'],
                'icon'        => $c['icon'],
                'description' => $c['description'],
                'is_active'   => $c['is_active'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('user_classifications')->insert($insert);
    }
}
