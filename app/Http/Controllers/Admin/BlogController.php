<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Blog/Index', [
            'posts' => Blog::with(['knowledgeCategory', 'author'])->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Blog/Form', [
            'categories' => KnowledgeCategory::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'knowledge_category_id' => 'nullable|exists:knowledge_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,scheduled',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'nullable|url',
            'author_name' => 'nullable|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'reading_time' => 'nullable|string',
            'is_public_visible' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['author_id'] = $request->user()->id;

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blogs', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        Blog::create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Knowledge post published.');
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Admin/Blog/Form', [
            'post' => $blog,
            'categories' => KnowledgeCategory::all()
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'knowledge_category_id' => 'nullable|exists:knowledge_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,scheduled',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'nullable|url',
            'author_name' => 'nullable|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'reading_time' => 'nullable|string',
            'is_public_visible' => 'boolean',
        ]);

        if ($validated['status'] === 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image && str_starts_with($blog->featured_image, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $blog->featured_image));
            }
            $path = $request->file('featured_image')->store('blogs', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        $blog->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Knowledge insight updated.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Knowledge insight purged.');
    }
}
