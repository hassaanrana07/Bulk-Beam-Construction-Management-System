<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CostEstimatorRule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstimateSettingsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Manager', 'Admin Manager'])) {
            abort(403);
        }

        return Inertia::render('Admin/EstimateSettings/Index', [
            'rules' => CostEstimatorRule::orderBy('order')->get()
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Manager', 'Admin Manager'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',
            'scope_level' => 'nullable|string|in:standard,premium,luxury',
            'base_rate_per_sqft' => 'required|numeric|min:0',
            'material_cost_factor' => 'required|numeric|min:0',
            'labor_cost_factor' => 'required|numeric|min:0',
            'timeline_weeks_per_1000sqft' => 'required|integer|min:1',
            'sector_multipliers' => 'nullable|array',
            'sector_multipliers.*' => 'numeric|min:0',
            'complexity_multipliers' => 'nullable|array',
            'complexity_multipliers.*' => 'numeric|min:0',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        CostEstimatorRule::create($validated);

        return redirect()->back()->with('success', 'Estimation rule created successfully.');
    }

    public function update(Request $request, CostEstimatorRule $estimateRule)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Manager', 'Admin Manager'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',
            'scope_level' => 'nullable|string|in:standard,premium,luxury',
            'base_rate_per_sqft' => 'required|numeric|min:0',
            'material_cost_factor' => 'required|numeric|min:0',
            'labor_cost_factor' => 'required|numeric|min:0',
            'timeline_weeks_per_1000sqft' => 'required|integer|min:1',
            'sector_multipliers' => 'nullable|array',
            'sector_multipliers.*' => 'numeric|min:0',
            'complexity_multipliers' => 'nullable|array',
            'complexity_multipliers.*' => 'numeric|min:0',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $estimateRule->update($validated);

        return redirect()->back()->with('success', 'Estimation rule updated successfully.');
    }

    public function destroy(CostEstimatorRule $estimateRule)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin'])) {
            abort(403);
        }

        $estimateRule->delete();

        return redirect()->back()->with('success', 'Estimation rule deleted.');
    }
}
