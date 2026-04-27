<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $page = \App\Models\Page::where('slug', 'contact')->first();
        if ($page) {
            $page->load(['contentSections' => fn($q) => $q->where('is_active', true)->orderBy('order')]);
            $page->setRelation('sections', $page->contentSections);
        }

        return Inertia::render('Public/Contact', [
            'page' => $page,
            'locations' => Location::where('is_active', true)->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
            'source' => 'nullable|string',
        ]);

        Lead::create([
            ...$validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'new',
        ]);

        return back()->with('success', 'Thank you for your inquiry. Our team will contact you soon.');
    }
}
