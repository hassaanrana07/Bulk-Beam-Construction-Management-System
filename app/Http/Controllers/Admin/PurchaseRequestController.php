<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PurchaseRequestController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();
        $query = PurchaseRequest::with(['portfolio', 'user'])->latest();
        $portfolioQuery = Portfolio::query();

        if (!$user->hasRole('Super Admin')) {
            if ($user->hasRole('Manager')) {
                $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'Finance Manager');
                })->pluck('id')->toArray();
                $managedProjectIds = Portfolio::whereIn('manager_id', array_merge([$user->id], $financeManagerIds))->pluck('id');

                $query->whereIn('portfolio_id', $managedProjectIds);
                $portfolioQuery->whereIn('id', $managedProjectIds);
            } elseif ($user->hasRole('Finance Manager')) {
                $managedProjectIds = Portfolio::where('manager_id', $user->id)->pluck('id');
                $query->whereIn('portfolio_id', $managedProjectIds);
                $portfolioQuery->whereIn('id', $managedProjectIds);
            } elseif ($user->hasRole('Staff')) {
                $query->where('user_id', $user->id);
            }
        }

        return Inertia::render('Admin/PurchaseRequests/Index', [
            'requests' => $query->get(),
            'portfolios' => $portfolioQuery->select('id', 'title')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'portfolio_id' => 'required|exists:portfolios,id',
            'title' => 'required|string|max:255',
            'items' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
        ]);

        PurchaseRequest::create($validated + ['user_id' => auth()->id(), 'status' => 'pending']);

        return redirect()->back()->with('success', 'Purchase request submitted');
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['Super Admin', 'Finance Manager', 'Manager'])) {
            abort(403);
        }

        $validated = $request->validate([
            'portfolio_id' => 'sometimes|required|exists:portfolios,id',
            'title' => 'sometimes|required|string|max:255',
            'items' => 'sometimes|required|string',
            'estimated_cost' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:pending,manager_approved,finance_processed,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $purchaseRequest->update($validated);

        return redirect()->back()->with('success', 'Procurement request updated');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Finance Manager'])) {
            abort(403);
        }

        $purchaseRequest->delete();

        return redirect()->back()->with('success', 'Procurement request purged');
    }
}
