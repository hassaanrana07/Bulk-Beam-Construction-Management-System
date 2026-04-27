<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VendorController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Finance Manager', 'Finance Support', 'Financial Support', 'Finance', 'Manager', 'Admin Manager'])) {
            abort(403);
        }

        return Inertia::render('Admin/Vendors/Index', [
            'vendors' => Vendor::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Finance Manager'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'company_bim' => 'nullable|string|max:255',
            'company_bic' => 'nullable|string|max:255',
        ]);

        Vendor::create($validated);

        return redirect()->back()->with('success', 'Entity registered successfully');
    }

    public function update(Request $request, Vendor $vendor)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Finance Manager'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'company_bim' => 'nullable|string|max:255',
            'company_bic' => 'nullable|string|max:255',
        ]);

        // Restrict BIM/BIC if already set (as per point 3)
        if ($vendor->company_bim && $vendor->company_bim !== $validated['company_bim']) {
            unset($validated['company_bim']);
        }
        if ($vendor->company_bic && $vendor->company_bic !== $validated['company_bic']) {
            unset($validated['company_bic']);
        }

        $vendor->update($validated);

        return redirect()->back()->with('success', 'Entity profile updated');
    }

    public function destroy(Vendor $vendor)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Finance Manager'])) {
            abort(403);
        }

        $vendor->delete();

        return redirect()->back()->with('success', 'Entity purged from archive');
    }
}
