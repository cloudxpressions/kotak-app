<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Seed public testimonials for marketing pages.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Arun Priyadharshan',
                'designation' => 'TNPSC Group 2 Aspirant',
                'message' => 'Kalviplus condensed complex subjects into structured micro notes. The mock exams simulated the exact exam pressure and my scores jumped from 62% to 82% within two months.',
                'rating' => 5,
                'sort_order' => 1,
                'avatar' => null,
            ],
            [
                'name' => 'Lakshmi Saravanan',
                'designation' => 'SSC CGL Ranker',
                'message' => 'I studied after office hours, so the revision cards and late-night doubt support made all the difference. The analytics dashboard told me exactly which topics to revisit.',
                'rating' => 5,
                'sort_order' => 2,
                'avatar' => null,
            ],
            [
                'name' => 'Rahul Mahesh',
                'designation' => 'Banking Candidate',
                'message' => 'The adaptive test engine and bilingual content kept me consistent. Even on low-internet days the PWA mode worked flawlessly. Highly recommended for serious aspirants.',
                'rating' => 4,
                'sort_order' => 3,
                'avatar' => null,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                [
                    'designation' => $testimonial['designation'],
                    'message' => $testimonial['message'],
                    'rating' => $testimonial['rating'],
                    'sort_order' => $testimonial['sort_order'],
                    'avatar' => $testimonial['avatar'],
                    'is_visible' => true,
                ]
            );
        }
    }
}
