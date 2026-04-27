<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use Spatie\Permission\Models\Role;

class StaffRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Rename Editor role for clarity
        $editorRole = Role::where('name', 'Editor')->first();
        if ($editorRole) {
            $editorRole->name = 'Editor (Staff)';
            $editorRole->save();
        }

        // 2. Ensure all Editors and Admin Managers are in the Staff table
        $usersToSync = User::role(['Editor (Staff)', 'Admin Manager'])->get();

        foreach ($usersToSync as $user) {
            Staff::updateOrCreate(
                ['name' => $user->name],
                [
                    'role' => $user->roles->first()->name,
                    'is_active' => true,
                    'is_leadership' => $user->hasRole('Admin Manager'),
                    'bio' => 'System generated staff profile for ' . $user->name,
                ]
            );
        }
    }
}
