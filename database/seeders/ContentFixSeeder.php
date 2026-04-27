<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;

class ContentFixSeeder extends Seeder
{
    public function run(): void
    {
        // Remove testimonials from Portfolio page
        $portfolioPage = Page::where('slug', 'portfolio')->first();
        if ($portfolioPage) {
            PageSection::where('page_id', $portfolioPage->id)
                ->where('section_key', 'testimonials')
                ->delete();
        }

        // Ensure Home page has testimonials
        $homePage = Page::where('slug', 'home')->first();
        if ($homePage) {
            $exists = PageSection::where('page_id', $homePage->id)
                ->where('section_key', 'testimonials')
                ->exists();

            if (!$exists) {
                PageSection::create([
                    'page_id' => $homePage->id,
                    'section_key' => 'testimonials',
                    'heading' => 'Proven Integrity',
                    'subheading' => 'What our clients say about our high-performance deployments.',
                    'is_active' => true,
                    'order' => 10
                ]);
            }
        }
    }
}
