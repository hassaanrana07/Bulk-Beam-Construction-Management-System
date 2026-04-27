<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\KnowledgeCategory;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $page = \App\Models\Page::where('slug', 'blog')->where('status', 'published')->first();

        if ($page) {
            $page->load([
                'contentSections' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                }
            ]);
            $page->setRelation('sections', $page->contentSections);
        }

        return Inertia::render('Public/Blog/Index', [
            'page' => $page,
            'posts' => Blog::with('knowledgeCategory')
                ->where('status', 'published')
                ->where('is_public_visible', true)
                ->latest('published_at')
                ->paginate(10),
            'categories' => KnowledgeCategory::all()
        ]);
    }

    public function show(string $slug)
    {
        $post = Blog::where('slug', $slug)
            ->where('is_public_visible', true)
            ->firstOrFail();
        $post->increment('views');
        return Inertia::render('Public/Blog/Show', [
            'post' => $post->load(['knowledgeCategory', 'author']),
            'related_posts' => Blog::where('knowledge_category_id', $post->knowledge_category_id)
                ->where('is_public_visible', true)
                ->where('status', 'published')
                ->where('id', '!=', $post->id)
                ->take(3)
                ->get()
        ]);
    }
}
