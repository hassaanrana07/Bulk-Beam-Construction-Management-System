<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Services/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'is_public_visible' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'image_url' => 'nullable|url|max:2048',
            // Product Structure
            'structure_description' => 'nullable|string',
            'timeline_summary' => 'nullable|string|max:255',
            'phases_details' => 'nullable|array',
            'technical_layout_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            // Operations
            'operations_description' => 'nullable|string',
            'operations_bullets' => 'nullable|array',
            'operations_bullets.*' => 'string',
            'operations_timeline' => 'nullable|string|max:255',
            'operations_team' => 'nullable|string|max:255',
            // Vacations
            'vacations_description' => 'nullable|string',
            'vacations_bullets' => 'nullable|array',
            'vacations_bullets.*' => 'string',
            'vacations_timeline' => 'nullable|string|max:255',
            // Capability Scope
            'capability_features' => 'nullable|array',
            'capability_deliverables' => 'nullable|array',
            'capability_exclusions' => 'nullable|array',
            'capability_tools' => 'nullable|array',
            'capability_scope_description' => 'nullable|string',
            // Product Structure Analysis
            'structural_type' => 'nullable|string|max:255',
            'technical_breakdown' => 'nullable|string',
            'materials_used' => 'nullable|array',
            'architecture_layout' => 'nullable|array',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('services', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        if ($request->hasFile('technical_layout_image')) {
            $path = $request->file('technical_layout_image')->store('services/layouts', 'public');
            $validated['technical_layout_image'] = '/storage/' . $path;
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service capability archived.');
    }

    public function edit(Service $service)
    {
        return Inertia::render('Admin/Services/Form', [
            'service' => $service
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $service->id,
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'is_public_visible' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'image_url' => 'nullable|url|max:2048',
            // Product Structure
            'structure_description' => 'nullable|string',
            'timeline_summary' => 'nullable|string|max:255',
            'phases_details' => 'nullable|array',
            'technical_layout_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            // Operations
            'operations_description' => 'nullable|string',
            'operations_bullets' => 'nullable|array',
            'operations_bullets.*' => 'string',
            'operations_timeline' => 'nullable|string|max:255',
            'operations_team' => 'nullable|string|max:255',
            // Vacations
            'vacations_description' => 'nullable|string',
            'vacations_bullets' => 'nullable|array',
            'vacations_bullets.*' => 'string',
            'vacations_timeline' => 'nullable|string|max:255',
            // Capability Scope
            'capability_features' => 'nullable|array',
            'capability_deliverables' => 'nullable|array',
            'capability_exclusions' => 'nullable|array',
            'capability_tools' => 'nullable|array',
            'capability_scope_description' => 'nullable|string',
            // Product Structure Analysis
            'structural_type' => 'nullable|string|max:255',
            'technical_breakdown' => 'nullable|string',
            'materials_used' => 'nullable|array',
            'architecture_layout' => 'nullable|array',
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($service->featured_image && str_starts_with($service->featured_image, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $service->featured_image));
            }
            $path = $request->file('featured_image')->store('services', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        if ($request->hasFile('technical_layout_image')) {
            // Delete old image if exists
            if ($service->technical_layout_image && str_starts_with($service->technical_layout_image, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $service->technical_layout_image));
            }
            $path = $request->file('technical_layout_image')->store('services/layouts', 'public');
            $validated['technical_layout_image'] = '/storage/' . $path;
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service capability updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service capability purged.');
    }
}
