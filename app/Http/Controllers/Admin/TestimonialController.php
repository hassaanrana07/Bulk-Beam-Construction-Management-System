<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Testimonial/Index', [
            'testimonials' => Testimonial::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Testimonial/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'image_url' => 'nullable|url|max:2048',
            'testimonial' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_published' => 'boolean',
            'is_public_visible' => 'boolean',
        ]);

        if ($request->hasFile('client_image')) {
            $path = $request->file('client_image')->store('testimonials', 'public');
            $validated['client_image'] = '/storage/' . $path;
        }

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        return Inertia::render('Admin/Testimonial/Form', [
            'testimonial' => $testimonial
        ]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'image_url' => 'nullable|url|max:2048',
            'testimonial' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_published' => 'boolean',
            'is_public_visible' => 'boolean',
        ]);

        if ($request->hasFile('client_image')) {
            if ($testimonial->client_image && str_starts_with($testimonial->client_image, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $testimonial->client_image));
            }
            $path = $request->file('client_image')->store('testimonials', 'public');
            $validated['client_image'] = '/storage/' . $path;
        }

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
