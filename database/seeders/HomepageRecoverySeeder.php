<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;

class HomepageRecoverySeeder extends Seeder
{
    /**
     * Run the database seeds for home page recovery.
     */
    public function run(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            ['title' => 'Home', 'status' => 'published']
        );

        // Clean old sections
        $page->contentSections()->delete();

        $sections = [
            [
                'type' => 'hero',
                'heading' => 'Structural Integrity.',
                'subheading' => 'High-Performance Construction',
                'description' => 'We engineer architectural legacies with industrial precision. High-performance construction logic for sophisticated residential and industrial command centers.',
                'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19480c5?q=80&w=2070',
                'button_text' => 'Commence Protocol',
                'button_link' => '/contact',
            ],
            [
                'type' => 'credibility',
                'heading' => 'The Numbers of Excellence',
                'subheading' => 'Trust Verification',
            ],
            [
                'type' => 'services_overview',
                'heading' => 'Technical',
                'subheading' => 'Precision.',
                'description' => 'Industrial-grade construction solutions engineered for scale and extreme durability across all technical verticals.',
            ],
            [
                'type' => 'why_choose_us',
                'heading' => 'Engineered for',
                'subheading' => 'Performance.',
                'description' => 'We don\'t just build structures; we build assets. Our methodology combines architectural vision with industrial-strength execution protocols.',
            ],
            [
                'type' => 'featured_projects',
                'heading' => 'Architectural',
                'subheading' => 'Archive.',
                'description' => 'A permanent record of technical integrity and high-performance execution across global zones.',
            ],
            [
                'type' => 'certifications',
                'heading' => 'Verification.',
                'subheading' => 'Standards Compliance',
            ],
            [
                'type' => 'before_after',
                'heading' => 'Structural',
                'subheading' => 'Evolution.',
                'description' => 'An audit of technical transformations and high-performance structural overhauls.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070',
                'before_image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=2071'
            ],
            [
                'type' => 'testimonials',
                'heading' => 'Performance',
                'subheading' => 'Verification.',
            ],
            [
                'type' => 'blog_list',
                'heading' => 'Industrial',
                'subheading' => 'Intelligence.',
                'description' => 'Technical observations and engineering breakthroughs from the Brick & Beam knowledge node.',
            ],
            [
                'type' => 'cta',
                'heading' => 'Initiate Collaboration',
                'subheading' => 'Deployment Protocol',
                'button_text' => 'CONNECT WITH ENGINEERING',
                'button_link' => '/contact',
            ],
        ];

        foreach ($sections as $index => $sData) {
            $page->contentSections()->create([
                'section_key' => $sData['type'],
                'heading' => $sData['heading'] ?? null,
                'subheading' => $sData['subheading'] ?? null,
                'description' => $sData['description'] ?? null,
                'image' => $sData['image'] ?? $sData['after_image'] ?? null,
                'button_text' => $sData['button_text'] ?? null,
                'button_link' => $sData['button_link'] ?? null,
                'order' => $index,
                'is_active' => true,
            ]);
        }

        echo "Homepage content restored successfully.\n";
    }
}
