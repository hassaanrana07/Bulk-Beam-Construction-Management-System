<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnnouncementController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => Announcement::with('user')->latest()->get(),
            'roles' => Role::pluck('name')
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('broadcast announcements');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_roles' => 'nullable|array',
            'expires_at' => 'nullable|date'
        ]);

        Announcement::create($validated + ['user_id' => auth()->id()]);

        return redirect()->back()->with('success', 'Announcement broadcasted');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorize('broadcast announcements');
        $announcement->delete();
        return redirect()->back()->with('success', 'Announcement removed');
    }
}
