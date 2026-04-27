<?php

namespace Database\Seeders;

use App\Models\KnowledgeCategory;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Industry News'],
            ['name' => 'Construction Tips'],
            ['name' => 'Sustainability'],
            ['name' => 'Safety Standards'],
        ];

        foreach ($categories as $cat) {
            KnowledgeCategory::firstOrCreate($cat);
        }

        $author = User::first();
        $cat = KnowledgeCategory::first();

        $posts = [
            [
                'title' => 'The Future of Sustainable Architecture',
                'excerpt' => 'How green building materials are reshaping the construction industry.',
                'content' => 'Full article content about sustainable architecture and eco-friendly materials...',
                'knowledge_category_id' => $cat->id,
                'author_id' => $author->id,
                'status' => 'published',
                'published_at' => now(),
                'is_featured' => true,
            ],
            [
                'title' => 'Top 5 Trends in Residential Design for 2026',
                'excerpt' => 'Exploring the fusion of technology and comfort in modern homes.',
                'content' => 'Detailed breakdown of residential design trends...',
                'knowledge_category_id' => $cat->id,
                'author_id' => $author->id,
                'status' => 'published',
                'published_at' => now(),
            ],
        ];

        foreach ($posts as $post) {
            Blog::firstOrCreate(['title' => $post['title']], $post);
        }
    }
}
