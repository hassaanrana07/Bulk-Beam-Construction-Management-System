<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/BlogCategories/Index', [
            'categories' => KnowledgeCategory::withCount('blogs')->orderBy('order')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/BlogCategories/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:knowledge_categories,slug',
            'description' => 'nullable|string',
            'status' => 'nullable|string'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        KnowledgeCategory::create($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Knowledge category created.');
    }

    public function edit(KnowledgeCategory $blogCategory)
    {
        return Inertia::render('Admin/BlogCategories/Form', [
            'category' => $blogCategory
        ]);
    }

    public function update(Request $request, KnowledgeCategory $blogCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:knowledge_categories,slug,' . $blogCategory->id,
            'description' => 'nullable|string',
            'status' => 'nullable|string'
        ]);

        $blogCategory->update($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Knowledge category updated.');
    }

    public function destroy(KnowledgeCategory $blogCategory)
    {
        $blogCategory->delete();
        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category purged.');
    }
}
