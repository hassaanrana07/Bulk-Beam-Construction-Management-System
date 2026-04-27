<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Leads/Index', [
            'leads' => Lead::latest()->get()
        ]);
    }

    public function show(Lead $lead)
    {
        return Inertia::render('Admin/Leads/Show', [
            'lead' => $lead
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,proposal,won,lost',
            'internal_notes' => 'nullable|string',
            'lead_score' => 'nullable|integer',
        ]);

        $lead->update($validated);

        return back()->with('success', 'Lead data vector updated.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Inquiry vector purged from infrastructure.');
    }
}
