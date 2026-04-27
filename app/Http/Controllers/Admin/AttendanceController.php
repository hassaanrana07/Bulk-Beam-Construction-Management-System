<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Attendance::with('user')->latest();

        if (!$user->can('manage attendance')) {
            $query->where('user_id', $user->id);
        }

        return Inertia::render('Admin/Attendances/Index', [
            'attendances' => $query->get(),
            'users' => \App\Models\User::select('id', 'name')->get(),
            'stats' => [
                'present_today' => Attendance::where('date', now()->toDateString())->count(),
                'late_today' => Attendance::where('date', now()->toDateString())->where('status', 'late')->count(),
                'on_leave' => Attendance::where('date', now()->toDateString())->where('status', 'on_leave')->count(),
                'avg_clock_in' => '09:00 AM' // Simplified for now
            ]
        ]);
    }

    public function clockIn()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'present', 'clock_in' => now()->toTimeString()]
        );

        return redirect()->back()->with('success', 'Clocked in successfully');
    }

    public function clockOut()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();
        if ($attendance) {
            $attendance->update(['clock_out' => now()->toTimeString()]);
            return redirect()->back()->with('success', 'Clocked out successfully');
        }

        return redirect()->back()->with('error', 'No clock-in record found for today');
    }

    public function store(Request $request)
    {
        // Existing store method logic can be kept or removed if redundant
        return $this->clockIn();
    }
}
