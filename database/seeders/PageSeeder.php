<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Structural Integrity.', 'subtitle' => 'High-Performance Construction', 'description' => 'We engineer architectural legacies with industrial precision.']],
                    ['type' => 'credibility', 'content' => ['title' => 'The Numbers of Excellence']],
                    ['type' => 'technical_services', 'content' => ['title' => 'Technical Services', 'subtitle' => 'Capability Matrix']],
                    ['type' => 'why_choose_us', 'content' => ['title' => 'The Brick & Beam Advantage']],
                    ['type' => 'featured_projects', 'content' => ['title' => 'Flagship Projects', 'subtitle' => 'Architectural Archive']],
                    ['type' => 'cta', 'content' => ['title' => 'Initiate Collaboration', 'button_text' => 'CONNECT WITH ENGINEERING']],
                ]
            ],
            [
                'title' => 'About',
                'slug' => 'about',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Our DNA.', 'subtitle' => 'Industrial Heritage', 'description' => 'Founded on the principles of structural integrity and modern engineering.']],
                    ['type' => 'story', 'content' => ['title' => 'The Origin Story', 'subtitle' => 'A Legacy in the Making']],
                    ['type' => 'mission_values', 'content' => ['title' => 'Protocols & Principles', 'subtitle' => 'Mission & Core Values']],
                    ['type' => 'leadership', 'content' => ['title' => 'Command Staff', 'subtitle' => 'Leadership Matrix']],
                    ['type' => 'certifications', 'content' => ['title' => 'Standards & Verification', 'subtitle' => 'Licensed Excellence']],
                    ['type' => 'cta', 'content' => ['title' => 'Join Our Ranks', 'button_text' => 'VIEW CAREERS', 'button_link' => '/careers']],
                ]
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Technical Capabilities.', 'subtitle' => 'Our Operations', 'description' => 'Industrial-grade construction solutions engineered for scale.']],
                    ['type' => 'service_list', 'content' => ['title' => 'Operational Verticals']],
                    ['type' => 'why_choose_us', 'content' => ['title' => 'Deployment Protocol']],
                    ['type' => 'pricing', 'content' => ['title' => 'Preliminary Estimation', 'button_text' => 'LAUNCH ESTIMATOR']],
                    ['type' => 'cta', 'content' => ['title' => 'Request Technical Brief', 'button_text' => 'CONSULT NOW']],
                ]
            ],
            [
                'title' => 'Portfolio',
                'slug' => 'portfolio',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Architectural Archive.', 'subtitle' => 'Archive Library', 'description' => 'A permanent record of technical integrity and high-performance execution.']],
                    ['type' => 'project_list', 'content' => ['title' => 'The Matrix of Completions']],
                    ['type' => 'before_after', 'content' => ['title' => 'Structural Evolution', 'subtitle' => 'Transformation Analysis']],
                    ['type' => 'testimonials', 'content' => ['title' => 'Peer Verification', 'subtitle' => 'Stakeholder Testimonials']],
                    ['type' => 'cta', 'content' => ['title' => 'Design Your Legacy', 'button_text' => 'START PROJECT']],
                ]
            ],
            [
                'title' => 'Insights',
                'slug' => 'insights',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Industrial Insights.', 'subtitle' => 'Knowledge Base', 'description' => 'Technical observations and engineering breakthroughs.']],
                    ['type' => 'blog_list', 'content' => ['title' => 'Knowledge Nodes']],
                    ['type' => 'cta', 'content' => ['title' => 'Subscribe to Intelligence', 'button_text' => 'NOTIFY ME', 'button_link' => '#']],
                ]
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Connect Protocol.', 'subtitle' => 'Global Support', 'description' => 'Establish direct communication with our regional command centers.']],
                    ['type' => 'contact_form', 'content' => ['title' => 'Inquiry Terminal']],
                    ['type' => 'contact_info', 'content' => ['title' => 'HQ Locations', 'subtitle' => 'Regional Nodes']],
                ]
            ],
            [
                'title' => 'FAQs',
                'slug' => 'faqs',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Operations FAQ.', 'subtitle' => 'Knowledge Access', 'description' => 'Standard operating procedures and frequently requested data.']],
                    ['type' => 'faq_list', 'content' => ['title' => 'Common Protocols']],
                ]
            ],
            [
                'title' => 'Certifications',
                'slug' => 'certification',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Verification Ops.', 'subtitle' => 'Compliance Library', 'description' => 'Official licensing and industrial certifications for our global operations.']],
                    ['type' => 'certifications', 'content' => ['title' => 'Licensed Excellence']],
                ]
            ],
            [
                'title' => 'Internal Portal',
                'slug' => 'internal',
                'sections' => [
                    ['type' => 'hero', 'content' => ['title' => 'Secure Access.', 'subtitle' => 'Employee Matrix', 'description' => 'Internal resource management and operational coordination portal.']],
                    ['type' => 'login_form', 'content' => ['title' => 'Access Terminal']],
                ]
            ],
            [
                'title' => 'Footer',
                'slug' => 'footer',
                'sections' => [
                    ['type' => 'footer_branding', 'content' => ['title' => 'Brick & Beam', 'description' => 'Infrastructure V8.0']],
                    ['type' => 'footer_links', 'content' => ['title' => 'Navigation Grid']],
                ]
            ]
        ];

        foreach ($pages as $pData) {
            $page = Page::withTrashed()->where('slug', $pData['slug'])->first();
            
            if ($page) {
                if ($page->trashed()) {
                    $page->restore();
                }
                $page->update(['title' => $pData['title'], 'status' => 'published']);
            } else {
                $page = Page::create(['title' => $pData['title'], 'slug' => $pData['slug'], 'status' => 'published']);
            }

            // Clean old sections
            $page->contentSections()->delete();

            foreach ($pData['sections'] as $index => $sData) {
                $page->contentSections()->create([
                    'section_key' => $sData['type'],
                    'heading' => $sData['content']['title'] ?? null,
                    'subheading' => $sData['content']['subtitle'] ?? null,
                    'description' => $sData['content']['description'] ?? null,
                    'button_text' => $sData['content']['button_text'] ?? null,
                    'button_link' => $sData['content']['button_link'] ?? null,
                    'image' => $sData['content']['image'] ?? null,
                    'order' => $index,
                    'is_active' => true
                ]);
            }
        }
    }
}
