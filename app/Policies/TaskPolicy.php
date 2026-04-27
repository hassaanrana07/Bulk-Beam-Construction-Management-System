<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    private function isFinanceRole(User $user): bool
    {
        return $user->hasAnyRole(['Finance Manager', 'Accountant', 'Finance Support', 'Finance', 'Admin Manager']);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage tasks');
    }

    public function view(User $user, Task $task): bool
    {
        if ($this->isFinanceRole($user)) {
            return true;
        }
        if ($user->hasRole('Manager')) {
            return $user->managedProjects()->pluck('id')->contains($task->portfolio_id);
        }
        if ($user->hasRole('Staff')) {
            return $task->assigned_to === $user->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage tasks');
    }

    public function update(User $user, Task $task): bool
    {
        if ($this->isFinanceRole($user)) {
            return true;
        }
        if ($user->hasRole('Manager')) {
            return $user->managedProjects()->pluck('id')->contains($task->portfolio_id);
        }
        if ($user->hasRole('Staff')) {
            // Staff can only update status of their own tasks
            return $task->assigned_to === $user->id;
        }
        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        if ($this->isFinanceRole($user)) {
            return true;
        }
        if ($user->hasRole('Manager')) {
            return $user->managedProjects()->pluck('id')->contains($task->portfolio_id);
        }
        return false;
    }

    public function restore(User $user, Task $task): bool
    {
        return $this->isFinanceRole($user) || $user->hasRole('Manager');
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $this->isFinanceRole($user);
    }
}
