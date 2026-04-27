<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'name' => 'John Carter',
                'role' => 'Managing Director',
                'bio' => 'With over 20 years of experience in high-end construction, John leads Brick & Beam with a vision for excellence and integrity.',
                'photo' => 'https://ui-avatars.com/api/?name=John+Carter&background=FF6B00&color=fff&size=512',
                'is_leadership' => true,
                'order' => 1,
                'social_links' => [
                    'linkedin' => 'https://linkedin.com',
                    'twitter' => 'https://twitter.com',
                ],
            ],
            [
                'name' => 'Sarah Mitchell',
                'role' => 'Principal Architect',
                'bio' => 'Sarah specializes in modern industrial design, bringing a unique aesthetic to every project handled by the firm.',
                'photo' => 'https://ui-avatars.com/api/?name=Sarah+Mitchell&background=FF6B00&color=fff&size=512',
                'is_leadership' => true,
                'order' => 2,
                'social_links' => [
                    'linkedin' => 'https://linkedin.com',
                    'behance' => 'https://behance.net',
                ],
            ],
            [
                'name' => 'Michael Ross',
                'role' => 'Project Manager',
                'bio' => 'Michael ensures that every project stays on schedule and meets the rigorous quality standards our clients expect.',
                'photo' => 'https://ui-avatars.com/api/?name=Michael+Ross&background=FF6B00&color=fff&size=512',
                'is_leadership' => false,
                'order' => 3,
                'social_links' => [
                    'linkedin' => 'https://linkedin.com',
                ],
            ],
            [
                'name' => 'Elena Rodriguez',
                'role' => 'Interior Design Lead',
                'bio' => 'Elena bridges the gap between raw construction and elegant living, creating functional yet beautiful spaces.',
                'photo' => 'https://ui-avatars.com/api/?name=Elena+Rodriguez&background=FF6B00&color=fff&size=512',
                'is_leadership' => false,
                'order' => 4,
                'social_links' => [
                    'instagram' => 'https://instagram.com',
                ],
            ],
        ];

        foreach ($staff as $person) {
            $slug = \Illuminate\Support\Str::slug($person['name']);
            Staff::updateOrCreate(['slug' => $slug], $person);
        }
    }
}
