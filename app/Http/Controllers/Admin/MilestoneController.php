<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Milestone;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MilestoneController extends Controller
{
    use AuthorizesRequests;

    private function isFinanceRole(User $user): bool
    {
        return $user->hasAnyRole(['Finance Manager', 'Accountant', 'Finance Support', 'Finance', 'Admin Manager']);
    }

    public function index()
    {
        $this->authorize('manage milestones');

        $user = auth()->user();
        $query = Milestone::with('portfolio');

        if (!$this->isFinanceRole($user)) {
            if ($user->hasRole('Manager')) {
                $managedProjectIds = $user->managedProjects()->pluck('id');
                $query->whereIn('portfolio_id', $managedProjectIds);
            }
        }
        // Finance roles see all milestones

        return Inertia::render('Admin/Milestones/Index', [
            'milestones' => $query->get(),
            'projects' => Portfolio::select('id', 'title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('manage milestones');

        $validated = $request->validate([
            'portfolio_id' => 'required|exists:portfolios,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $milestone = Milestone::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Milestone Created',
            'model_type' => 'Milestone',
            'model_id' => $milestone->id,
            'details' => ['message' => 'Milestone "' . $milestone->title . '" created.'],
        ]);

        return redirect()->back()->with('success', 'Milestone created');
    }

    public function update(Request $request, Milestone $milestone)
    {
        $this->authorize('update', $milestone);

        $validated = $request->validate([
            'status' => 'required|in:pending,completed',
            'title' => 'sometimes|required|string|max:255',
        ]);

        $milestone->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Milestone Updated',
            'model_type' => 'Milestone',
            'model_id' => $milestone->id,
            'details' => ['message' => 'Milestone "' . $milestone->title . '" set to ' . $validated['status'] . '.'],
        ]);

        return redirect()->back()->with('success', 'Milestone updated');
    }

    public function destroy(Milestone $milestone)
    {
        $this->authorize('delete', $milestone);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Milestone Deleted',
            'model_type' => 'Milestone',
            'model_id' => $milestone->id,
            'details' => ['message' => 'Milestone "' . $milestone->title . '" deleted.'],
        ]);

        $milestone->delete();
        return redirect()->back()->with('success', 'Milestone deleted');
    }
}
