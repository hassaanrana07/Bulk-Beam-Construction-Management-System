<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Portfolio;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Lead;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\Location;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectDataSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::first();

        // Staff (Leadership)
        $staff = [
            ['name' => 'Arthur Beam', 'role' => 'Founder & Principal Engineer', 'is_leadership' => true, 'photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1974'],
            ['name' => 'Elena Vance', 'role' => 'Head of Operations', 'is_leadership' => true, 'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1976'],
            ['name' => 'Marcus Steel', 'role' => 'Industrial Construction Lead', 'is_leadership' => true, 'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=2070'],
            ['name' => 'Sarah Brick', 'role' => 'Lead Architect', 'is_leadership' => true, 'photo' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=1961'],
        ];

        foreach ($staff as $s) {
            Staff::updateOrCreate(['slug' => Str::slug($s['name'])], [
                'name' => $s['name'],
                'role' => $s['role'],
                'is_leadership' => $s['is_leadership'],
                'is_active' => true,
                'photo' => $s['photo'],
                'bio' => 'Strategic commander of structural logistics and executive engineering protocols.'
            ]);
        }

        // Services
        $services = [
            ['title' => 'Structural Engineering', 'desc' => 'Precision structural analysis and engineering for high-rise development.', 'image' => 'https://images.unsplash.com/photo-1517089535819-3d4400263f16?q=80&w=2070'],
            ['title' => 'Civil Infrastructure', 'desc' => 'Large-scale public works and transport networks with technical rigor.', 'image' => 'https://images.unsplash.com/photo-1590644310346-6da90356e719?q=80&w=2070'],
            ['title' => 'Technical Renovation', 'desc' => 'Structural overhauls and optimization of existing architectural environments.', 'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19480c5?q=80&w=2070'],
            ['title' => 'Consultancy Matrix', 'desc' => 'Strategic feasibility audits and project management for enterprise clients.', 'image' => 'https://images.unsplash.com/photo-1454165833767-027ffea7025c?q=80&w=2070'],
        ];

        foreach ($services as $idx => $s) {
            Service::updateOrCreate(['slug' => Str::slug($s['title'])], [
                'title' => $s['title'],
                'short_description' => $s['desc'],
                'description' => 'Detailed operational narrative for ' . $s['title'] . '. Engineering precision at scale.',
                'status' => 'published',
                'is_featured' => true,
                'order' => $idx
            ]);
        }

        // Portfolios
        $portfolios = [
            ['title' => 'The Monolith Plaza', 'type' => 'Commercial', 'loc' => 'Chicago, IL', 'budget' => '$120M', 'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070'],
            ['title' => 'Bridge Vector-9', 'type' => 'Infrastructure', 'loc' => 'Austin, TX', 'budget' => '$45M', 'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=2071'],
            ['title' => 'Alpha Industrial Hub', 'type' => 'Industrial', 'loc' => 'Detroit, MI', 'budget' => '$210M', 'image' => 'https://images.unsplash.com/photo-1590644365607-1c5a519a7a37?q=80&w=2070'],
        ];

        foreach ($portfolios as $p) {
            Portfolio::updateOrCreate(['slug' => Str::slug($p['title'])], [
                'title' => $p['title'],
                'project_type' => $p['type'],
                'location' => $p['loc'],
                'budget_range' => $p['budget'],
                'short_description' => 'Architectural legacy project focusing on high-performance execution.',
                'description' => 'Technical analysis of ' . $p['title'] . '. Case study of structural integrity.',
                'status' => 'published',
                'is_featured' => true,
                'featured_image' => $p['image']
            ]);
        }

        // ... Blog, Testimonials, Locations, Leads (Keeping existing)
        $cat = BlogCategory::updateOrCreate(['slug' => 'technical-insights'], ['name' => 'Technical Insights']);
        BlogPost::updateOrCreate(['slug' => 'science-of-high-rise-integrity'], [
            'title' => 'The Science of High-Rise Integrity',
            'category_id' => $cat->id,
            'author_id' => $admin->id,
            'content' => 'Full technical insight into modern skyscraper engineering and structural logic.',
            'status' => 'published',
            'published_at' => now()
        ]);

        $testimonials = [
            ['name' => 'David Vance', 'comp' => 'Global Logistics Inc.', 'text' => 'Brick & Beam transformed our distribution center into a high-performance asset.'],
            ['name' => 'Sarah Steel', 'comp' => 'Metropolis Dev', 'text' => 'The real-time data feeds and executive accountability were game-changers.'],
            ['name' => 'Marcus Frame', 'comp' => 'Industrial Core', 'text' => 'Precision is their baseline. A seamless engineering experience.'],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['client_name' => $t['name']], [
                'client_company' => $t['comp'],
                'testimonial' => $t['text'],
                'is_featured' => true,
                'is_published' => true,
                'rating' => 5
            ]);
        }

        Location::updateOrCreate(['name' => 'Chicago Headquarters'], [
            'address' => '123 Industrial Way',
            'city' => 'Chicago',
            'state' => 'IL',
            'zip_code' => '60601',
            'phone' => '+1 (312) 555-0199',
            'email' => 'chicago@brickandbeam.com',
            'is_primary' => true
        ]);

        if (Lead::count() < 10) {
            for ($i = 0; $i < 10; $i++) {
                Lead::create([
                    'name' => 'Client ' . $i,
                    'email' => 'contact' . $i . '@enterprise.com',
                    'phone' => '+15551234' . $i,
                    'message' => 'Interested in a structural feasibility study.',
                    'status' => $i % 3 == 0 ? 'processed' : 'new',
                    'lead_score' => rand(50, 95),
                    'created_at' => now()->subDays(rand(1, 180))
                ]);
            }
        }
    }
}
