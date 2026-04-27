<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Pages/Index', [
            'pages' => Page::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Pages/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'sections' => 'nullable|array',
            'status' => 'required|in:draft,published,scheduled',
        ]);

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'status' => $validated['status'],
        ]);

        if (!empty($validated['sections'])) {
            foreach ($validated['sections'] as $index => $section) {
                $content = $section['content'] ?? [];
                $imagePath = $content['image'] ?? null;

                if ($imagePath instanceof \Illuminate\Http\UploadedFile) {
                    $imagePath = $imagePath->store('pages', 'public');
                }

                $page->contentSections()->create([
                    'section_key' => $section['type'] ?? 'unknown',
                    'heading' => $content['title'] ?? null,
                    'subheading' => $content['subtitle'] ?? null,
                    'description' => $content['description'] ?? null,
                    'image' => $imagePath,
                    'image_url' => $content['image_url'] ?? null,
                    'button_text' => $content['button_text'] ?? null,
                    'button_link' => $content['button_link'] ?? null,
                    'order' => $index,
                    'is_active' => $section['is_active'] ?? true,
                ]);
            }
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $page->load([
            'contentSections' => function ($query) {
                $query->orderBy('order');
            }
        ]);

        // Transform relationship to match frontend expectation
        $sections = $page->contentSections->map(function ($section) {
            return [
                'id' => $section->id,
                'type' => $section->section_key,
                'content' => [
                    'title' => $section->heading,
                    'subtitle' => $section->subheading,
                    'description' => $section->description,
                    'image' => $section->image,
                    'image_url' => $section->image_url,
                    'button_text' => $section->button_text,
                    'button_link' => $section->button_link,
                ],
                'is_active' => (bool) $section->is_active,
            ];
        });

        $pageArray = $page->toArray();
        $pageArray['sections'] = $sections;

        return Inertia::render('Admin/Pages/Edit', [
            'page' => $pageArray
        ]);
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'sections' => 'nullable|array',
            'status' => 'required|in:draft,published,scheduled',
        ]);

        $page->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'status' => $validated['status'],
        ]);

        // Sync Sections
        if (isset($validated['sections'])) {
            $page->contentSections()->delete();

            foreach ($validated['sections'] as $index => $section) {
                $content = $section['content'] ?? [];
                $imagePath = $content['image'] ?? null;

                if ($imagePath instanceof \Illuminate\Http\UploadedFile) {
                    $imagePath = $imagePath->store('pages', 'public');
                }

                $page->contentSections()->create([
                    'section_key' => $section['type'] ?? 'unknown',
                    'heading' => $content['title'] ?? null,
                    'subheading' => $content['subtitle'] ?? null,
                    'description' => $content['description'] ?? null,
                    'image' => $imagePath,
                    'image_url' => $content['image_url'] ?? null,
                    'button_text' => $content['button_text'] ?? null,
                    'button_link' => $content['button_link'] ?? null,
                    'order' => $index,
                    'is_active' => $section['is_active'] ?? true,
                ]);
            }
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->contentSections()->delete();
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
