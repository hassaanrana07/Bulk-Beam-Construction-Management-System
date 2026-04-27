<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Core
            'manage dashboard',
            'manage users',
            'manage roles',
            'manage settings',
            'manage audit logs',
            'broadcast announcements',
            'view analytics',
            'generate reports',
            'export data',

            // Project & Management
            'manage projects',
            'reassign projects',
            'archive projects',
            'manage tasks',
            'manage milestones',
            'view team',
            'manage documents',
            'approve requests',
            'submit progress log',
            'manage attendance',

            // Finance
            'view finance snapshot',
            'manage finances',
            'control budgets',
            'manage vendors',
            'manage expenses',
            'manage payroll',
            'process payments',

            // Legacy support
            'manage pages',
            'manage services',
            'manage leads',
            'manage blog',
            'manage testimonials',
            'manage faqs',
            'manage certifications',
            'manage locations',
            'manage careers',
            'manage media',
            'manage staff',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // 1. Super Admin
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        // Bypasses all via Gate::before

        // 2. Manager
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->syncPermissions([
            'manage dashboard',
            'manage projects',
            'manage tasks',
            'manage milestones',
            'view team',
            'manage documents',
            'approve requests',
            'generate reports',
            'view finance snapshot',
            'manage attendance',
        ]);

        // 3. Staff
        $staff = Role::firstOrCreate(['name' => 'Staff']);
        $staff->syncPermissions([
            'manage dashboard',
            'submit progress log',
            'manage attendance',
            'manage tasks', // their own tasks
        ]);

        // 4. Finance Manager
        $financeManager = Role::firstOrCreate(['name' => 'Finance Manager']);
        $financeManager->syncPermissions([
            'manage dashboard',
            'manage finances',
            'control budgets',
            'approve requests',
            'generate reports',
            'manage vendors',
            'manage payroll',
            'process payments',
            'view finance snapshot',
            'manage tasks',
            'manage milestones',
        ]);

        // 5. Accountant
        $accountant = Role::firstOrCreate(['name' => 'Accountant']);
        $accountant->syncPermissions([
            'manage dashboard',
            'manage finances',
            'control budgets',
            'generate reports',
            'manage vendors',
            'manage expenses',
            'process payments',
            'view finance snapshot',
            'manage tasks',
            'manage milestones',
        ]);

        // 6. Finance Support
        $financeSupport = Role::firstOrCreate(['name' => 'Finance Support']);
        $financeSupport->syncPermissions([
            'manage dashboard',
            'manage finances',
            'manage vendors',
            'view finance snapshot',
            'generate reports',
            'manage tasks',
            'manage milestones',
        ]);
    }
}
