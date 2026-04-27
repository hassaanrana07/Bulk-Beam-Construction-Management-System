<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InquiryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Inquiries/Index', [
            'inquiries' => Inquiry::latest()->get()
        ]);
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,resolved',
        ]);

        $inquiry->update($validated);

        return back()->with('success', 'Inquiry status updated successfully.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return back()->with('success', 'Inquiry deleted successfully.');
    }
}
