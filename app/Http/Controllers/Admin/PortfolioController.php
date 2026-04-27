<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Portfolio::latest();

        if (!$user->hasRole('Super Admin')) {
            // Managers and Finance Managers see products they manage
            // If the user is a 'Manager', they might see 'Finance Manager''s products too
            // based on the instruction: "Manager: limited to Finance Manager’s portfolio products and reports"
            if ($user->hasRole('Manager')) {
                // Find IDs of users with 'Finance Manager' role
                $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'Finance Manager');
                })->pluck('id')->toArray();
                $query->whereIn('manager_id', array_merge([$user->id], $financeManagerIds));
            } else {
                $query->where('manager_id', $user->id);
            }
        }

        return Inertia::render('Admin/Portfolio/Index', [
            'projects' => $query->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Portfolio/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolios,slug',
            'short_description' => 'nullable|string',
            'project_type' => 'required|string',
            'location' => 'nullable|string',
            'budget_range' => 'nullable|string',
            'total_budget' => 'nullable|numeric|min:0',
            'expected_revenue' => 'nullable|numeric|min:0',
            'received_payment' => 'nullable|numeric|min:0',
            'execution_status' => 'required|in:Planning,In Progress,Completed,On Hold,Ongoing,Delayed',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            // Case Study
            'case_study_category' => 'nullable|string|max:255',
            'case_study_scope' => 'nullable|string|max:255',
            'case_study_sector' => 'nullable|string|max:255',
            'cs_phase_1' => 'nullable|string|max:255',
            'cs_phase_2' => 'nullable|string|max:255',
            'cs_phase_3' => 'nullable|string|max:255',
            'cs_phase_4' => 'nullable|string|max:255',
            'cs_phase_5' => 'nullable|string|max:255',
            'cs_duration_weeks' => 'nullable|integer|min:0',
            'cs_team' => 'nullable|string|max:255',
            'cs_total_value' => 'nullable|string|max:255',
            'is_public_visible' => 'boolean',
            'structural_features' => 'nullable|array',
            'base_structure' => 'nullable|string|max:255',
            'foundation_type' => 'nullable|string|max:255',
            'total_floors' => 'nullable|integer|min:0',
            'floor_composition' => 'nullable|string|max:255',
            'capabilities' => 'nullable|array',
            'functional_features' => 'nullable|array',
            'technology_used' => 'nullable|string|max:255',
            'construction_technology' => 'nullable|string|max:255',
            'tools_used' => 'nullable|array',
            'framework_type' => 'nullable|string|max:255',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('portfolios', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        $validated['manager_id'] = auth()->id();

        Portfolio::create($validated);

        return redirect()->route('admin.portfolios.index')->with('success', 'Project created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        $this->authorizeAccess($portfolio);

        return Inertia::render('Admin/Portfolio/Form', [
            'project' => $portfolio
        ]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $this->authorizeAccess($portfolio);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:portfolios,slug,' . $portfolio->id,
            'short_description' => 'nullable|string',
            'project_type' => 'required|string',
            'location' => 'nullable|string',
            'budget_range' => 'nullable|string',
            'total_budget' => 'nullable|numeric|min:0',
            'expected_revenue' => 'nullable|numeric|min:0',
            'received_payment' => 'nullable|numeric|min:0',
            'execution_status' => 'required|in:Planning,In Progress,Completed,On Hold,Ongoing,Delayed',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            // Case Study
            'case_study_category' => 'nullable|string|max:255',
            'case_study_scope' => 'nullable|string|max:255',
            'case_study_sector' => 'nullable|string|max:255',
            'cs_phase_1' => 'nullable|string|max:255',
            'cs_phase_2' => 'nullable|string|max:255',
            'cs_phase_3' => 'nullable|string|max:255',
            'cs_phase_4' => 'nullable|string|max:255',
            'cs_phase_5' => 'nullable|string|max:255',
            'cs_duration_weeks' => 'nullable|integer|min:0',
            'cs_team' => 'nullable|string|max:255',
            'cs_total_value' => 'nullable|string|max:255',
            'is_public_visible' => 'boolean',
            'structural_features' => 'nullable|array',
            'base_structure' => 'nullable|string|max:255',
            'foundation_type' => 'nullable|string|max:255',
            'total_floors' => 'nullable|integer|min:0',
            'floor_composition' => 'nullable|string|max:255',
            'capabilities' => 'nullable|array',
            'functional_features' => 'nullable|array',
            'technology_used' => 'nullable|string|max:255',
            'construction_technology' => 'nullable|string|max:255',
            'tools_used' => 'nullable|array',
            'framework_type' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($portfolio->featured_image && str_starts_with($portfolio->featured_image, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $portfolio->featured_image));
            }
            $path = $request->file('featured_image')->store('portfolios', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        $portfolio->update($validated);

        return redirect()->route('admin.portfolios.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $this->authorizeAccess($portfolio);

        $portfolio->delete();
        return redirect()->route('admin.portfolios.index')->with('success', 'Case study purged.');
    }

    public function generatePdf(Portfolio $portfolio)
    {
        $this->authorizeAccess($portfolio);

        $portfolio->load(['manager', 'tasks', 'milestones', 'expenses']);
        
        $auditLogs = \App\Models\AuditLog::where('model_type', get_class($portfolio))
            ->where('model_id', $portfolio->id)
            ->latest()
            ->take(20)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.portfolio', compact('portfolio', 'auditLogs'));
        return $pdf->download("Asset-Audit-{$portfolio->slug}.pdf");
    }

    protected function authorizeAccess(Portfolio $portfolio)
    {
        $user = auth()->user();
        if ($user->hasRole('Super Admin'))
            return;

        if ($user->hasRole('Manager')) {
            $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                $q->where('name', 'Finance Manager');
            })->pluck('id')->toArray();
            if ($portfolio->manager_id === $user->id || in_array($portfolio->manager_id, $financeManagerIds)) {
                return;
            }
        }

        if ($portfolio->manager_id === $user->id)
            return;

        abort(403, 'Unauthorized access to this portfolio.');
    }

    public function bulkPdf(Request $request)
    {
        $ids = $request->input('ids', []);
        $user = auth()->user();
        $query = Portfolio::whereIn('id', $ids)->with(['manager', 'tasks', 'milestones', 'expenses']);

        if (!$user->hasRole('Super Admin')) {
            if ($user->hasRole('Manager')) {
                $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'Finance Manager');
                })->pluck('id')->toArray();
                $query->where(function ($q) use ($user, $financeManagerIds) {
                    $q->where('manager_id', $user->id)->orWhereIn('manager_id', $financeManagerIds);
                });
            } else {
                $query->where('manager_id', $user->id);
            }
        }

        $portfolios = $query->get();

        if ($portfolios->isEmpty()) {
            return back()->with('error', 'No assets selected for audit.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.bulk_portfolio', compact('portfolios'));
        return $pdf->download("Bulk-Asset-Audit-" . date('Ymd') . ".pdf");
    }
}
