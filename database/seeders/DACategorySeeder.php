<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DACategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            // --- Locomotor / Orthopedic Disabilities ---
            [
                'name'            => 'Orthopedic Handicap',
                'code'            => 'OH',
                'severity_level'  => 'moderate',
                'percentage'      => 40,
                'description'     => 'Locomotor disability affecting bones, joints or muscles.'
            ],
            [
                'name'            => 'Locomotor Disability',
                'code'            => 'LD',
                'severity_level'  => 'moderate',
                'percentage'      => 40,
                'description'     => 'Movement limitation due to impaired bones, joints, or muscles.'
            ],

            // --- Visual Disabilities ---
            [
                'name'            => 'Visually Impaired',
                'code'            => 'VI',
                'severity_level'  => 'severe',
                'percentage'      => 40,
                'description'     => 'Visual impairment including low vision and blindness.'
            ],
            [
                'name'            => 'Blindness',
                'code'            => 'BL',
                'severity_level'  => 'severe',
                'percentage'      => 40,
                'description'     => 'Total absence of vision or profound visual loss.'
            ],

            // --- Hearing Disabilities ---
            [
                'name'            => 'Hearing Impaired',
                'code'            => 'HI',
                'severity_level'  => 'moderate',
                'percentage'      => 40,
                'description'     => 'Hearing loss of 60 dB or more in conversational frequencies.'
            ],
            [
                'name'            => 'Deaf',
                'code'            => 'DF',
                'severity_level'  => 'severe',
                'percentage'      => 70,
                'description'     => 'Severe to profound hearing loss.'
            ],

            // --- Intellectual Disabilities ---
            [
                'name'            => 'Intellectual Disability',
                'code'            => 'ID',
                'severity_level'  => 'moderate',
                'percentage'      => null,
                'description'     => 'Significant limitations in intellectual functioning and adaptive behavior.'
            ],
            [
                'name'            => 'Mental Illness',
                'code'            => 'MI',
                'severity_level'  => 'mild',
                'percentage'      => null,
                'description'     => 'Mental disorders affecting thinking, mood and behavior.'
            ],

            // --- Multiple Disabilities ---
            [
                'name'            => 'Multiple Disabilities',
                'code'            => 'MD',
                'severity_level'  => null,
                'percentage'      => null,
                'description'     => 'Combination of two or more disabilities.'
            ],

            // --- Speech & Language Disability ---
            [
                'name'            => 'Speech and Language Disability',
                'code'            => 'SLD',
                'severity_level'  => 'moderate',
                'percentage'      => 40,
                'description'     => 'Permanent speech disability affecting communication.'
            ],

            // --- Autism & Learning Disabilities ---
            [
                'name'            => 'Autism Spectrum Disorder',
                'code'            => 'ASD',
                'severity_level'  => 'moderate',
                'percentage'      => null,
                'description'     => 'Neurodevelopmental disorder affecting communication and social interaction.'
            ],
            [
                'name'            => 'Specific Learning Disability',
                'code'            => 'SL',
                'severity_level'  => 'mild',
                'percentage'      => null,
                'description'     => 'Difficulty in reading, writing, arithmetic or comprehension.'
            ],

            // --- Chronic Neurological Conditions ---
            [
                'name'            => 'Chronic Neurological Conditions',
                'code'            => 'CNC',
                'severity_level'  => null,
                'percentage'      => null,
                'description'     => 'Chronic disorders affecting the nervous system.'
            ],

            // --- Leprosy Cured ---
            [
                'name'            => 'Leprosy Cured',
                'code'            => 'LC',
                'severity_level'  => 'mild',
                'percentage'      => null,
                'description'     => 'Persons cured of leprosy but suffering from residual deformity.'
            ],
        ];

        $insert = [];

        foreach ($categories as $c) {
            $insert[] = [
                'name'            => $c['name'],
                'code'            => $c['code'],
                'severity_level'  => $c['severity_level'],
                'percentage'      => $c['percentage'],
                'description'     => $c['description'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        DB::table('d_a_categories')->insert($insert);
    }
}
