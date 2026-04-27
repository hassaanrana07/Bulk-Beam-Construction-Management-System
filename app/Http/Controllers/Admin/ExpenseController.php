<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Portfolio;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['Super Admin', 'Finance Manager', 'Finance Support', 'Financial Support', 'Finance', 'Manager', 'Admin Manager'])) {
            abort(403);
        }

        $query = Expense::with(['portfolio', 'vendor'])->latest();
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
            }
        }

        $expenses = $query->get();
        $filteredProjectIds = $portfolioQuery->pluck('id');

        return Inertia::render('Admin/Expenses/Index', [
            'expenses' => $expenses,
            'portfolios' => $portfolioQuery->select('id', 'title')->get(),
            'vendors' => Vendor::select('id', 'name')->get(),
            'stats' => [
                'total_outflow' => (float) $expenses->sum('amount'),
                'pending_ap' => (float) $expenses->where('status', 'pending')->sum('amount'),
                'monthly_burn' => (float) $expenses->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
                'disputed_amount' => (float) $expenses->where('status', 'disputed')->sum('amount'),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('manage finances');

        $validated = $request->validate([
            'portfolio_id' => 'required|exists:portfolios,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:labor,materials,equipment,overhead',
            'invoice_number' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,disputed'
        ]);

        Expense::create($validated);

        return redirect()->back()->with('success', 'Expense logged successfully');
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('process payments');

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,disputed',
            'paid_at' => 'nullable|date'
        ]);

        if ($validated['status'] === 'paid' && !$expense->paid_at) {
            $validated['paid_at'] = now();
        }

        $expense->update($validated);

        return redirect()->back()->with('success', 'Expense updated');
    }

    public function exportCsv()
    {
        $this->authorize('view finances');
        $user = auth()->user();

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=expenses_' . now()->format('YmdHi') . '.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $callback = function () use ($user) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Project', 'Vendor', 'Amount', 'Category', 'Status', 'Date']);

            $query = Expense::with(['portfolio', 'vendor']);

            if (!$user->hasRole('Super Admin')) {
                if ($user->hasRole('Manager')) {
                    $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                        $q->where('name', 'Finance Manager');
                    })->pluck('id')->toArray();
                    $managedProjectIds = Portfolio::whereIn('manager_id', array_merge([$user->id], $financeManagerIds))->pluck('id');
                    $query->whereIn('portfolio_id', $managedProjectIds);
                } elseif ($user->hasRole('Finance Manager')) {
                    $managedProjectIds = Portfolio::where('manager_id', $user->id)->pluck('id');
                    $query->whereIn('portfolio_id', $managedProjectIds);
                }
            }

            $query->chunk(100, function ($expenses) use ($file) {
                foreach ($expenses as $expense) {
                    fputcsv($file, [
                        $expense->id,
                        $expense->portfolio?->title ?? 'N/A',
                        $expense->vendor?->name ?? 'N/A',
                        $expense->amount,
                        $expense->category,
                        $expense->status,
                        $expense->created_at->format('Y-m-d')
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $this->authorize('view finances');
        $user = auth()->user();

        $query = Expense::with(['portfolio', 'vendor'])->latest();

        if (!$user->hasRole('Super Admin')) {
            if ($user->hasRole('Manager')) {
                $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'Finance Manager');
                })->pluck('id')->toArray();
                $managedProjectIds = Portfolio::whereIn('manager_id', array_merge([$user->id], $financeManagerIds))->pluck('id');
                $query->whereIn('portfolio_id', $managedProjectIds);
            } elseif ($user->hasRole('Finance Manager')) {
                $managedProjectIds = Portfolio::where('manager_id', $user->id)->pluck('id');
                $query->whereIn('portfolio_id', $managedProjectIds);
            }
        }

        $expenses = $query->take(500)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.expenses', compact('expenses'));

        return $pdf->download('expense_report_' . now()->format('YmdHi') . '.pdf');
    }
}
