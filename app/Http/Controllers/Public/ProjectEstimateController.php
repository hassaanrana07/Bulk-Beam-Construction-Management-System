<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\ProjectEstimate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ProjectEstimateController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::select('id', 'title', 'location', 'project_type', 'description', 'short_description')->get();
        return Inertia::render('Public/Estimator', [
            'portfolios' => $portfolios
        ]);
    }

    public function calculate(Request $request)
    {
        // For public users, we validate and return the calculated result.
        // If they want to "Save", they need to be signed in (handled by another method or conditional).
        $validated = $request->validate([
            'portfolio_id' => 'nullable|exists:portfolios,id',
            'materials' => 'array',
            'labor' => 'array',
            'equipment' => 'array',
            'other_cost' => 'numeric|min:0',
            'tax_percent' => 'numeric|min:0',
            'contingency_percent' => 'numeric|min:0',
            'profit_percent' => 'numeric|min:0',
        ]);

        // Backend recalculation logic for security
        $materialCost = collect($request->input('materials', []))->sum(function ($item) {
            return ($item['quantity'] ?? 0) * ($item['unit_cost'] ?? 0);
        });

        $laborCost = collect($request->input('labor', []))->sum(function ($item) {
            return ($item['count'] ?? 0) * ($item['days'] ?? 0) * ($item['daily_rate'] ?? 0);
        });

        $equipmentCost = collect($request->input('equipment', []))->sum(function ($item) {
            return ($item['hours'] ?? 0) * ($item['hourly_rate'] ?? 0);
        });

        $otherCost = (float) $validated['other_cost'];
        $subtotal = $materialCost + $laborCost + $equipmentCost + $otherCost;

        $tax = $subtotal * ($validated['tax_percent'] / 100);
        $contingency = $subtotal * ($validated['contingency_percent'] / 100);
        $profit = $subtotal * ($validated['profit_percent'] / 100);

        $total = $subtotal + $tax + $contingency + $profit;

        return response()->json([
            'success' => true,
            'calculated_totals' => [
                'material_cost' => $materialCost,
                'labor_cost' => $laborCost,
                'equipment_cost' => $equipmentCost,
                'other_cost' => $otherCost,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'contingency_amount' => $contingency,
                'profit_amount' => $profit,
                'grand_total' => $total
            ]
        ]);
    }

    public function fetch(Portfolio $portfolio)
    {
        $estimate = ProjectEstimate::where('portfolio_id', $portfolio->id)
            ->with(['materials', 'labor', 'equipment'])
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'estimate' => $estimate,
            'project' => $portfolio
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Authentication required to save estimates.'], 401);
        }

        $validated = $request->validate([
            'portfolio_id' => 'nullable|exists:portfolios,id',
            'project_title' => 'required_if:portfolio_id,null|string|max:255',
            'reference_number' => 'required|unique:project_estimates,reference_number',
            'estimate_date' => 'required|date',
            'materials' => 'array',
            'labor' => 'array',
            'equipment' => 'array',
            'other_cost' => 'numeric|min:0',
            'tax_percent' => 'numeric|min:0',
            'contingency_percent' => 'numeric|min:0',
            'profit_percent' => 'numeric|min:0',
            'material_cost' => 'numeric|min:0',
            'labor_cost' => 'numeric|min:0',
            'equipment_cost' => 'numeric|min:0',
            'total_amount' => 'required|numeric',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $estimate = ProjectEstimate::create([
                'portfolio_id' => $validated['portfolio_id'] ?? null,
                'project_title' => $validated['project_title'] ?? null,
                'reference_number' => $validated['reference_number'],
                'estimate_date' => $validated['estimate_date'],
                'material_cost' => $validated['material_cost'] ?? 0,
                'labor_cost' => $validated['labor_cost'] ?? 0,
                'equipment_cost' => $validated['equipment_cost'] ?? 0,
                'other_cost' => $validated['other_cost'],
                'tax_percent' => $validated['tax_percent'] ?? 0,
                'contingency_percent' => $validated['contingency_percent'] ?? 0,
                'profit_percent' => $validated['profit_percent'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'status' => 'draft',
                'created_by' => auth()->id()
            ]);

            foreach ($request->input('materials', []) as $m) {
                if (!empty($m['item_name'])) {
                    $estimate->materials()->create($m);
                }
            }
            foreach ($request->input('labor', []) as $l) {
                if (!empty($l['worker_type'])) {
                    $estimate->labor()->create($l);
                }
            }
            foreach ($request->input('equipment', []) as $e) {
                if (!empty($e['equipment_name'])) {
                    $estimate->equipment()->create($e);
                }
            }
        });

        return response()->json(['success' => 'Estimate saved to your account.']);
    }
}
