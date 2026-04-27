<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Blog;
use App\Models\Inquiry;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();
        $role = $user->getRoleNames()->first();

        // Common Data
        $data = [
            'user_role' => $role,
            'recent_announcements' => \App\Models\Announcement::where(function ($q) use ($role) {
                $q->whereNull('target_roles')->orWhereJsonContains('target_roles', $role);
            })->latest()->take(5)->get(),
        ];

        // Route to appropriate dashboard
        if ($user->hasRole('Super Admin')) {
            return $this->superAdminDashboard($data);
        } elseif ($user->hasRole('Admin Manager') || $user->hasRole('Manager') || $user->hasRole('Finance Manager') || $user->hasRole('Finance Support') || $user->hasRole('Finance') || $user->hasRole('Accountant')) {
            return $this->financeDashboard($data);
        }

        // Fallback: render generic dashboard
        return Inertia::render('Admin/Dashboard', array_merge($data, ['stats' => []]));
    }

    protected function superAdminDashboard($data)
    {
        $totalProjects = Portfolio::count();
        $activeProjects = Portfolio::where('execution_status', 'Ongoing')->count();
        $completedProjects = Portfolio::where('execution_status', 'Completed')->count();
        $pendingProjects = Portfolio::whereNotIn('execution_status', ['Ongoing', 'Completed'])->count();

        $totalRevenue = (float) Portfolio::sum('received_payment');
        $totalProfit = (float) Portfolio::sum('project_profit');
        $totalExpenses = (float) \App\Models\Expense::sum('amount');

        $isSqlite = DB::getDriverName() === 'sqlite';
        $yearFormat = $isSqlite ? "strftime('%Y', created_at)" : "YEAR(created_at)";

        // Yearly performance
        $yearlyData = Portfolio::select(
            DB::raw("$yearFormat as year"),
            DB::raw('SUM(received_payment) as revenue'),
            DB::raw('SUM(project_profit) as profit'),
            DB::raw('SUM(total_budget) as expenses')
        )->groupBy('year')->orderBy('year')->get();

        // User distribution
        $userDistribution = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('roles.name', DB::raw('count(*) as total'))
            ->groupBy('roles.name')
            ->get();

        // Product-wise revenue (top 20)
        $projectRevenue = Portfolio::select('id', 'title', 'execution_status', 'received_payment', 'project_profit')
            ->orderByDesc('received_payment')
            ->take(20)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'status' => $p->execution_status,
                'revenue' => (float) $p->received_payment,
                'profit' => (float) $p->project_profit,
            ]);

        $monthFormat = $isSqlite ? "CAST(strftime('%m', created_at) AS integer)" : "MONTH(created_at)";
        $monthNameFormat = $isSqlite ?
            "(CASE strftime('%m', created_at) 
                WHEN '01' THEN 'January' WHEN '02' THEN 'February' WHEN '03' THEN 'March' 
                WHEN '04' THEN 'April' WHEN '05' THEN 'May' WHEN '06' THEN 'June' 
                WHEN '07' THEN 'July' WHEN '08' THEN 'August' WHEN '09' THEN 'September' 
                WHEN '10' THEN 'October' WHEN '11' THEN 'November' WHEN '12' THEN 'December' 
             END)" : "MONTHNAME(created_at)";

        // Monthly data for financial trends
        $monthlyData = Portfolio::select(
            DB::raw("$monthFormat as month"),
            DB::raw("$monthNameFormat as month_name"),
            DB::raw('SUM(received_payment) as revenue'),
            DB::raw('SUM(project_profit) as profit'),
            DB::raw('SUM(pending_payment) as pending'),
            DB::raw('SUM(total_budget) as expenses')
        )->groupBy('month', 'month_name')->orderBy('month')->get();

        return Inertia::render('Admin/Dashboard', array_merge($data, [
            'stats' => [
                'total_projects' => $totalProjects,
                'active_projects' => $activeProjects,
                'completed_projects' => $completedProjects,
                'pending_projects' => $pendingProjects,
                'user_count' => \App\Models\User::count(),
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'total_expenses' => $totalExpenses,
                'total_expected' => (float) Portfolio::sum('expected_revenue'),
                'total_pending' => (float) Portfolio::sum('pending_payment'),
                'total_loss' => abs((float) Portfolio::where('project_profit', '<', 0)->sum('project_profit')),
                'collection_rate' => Portfolio::sum('expected_revenue') > 0
                    ? ($totalRevenue / Portfolio::sum('expected_revenue')) * 100
                    : 0,
                'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
            ],
            'chart_data' => [
                'projects' => [
                    'labels' => ['Active', 'Completed', 'Pending', 'Total'],
                    'values' => [$activeProjects, $completedProjects, $pendingProjects, $totalProjects],
                ],
                'financial_overview' => [
                    'labels' => $monthlyData->pluck('month_name'),
                    'revenue' => $monthlyData->pluck('revenue'),
                    'expenses' => $monthlyData->pluck('expenses'),
                ],
                'collection_matrix' => [
                    'labels' => $monthlyData->pluck('month_name'),
                    'received' => $monthlyData->pluck('revenue'),
                    'pending' => $monthlyData->pluck('pending'),
                ],
                'revenue_by_status' => [
                    'labels' => ['Ongoing', 'Completed', 'Planning', 'Delayed'],
                    'values' => [
                        Portfolio::where('execution_status', 'Ongoing')->sum('received_payment'),
                        Portfolio::where('execution_status', 'Completed')->sum('received_payment'),
                        Portfolio::where('execution_status', 'Planning')->sum('received_payment'),
                        Portfolio::where('execution_status', 'Delayed')->sum('received_payment'),
                    ]
                ],
                'product_stats' => [
                    'labels' => ['Ongoing', 'Completed', 'Pending', 'Delayed'],
                    'values' => [$activeProjects, $completedProjects, $pendingProjects, Portfolio::where('execution_status', 'Delayed')->count()],
                ],
                'revenue_stream' => [
                    'labels' => $projectRevenue->take(10)->pluck('title'),
                    'expected' => Portfolio::whereIn('id', $projectRevenue->take(10)->pluck('id'))->pluck('expected_revenue'),
                    'received' => $projectRevenue->take(10)->pluck('revenue'),
                ],
                'profit_loss_trend' => [
                    'labels' => $monthlyData->pluck('month_name'),
                    'profit' => $monthlyData->pluck('profit'),
                    'loss' => $monthlyData->map(fn($m) => abs(Portfolio::whereMonth('created_at', $m->month)->where('project_profit', '<', 0)->sum('project_profit'))),
                ]
            ],
            'recent_activity' => \App\Models\AuditLog::with('user')->latest()->take(10)->get(),
        ]));
    }

    protected function managerDashboard($data, $user)
    {
        if ($user->hasRole('Admin Manager')) {
            $projects = Portfolio::latest()->take(20)->get();
        } else {
            $projects = method_exists($user, 'managedProjects')
                ? $user->managedProjects()->get()
                : Portfolio::latest()->take(20)->get();
        }

        $projectIds = $projects->pluck('id');
        $totalProjects = $projects->count();
        $ongoingProjects = $projects->where('execution_status', 'Ongoing')->count();
        $completedProjects = $projects->where('execution_status', 'Completed')->count();
        $pendingProjects = $projects->whereNotIn('execution_status', ['Ongoing', 'Completed'])->count();
        $delayedProjects = $projects->where('execution_status', 'Delayed')->count();

        return Inertia::render('Admin/Dashboard', array_merge($data, [
            'stats' => [
                'total_projects' => $totalProjects,
                'my_projects' => $totalProjects,
                'on_track' => $ongoingProjects,
                'completed_projects' => $completedProjects,
                'pending_projects' => $pendingProjects,
                'delayed' => $delayedProjects,
                'services_count' => Service::count(),
                'staff_count' => \App\Models\User::whereHas('roles', function ($q) {
                    $q->whereIn('name', ['Staff', 'Editor (Staff)']);
                })->count(),
            ],
            'projects' => $projects->map(function ($p) {
                $totalBudget = (float) $p->total_budget;
                $spent = \App\Models\Expense::where('portfolio_id', $p->id)->sum('amount');
                $taskTotal = \App\Models\Task::where('portfolio_id', $p->id)->count();
                $taskDone = \App\Models\Task::where('portfolio_id', $p->id)->where('status', 'completed')->count();

                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'status' => $p->execution_status,
                    'budget_consumed' => $totalBudget > 0 ? (int) (($spent / $totalBudget) * 100) : 0,
                    'task_completion' => $taskTotal > 0 ? (int) (($taskDone / $taskTotal) * 100) : 0,
                    'deadline' => $p->completion_date,
                ];
            }),
            'upcoming_deadlines' => \App\Models\Task::with('portfolio')
                ->whereIn('portfolio_id', $projectIds)
                ->where('status', '!=', 'completed')
                ->where('deadline', '>', now())
                ->orderBy('deadline')
                ->take(5)
                ->get(),
            'team_members' => \App\Models\User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['Manager', 'Admin Manager']);
            })->take(10)->get(),
        ]));
    }

    protected function financeDashboard($data)
    {
        $user = auth()->user();
        $query = Portfolio::query();

        // Finance roles see all projects; Managers see their projects; others see their own projects
        if (!$user->hasRole('Super Admin')) {
            if ($user->hasRole('Admin Manager') || $user->hasRole('Finance Manager') || $user->hasRole('Accountant') || $user->hasRole('Finance Support') || $user->hasRole('Finance')) {
                // They see everything
            } elseif ($user->hasRole('Manager')) {
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

        $filteredProjects = $query->get();
        $projectIds = $filteredProjects->pluck('id');

        $totalRevenue = (float) $filteredProjects->sum('received_payment');
        $totalProfit = (float) $filteredProjects->sum('project_profit');
        $totalExpenses = (float) \App\Models\Expense::whereIn('portfolio_id', $projectIds)->sum('amount');
        $totalLoss = (float) $filteredProjects->where('project_profit', '<', 0)->sum('project_profit');
        $totalPending = (float) $filteredProjects->sum('pending_payment');
        $totalExpected = (float) $filteredProjects->sum('expected_revenue');

        // Spend by category
        $expensesByCategory = \App\Models\Expense::whereIn('portfolio_id', $projectIds)
            ->select('category', DB::raw('sum(amount) as total'))
            ->groupBy('category')
            ->get();

        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthFormat = $isSqlite ? "CAST(strftime('%m', created_at) AS integer)" : "MONTH(created_at)";
        $monthNameFormat = $isSqlite ?
            "(CASE strftime('%m', created_at) 
                WHEN '01' THEN 'January' WHEN '02' THEN 'February' WHEN '03' THEN 'March' 
                WHEN '04' THEN 'April' WHEN '05' THEN 'May' WHEN '06' THEN 'June' 
                WHEN '07' THEN 'July' WHEN '08' THEN 'August' WHEN '09' THEN 'September' 
                WHEN '10' THEN 'October' WHEN '11' THEN 'November' WHEN '12' THEN 'December' 
             END)" : "MONTHNAME(created_at)";

        // Monthly revenue/expenses for chart
        $monthlyData = Portfolio::whereIn('id', $projectIds)
            ->select(
                DB::raw("$monthFormat as month"),
                DB::raw("$monthNameFormat as month_name"),
                DB::raw('SUM(received_payment) as revenue'),
                DB::raw('SUM(project_profit) as profit'),
                DB::raw('SUM(pending_payment) as pending')
            )->groupBy('month', 'month_name')->orderBy('month')->get();

        $monthlyExpenses = \App\Models\Expense::whereIn('portfolio_id', $projectIds)
            ->select(
                DB::raw("$monthFormat as month"),
                DB::raw('SUM(amount) as total')
            )->groupBy('month')->orderBy('month')->get()->keyBy('month');

        // Profit trend per project
        $profitTrend = Portfolio::whereIn('id', $projectIds)
            ->select('id', 'title', 'project_profit', 'total_budget', 'expected_revenue', 'received_payment')
            ->orderByDesc('project_profit')
            ->take(10)
            ->get();

        $projectCount = $filteredProjects->count();
        $runningCount = $filteredProjects->where('execution_status', 'Ongoing')->count();
        $pendingCount = $filteredProjects->whereNotIn('execution_status', ['Ongoing', 'Completed'])->count();
        $completedCount = $filteredProjects->where('execution_status', 'Completed')->count();
        $delayedCount = $filteredProjects->where('execution_status', 'Delayed')->count();

        return Inertia::render('Admin/Dashboard', array_merge($data, [
            'stats' => [
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'total_loss' => abs($totalLoss),
                'total_expenses' => $totalExpenses,
                'total_pending' => $totalPending,
                'total_expected' => $totalExpected,
                'profit_margin' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
                'average_profit' => $projectCount > 0 ? $totalProfit / $projectCount : 0,
                'project_count' => $projectCount,
                'ongoing_count' => $runningCount,
                'completed_count' => $completedCount,
                'pending_count' => $pendingCount,
                'delayed_count' => $delayedCount,
                'collection_rate' => $totalExpected > 0 ? ($totalRevenue / $totalExpected) * 100 : 0,
                'pending_purchase_requests' => \App\Models\PurchaseRequest::whereIn('portfolio_id', $projectIds)->where('status', 'manager_approved')->count(),
                'pending_invoices' => \App\Models\Expense::whereIn('portfolio_id', $projectIds)->where('status', 'pending')->count(),
            ],
            'chart_data' => [
                'spend_by_category' => [
                    'labels' => $expensesByCategory->pluck('category')->map(fn($c) => ucfirst($c)),
                    'values' => $expensesByCategory->pluck('total'),
                ],
                'profit_trend' => [
                    'labels' => $profitTrend->pluck('title'),
                    'profit' => $profitTrend->pluck('project_profit'),
                    'expenses' => $profitTrend->map(fn($p) => (float) $p->total_budget - (float) $p->project_profit),
                ],
                'financial_overview' => [
                    'labels' => $monthlyData->pluck('month_name'),
                    'revenue' => $monthlyData->pluck('revenue'),
                    'expenses' => $monthlyData->map(fn($m) => (float) ($monthlyExpenses->get($m->month)?->total ?? 0)),
                ],
                'collection_matrix' => [
                    'labels' => $monthlyData->pluck('month_name'),
                    'received' => $monthlyData->pluck('revenue'),
                    'pending' => $monthlyData->pluck('pending'),
                ],
                'revenue_by_status' => [
                    'labels' => ['Ongoing', 'Completed', 'Planning', 'Delayed'],
                    'values' => [
                        $filteredProjects->where('execution_status', 'Ongoing')->sum('received_payment'),
                        $filteredProjects->where('execution_status', 'Completed')->sum('received_payment'),
                        $filteredProjects->where('execution_status', 'Planning')->sum('received_payment'),
                        $filteredProjects->where('execution_status', 'Delayed')->sum('received_payment'),
                    ]
                ],
                'product_stats' => [
                    'labels' => ['Ongoing', 'Completed', 'Pending', 'Delayed'],
                    'values' => [$runningCount, $completedCount, $pendingCount, $delayedCount],
                ],
                'revenue_stream' => [
                    'labels' => $profitTrend->pluck('title'),
                    'expected' => $profitTrend->pluck('expected_revenue'),
                    'received' => $profitTrend->pluck('received_payment'),
                ],
                'profit_loss_trend' => [
                    'labels' => $monthlyData->pluck('month_name'),
                    'profit' => $monthlyData->pluck('profit'),
                    'loss' => $monthlyData->map(fn($m) => abs(Portfolio::whereIn('id', $projectIds)->whereMonth('created_at', $m->month)->where('project_profit', '<', 0)->sum('project_profit'))),
                ]
            ],
            'recent_expenses' => \App\Models\Expense::whereIn('portfolio_id', $projectIds)->with(['portfolio', 'vendor'])->latest()->take(10)->get(),
            'purchase_queue' => \App\Models\PurchaseRequest::whereIn('portfolio_id', $projectIds)->with(['portfolio', 'user'])->where('status', 'manager_approved')->get(),
            'team_members' => \App\Models\User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['Finance Manager', 'Finance Support', 'Financial Support', 'Finance']);
            })->take(10)->get(),
            'projects' => $filteredProjects->sortByDesc('received_payment'),
        ]));
    }

    public function systemMetrics($data = [])
    {
        $this->authorize('manage settings');

        $data = [
            'user_role' => auth()->user()->getRoleNames()->first(),
            'stats' => [
                'cpu_load' => '12%',
                'memory_usage' => '450MB / 2GB',
                'disk_usage' => '1.2GB / 20GB',
                'db_size' => '24MB',
                'active_sessions' => \Illuminate\Support\Facades\DB::table('sessions')->count() ?? 0,
                'error_count' => 0,
                'last_backup' => now()->subDay()->format('Y-m-d H:i'),
            ],
            'recent_logs' => \App\Models\AuditLog::with('user')->latest()->take(20)->get(),
        ];

        return Inertia::render('Admin/SystemMetrics', $data);
    }
}
