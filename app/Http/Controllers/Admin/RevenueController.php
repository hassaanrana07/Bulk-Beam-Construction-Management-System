<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RevenueController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['Super Admin', 'Finance Manager', 'Finance Support', 'Financial Support', 'Finance', 'Manager', 'Admin Manager'])) {
            abort(403);
        }

        $query = Portfolio::query();

        if (!$user->hasRole('Super Admin')) {
            if ($user->hasRole('Manager')) {
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
            'expected_revenue',
            'received_payment',
            'pending_payment',
            'execution_status',
            'created_at',
            'manager_id'
        ])->orderByDesc('received_payment')->get();

        $stats = [
            'total_expected' => $portfolios->sum('expected_revenue'),
            'total_received' => $portfolios->sum('received_payment'),
            'total_pending' => $portfolios->sum('pending_payment'),
            'avg_collection_rate' => $portfolios->sum('expected_revenue') > 0
                ? ($portfolios->sum('received_payment') / $portfolios->sum('expected_revenue')) * 100
                : 0,
        ];

        return Inertia::render('Admin/Finance/Revenue', [
            'portfolios' => $portfolios,
            'stats' => $stats,
            'chart_data' => [
                'revenue_stream' => [
                    'labels' => $portfolios->take(10)->pluck('title'),
                    'expected' => $portfolios->take(10)->pluck('expected_revenue'),
                    'received' => $portfolios->take(10)->pluck('received_payment'),
                ],
                'revenue_by_status' => [
                    'labels' => ['Ongoing', 'Completed', 'Planning', 'Delayed'],
                    'values' => [
                        $portfolios->where('execution_status', 'Ongoing')->sum('received_payment'),
                        $portfolios->where('execution_status', 'Completed')->sum('received_payment'),
                        $portfolios->where('execution_status', 'Planning')->sum('received_payment'),
                        $portfolios->where('execution_status', 'Delayed')->sum('received_payment'),
                    ]
                ]
            ]
        ]);
    }
}
