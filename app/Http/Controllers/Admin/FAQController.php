<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FAQController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/FAQ/Index', [
            'faqs' => FAQ::orderBy('order')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/FAQ/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string',
            'order' => 'integer',
            'is_published' => 'boolean',
            'is_public_visible' => 'boolean',
        ]);

        FAQ::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(FAQ $faq)
    {
        return Inertia::render('Admin/FAQ/Form', [
            'faq' => $faq
        ]);
    }

    public function update(Request $request, FAQ $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string',
            'order' => 'integer',
            'is_published' => 'boolean',
            'is_public_visible' => 'boolean',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(FAQ $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }
}
