<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CertificationController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Certifications/Index', [
            'certifications' => Certification::orderBy('order')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Certifications/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuing_organization' => 'required|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_public_visible' => 'boolean',
            'order' => 'integer',
        ]);

        Certification::create($validated);

        return redirect()->route('admin.certifications.index')->with('success', 'Certification archived.');
    }

    public function edit(Certification $certification)
    {
        return Inertia::render('Admin/Certifications/Form', [
            'certification' => $certification
        ]);
    }

    public function update(Request $request, Certification $certification)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuing_organization' => 'required|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_public_visible' => 'boolean',
            'order' => 'integer',
        ]);

        $certification->update($validated);

        return redirect()->route('admin.certifications.index')->with('success', 'Certification updated.');
    }

    public function destroy(Certification $certification)
    {
        $certification->delete();
        return redirect()->route('admin.certifications.index')->with('success', 'Certification purged.');
    }
}
