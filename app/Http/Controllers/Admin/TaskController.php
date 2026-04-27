<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Task;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    private function isFinanceRole(User $user): bool
    {
        return $user->hasAnyRole(['Finance Manager', 'Accountant', 'Finance Support', 'Finance', 'Admin Manager']);
    }

    public function index(Request $request)
    {
        $this->authorize('manage tasks');

        $user = auth()->user();
        $query = Task::with(['portfolio', 'assignee'])->latest();

        if (!$this->isFinanceRole($user)) {
            if ($user->hasRole('Manager')) {
                $managedProjectIds = $user->managedProjects()->pluck('id');
                $query->whereIn('portfolio_id', $managedProjectIds);
            } elseif ($user->hasRole('Staff')) {
                $query->where('assigned_to', $user->id);
            }
        }
        // Finance roles see all tasks

        $tasks = $query->get();

        // Add empty state message for frontend
        return Inertia::render('Admin/Tasks/Index', [
            'tasks' => $tasks,
            'portfolios' => Portfolio::select('id', 'title')->get(),
            'staff' => User::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('manage tasks');

        $validated = $request->validate([
            'portfolio_id' => 'required|exists:portfolios,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        $task = Task::create($validated);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Task Created',
            'model_type' => 'Task',
            'model_id' => $task->id,
            'details' => ['message' => 'Task "' . $task->title . '" created.'],
        ]);

        return redirect()->back()->with('success', 'Task created successfully');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:todo,in_progress,completed,blocked',
            'priority' => 'sometimes|required|in:low,medium,high',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Task Updated',
            'model_type' => 'Task',
            'model_id' => $task->id,
            'details' => ['message' => 'Task "' . $task->title . '" updated.'],
        ]);

        return redirect()->back()->with('success', 'Task updated successfully');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Task Deleted',
            'model_type' => 'Task',
            'model_id' => $task->id,
            'details' => ['message' => 'Task "' . $task->title . '" deleted.'],
        ]);

        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully');
    }
}
