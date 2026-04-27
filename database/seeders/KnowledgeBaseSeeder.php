<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnowledgeCategory;
use App\Models\Blog;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $category = KnowledgeCategory::create([
            'name' => 'General Operations',
            'slug' => 'general-operations',
            'status' => 'active'
        ]);

        Blog::create([
            'title' => 'Structural Integrity Protocols',
            'slug' => 'structural-integrity-protocols',
            'content' => 'Comprehensive guide on maintaining structural integrity in high-load industrial environments.',
            'knowledge_category_id' => $category->id,
            'status' => 'published'
        ]);
    }
}
