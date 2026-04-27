<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Staff/Index', [
            'staff' => Staff::where('role', 'like', '%Finance%')
                ->orWhere('role', 'like', '%Account%')
                ->orderBy('order')
                ->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Staff/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_url' => 'nullable|url|max:2048',
            'is_leadership' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
            'social_links' => 'nullable|array',
            'is_public_visible' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('staff', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        Staff::create($validated);

        return redirect()->route('admin.staff.index')->with('success', 'New command personnel deployed to active duty.');
    }

    public function edit(Staff $staff)
    {
        return Inertia::render('Admin/Staff/Form', [
            'staff' => $staff
        ]);
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_url' => 'nullable|url|max:2048',
            'is_leadership' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
            'social_links' => 'nullable|array',
            'is_public_visible' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($staff->photo && str_starts_with($staff->photo, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $staff->photo));
            }
            $path = $request->file('photo')->store('staff', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Personnel dossiers updated and synchronized.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Personnel records purged.');
    }
}
