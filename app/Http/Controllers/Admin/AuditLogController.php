<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuditLogController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('manage audit logs');

        $query = AuditLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        return Inertia::render('Admin/AuditLogs/Index', [
            'logs' => $query->paginate(50)->withQueryString(),
            'filters' => $request->only(['user_id', 'action'])
        ]);
    }

    public function exportCsv()
    {
        $this->authorize('manage audit logs');

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=audit_logs_' . now()->format('YmdHi') . '.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Action', 'Model', 'Model ID', 'IP Address', 'Timestamp']);

            AuditLog::with('user')->chunk(100, function ($logs) use ($file) {
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->user?->name ?? 'System',
                        $log->action,
                        $log->model_type,
                        $log->model_id,
                        $log->ip_address,
                        $log->created_at
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
