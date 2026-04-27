<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectEstimate;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProjectEstimateController extends Controller
{
    public function index()
    {
        $estimates = ProjectEstimate::with('portfolio')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Estimates/Index', [
            'estimates' => $estimates
        ]);
    }

    public function create()
    {
        $portfolios = Portfolio::with('manager')->get();

        $nextId = ProjectEstimate::max('id') + 1;
        $referenceNumber = 'EST-' . date('Ymd') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return Inertia::render('Admin/Estimates/Create', [
            'portfolios' => $portfolios,
            'defaultReference' => $referenceNumber
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'portfolio_id' => 'nullable|exists:portfolios,id',
            'project_title' => 'required_if:portfolio_id,null|string|max:255',
            'reference_number' => 'required|unique:project_estimates,reference_number',
            'estimate_date' => 'required|date',
            'materials' => 'array',
            'labor' => 'array',
            'equipment' => 'array',
            'other_costs_details' => 'array',
            'other_cost' => 'numeric|min:0',
            'tax_percent' => 'numeric|min:0',
            'contingency_percent' => 'numeric|min:0',
            'profit_percent' => 'numeric|min:0',
            'material_cost' => 'numeric|min:0',
            'labor_cost' => 'numeric|min:0',
            'equipment_cost' => 'numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $estimate = ProjectEstimate::create([
                'portfolio_id' => $validated['portfolio_id'] ?? null,
                'project_title' => $validated['project_title'] ?? null,
                'reference_number' => $validated['reference_number'],
                'estimate_date' => $validated['estimate_date'],
                'material_cost' => $validated['material_cost'],
                'labor_cost' => $validated['labor_cost'],
                'equipment_cost' => $validated['equipment_cost'],
                'other_cost' => $validated['other_cost'],
                'other_costs_details' => $validated['other_costs_details'],
                'tax_percent' => $validated['tax_percent'],
                'contingency_percent' => $validated['contingency_percent'],
                'profit_percent' => $validated['profit_percent'],
                'total_amount' => $validated['total_amount'],
                'status' => 'draft',
                'created_by' => $request->user()?->id,
            ]);

            // Save Materials
            foreach ($request->input('materials', []) as $material) {
                if (!empty($material['item_name'])) {
                    $estimate->materials()->create($material);
                }
            }

            // Save Labor
            foreach ($request->input('labor', []) as $labor) {
                if (!empty($labor['worker_type'])) {
                    $estimate->labor()->create($labor);
                }
            }

            // Save Equipment
            foreach ($request->input('equipment', []) as $equipment) {
                if (!empty($equipment['equipment_name'])) {
                    $estimate->equipment()->create($equipment);
                }
            }
        });

        return redirect()->route('admin.estimates.index')
            ->with('success', 'Project estimate created successfully.');
    }

    public function show(ProjectEstimate $estimate)
    {
        return Inertia::render('Admin/Estimates/Show', [
            'estimate' => $estimate->load(['portfolio', 'materials', 'labor', 'equipment'])
        ]);
    }

    public function edit(ProjectEstimate $estimate)
    {
        $portfolios = Portfolio::select('id', 'title')->get();
        return Inertia::render('Admin/Estimates/Create', [
            'estimate' => $estimate->load(['materials', 'labor', 'equipment']),
            'portfolios' => $portfolios,
            'isEditing' => true
        ]);
    }

    public function update(Request $request, ProjectEstimate $estimate)
    {
        $validated = $request->validate([
            'portfolio_id' => 'nullable|exists:portfolios,id',
            'project_title' => 'required_if:portfolio_id,null|string|max:255',
            'reference_number' => 'required|unique:project_estimates,reference_number,' . $estimate->id,
            'estimate_date' => 'required|date',
            'materials' => 'array',
            'labor' => 'array',
            'equipment' => 'array',
            'other_costs_details' => 'array',
            'other_cost' => 'numeric|min:0',
            'tax_percent' => 'numeric|min:0',
            'contingency_percent' => 'numeric|min:0',
            'profit_percent' => 'numeric|min:0',
            'material_cost' => 'numeric|min:0',
            'labor_cost' => 'numeric|min:0',
            'equipment_cost' => 'numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $request, $estimate) {
            $estimate->update([
                'portfolio_id' => $validated['portfolio_id'] ?? null,
                'project_title' => $validated['project_title'] ?? null,
                'reference_number' => $validated['reference_number'],
                'estimate_date' => $validated['estimate_date'],
                'material_cost' => $validated['material_cost'],
                'labor_cost' => $validated['labor_cost'],
                'equipment_cost' => $validated['equipment_cost'],
                'other_cost' => $validated['other_cost'],
                'other_costs_details' => $validated['other_costs_details'],
                'tax_percent' => $validated['tax_percent'],
                'contingency_percent' => $validated['contingency_percent'],
                'profit_percent' => $validated['profit_percent'],
                'total_amount' => $validated['total_amount'],
                'status' => $validated['status'],
            ]);

            // Re-sync relations (Delete and recreate for simple MVP)
            $estimate->materials()->delete();
            foreach ($request->input('materials', []) as $material) {
                if (!empty($material['item_name'])) {
                    $estimate->materials()->create($material);
                }
            }

            $estimate->labor()->delete();
            foreach ($request->input('labor', []) as $labor) {
                if (!empty($labor['worker_type'])) {
                    $estimate->labor()->create($labor);
                }
            }

            $estimate->equipment()->delete();
            foreach ($request->input('equipment', []) as $equipment) {
                if (!empty($equipment['equipment_name'])) {
                    $estimate->equipment()->create($equipment);
                }
            }
        });

        return redirect()->route('admin.estimates.index')
            ->with('success', 'Project estimate updated successfully.');
    }

    public function destroy(ProjectEstimate $estimate)
    {
        $estimate->delete();
        return redirect()->route('admin.estimates.index')
            ->with('success', 'Project estimate deleted successfully.');
    }
}
