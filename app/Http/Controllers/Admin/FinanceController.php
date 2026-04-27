<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Portfolio::query();

        if (!$user->hasRole('Super Admin')) {
            if ($user->hasRole('Admin Manager') || $user->hasRole('Finance Manager') || $user->hasRole('Accountant') || $user->hasRole('Finance Support') || $user->hasRole('Finance')) {
                // They see everything
            } elseif ($user->hasRole('Manager')) {
                $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'Finance Manager');
                })->pluck('id')->toArray();
                $query->whereIn('manager_id', array_merge([$user->id], $financeManagerIds));
            } else {
                $query->where('manager_id', $user->id);
            }
        }

        $portfolios = $query->select([
            'id',
            'title',
            'total_budget',
            'expected_revenue',
            'received_payment',
            'pending_payment',
            'project_profit',
            'execution_status'
        ])->get();

        $stats = [
            'total_invested' => $portfolios->sum('total_budget'),
            'total_revenue' => $portfolios->sum('received_payment'),
            'total_expected' => $portfolios->sum('expected_revenue'),
            'total_pending' => $portfolios->sum('pending_payment'),
            'total_profit' => $portfolios->sum('project_profit'),
            'profit_margin' => $portfolios->sum('received_payment') > 0
                ? ($portfolios->sum('project_profit') / $portfolios->sum('received_payment')) * 100
                : 0,
        ];

        return Inertia::render('Admin/Finance/Index', [
            'products' => $portfolios,
            'stats' => $stats,
            'chart_data' => [
                'profit_performance' => [
                    'labels' => $portfolios->take(10)->pluck('title'),
                    'profit' => $portfolios->take(10)->pluck('project_profit'),
                    'budget' => $portfolios->take(10)->pluck('total_budget'),
                ],
                'efficiency_radar' => [
                    'labels' => ['Completed', 'Ongoing', 'Planning'],
                    'values' => [
                        $portfolios->where('execution_status', 'Completed')->avg('project_profit') ?: 0,
                        $portfolios->where('execution_status', 'Ongoing')->avg('project_profit') ?: 0,
                        $portfolios->where('execution_status', 'Planning')->avg('project_profit') ?: 0,
                    ]
                ]
            ]
        ]);
    }

    public function report(Request $request)
    {
        $ids = $request->input('ids', []);
        $type = $request->input('type');
        $category = $request->input('category');

        $user = auth()->user();
        $query = Portfolio::query();

        if (!$user->hasRole('Super Admin')) {
            if ($user->hasRole('Admin Manager') || $user->hasRole('Finance Manager') || $user->hasRole('Accountant') || $user->hasRole('Finance Support') || $user->hasRole('Finance')) {
                // They see everything
            } elseif ($user->hasRole('Manager')) {
                $financeManagerIds = \App\Models\User::whereHas('roles', function ($q) {
                    $q->where('name', 'Finance Manager');
                })->pluck('id')->toArray();
                $query->whereIn('manager_id', array_merge([$user->id], $financeManagerIds));
            } else {
                $query->where('manager_id', $user->id);
            }
        }

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        if ($category) {
            // Mapping friendly names to status
            $statusMap = [
                'completed_products' => 'Completed',
                'planning_products' => 'Planning',
                'in_progress_products' => 'In Progress',
                'on_hold_products' => 'On Hold',
            ];
            if (isset($statusMap[$category])) {
                $query->where('execution_status', $statusMap[$category]);
            }
        }

        $portfolios = $query->get();

        $stats = [
            'total_invested' => $portfolios->sum('total_budget'),
            'total_revenue' => $portfolios->sum('received_payment'),
            'total_expected' => $portfolios->sum('expected_revenue'),
            'total_pending' => $portfolios->sum('pending_payment'),
            'total_profit' => $portfolios->sum('project_profit'),
            'total_loss' => abs($portfolios->where('project_profit', '<', 0)->sum('project_profit')),
            'profit_margin' => $portfolios->sum('received_payment') > 0
                ? ($portfolios->sum('project_profit') / $portfolios->sum('received_payment')) * 100
                : 0,
            'report_type' => $type ? ucfirst($type) : 'Full Fiscal Audit',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.finance_report', [
            'products' => $portfolios,
            'stats' => $stats,
            'report_type' => $type,
            'category' => $category
        ]);

        $filename = $type ? ucfirst($type) . "-Report-" : "Fiscal-Audit-Report-";
        return $pdf->download($filename . date('Ymd') . ".pdf");
    }
}
