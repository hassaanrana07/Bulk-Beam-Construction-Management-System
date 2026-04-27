<?php

namespace App\Policies;

use App\Models\Milestone;
use App\Models\User;

class MilestonePolicy
{
    private function isFinanceRole(User $user): bool
    {
        return $user->hasAnyRole(['Finance Manager', 'Accountant', 'Finance Support', 'Finance', 'Admin Manager']);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage milestones');
    }

    public function view(User $user, Milestone $milestone): bool
    {
        if ($this->isFinanceRole($user)) {
            return true;
        }
        if ($user->hasRole('Manager')) {
            return $user->managedProjects()->pluck('id')->contains($milestone->portfolio_id);
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage milestones');
    }

    public function update(User $user, Milestone $milestone): bool
    {
        if ($this->isFinanceRole($user)) {
            return true;
        }
        if ($user->hasRole('Manager')) {
            return $user->managedProjects()->pluck('id')->contains($milestone->portfolio_id);
        }
        return false;
    }

    public function delete(User $user, Milestone $milestone): bool
    {
        if ($this->isFinanceRole($user)) {
            return true;
        }
        if ($user->hasRole('Manager')) {
            return $user->managedProjects()->pluck('id')->contains($milestone->portfolio_id);
        }
        return false;
    }

    public function restore(User $user, Milestone $milestone): bool
    {
        return $this->isFinanceRole($user) || $user->hasRole('Manager');
    }

    public function forceDelete(User $user, Milestone $milestone): bool
    {
        return $this->isFinanceRole($user);
    }
}
