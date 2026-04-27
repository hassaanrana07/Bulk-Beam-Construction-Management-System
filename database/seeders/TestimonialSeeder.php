<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'Sarah Johnson',
                'client_position' => 'Homeowner',
                'testimonial' => 'Brick & Beam turned our dream home into a reality. Their attention to detail and professionalism were unmatched throughout the process.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'client_name' => 'Michael Chen',
                'client_position' => 'CEO',
                'client_company' => 'Nexus Tech',
                'testimonial' => 'The renovation of our corporate headquarters was completed ahead of schedule and exceeded our expectations in every way.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'client_name' => 'David Miller',
                'client_position' => 'Operations Director',
                'testimonial' => 'Exceptional safety standards and high-quality industrial structural work. Highly recommended for complex projects.',
                'rating' => 4,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}
