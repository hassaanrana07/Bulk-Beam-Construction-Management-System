<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'The Glass Pavilion ',
                'short_description' => 'A modern minimalist residential masterpiece in the hills.',
                'project_type' => 'Residential',
                'location' => 'Malibu, CA',
                'budget_range' => '$2M - $5M',
                'is_featured' => true,
                'status' => 'published',
                'project_details' => ['Square Footage' => '4,500', 'Materials' => 'Glass, Steel, Concrete'],
            ],
            [
                'title' => 'Nexus Office Hub',
                'short_description' => 'Adaptive reuse of a historic warehouse into a modern tech campus.',
                'project_type' => 'Commercial',
                'location' => 'Austin, TX',
                'budget_range' => '$10M+',
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'title' => 'Summit Industrial Park',
                'short_description' => 'State-of-the-art logistics center with sustainable energy systems.',
                'project_type' => 'Industrial',
                'location' => 'Chicago, IL',
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'title' => 'The Horizon Tower',
                'short_description' => 'A vertical living experience with panoramic city views.',
                'project_type' => 'Residential',
                'location' => 'Seattle, WA',
                'status' => 'published', // "Completed" in our logic
            ],
            [
                'title' => 'Eco-Terminal Alpha',
                'short_description' => 'Carbon-neutral distribution hub.',
                'project_type' => 'Industrial',
                'location' => 'Savannah, GA',
                'status' => 'published',
            ],
            [
                'title' => 'Urban Greenbelt',
                'short_description' => 'Community-focused sustainable housing complex.',
                'project_type' => 'Residential',
                'location' => 'Portland, OR',
                'status' => 'published',
            ],
        ];

        foreach ($projects as $project) {
            $slug = \Illuminate\Support\Str::slug($project['title']);
            $portfolio = Portfolio::withTrashed()->where('slug', $slug)->first();
            
            if ($portfolio) {
                if ($portfolio->trashed()) {
                    $portfolio->restore();
                }
                $portfolio->update($project);
            } else {
                Portfolio::create($project);
            }
        }
    }
}
