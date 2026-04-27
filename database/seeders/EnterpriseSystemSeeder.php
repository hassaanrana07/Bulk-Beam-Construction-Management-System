<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Task;
use App\Models\Milestone;
use App\Models\Vendor;
use App\Models\Expense;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EnterpriseSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users for each Role
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@brickbeam.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        $superAdmin->assignRole('Super Admin');

        $manager = User::updateOrCreate(
            ['email' => 'manager@brickbeam.com'],
            ['name' => 'Project Manager John', 'password' => Hash::make('password')]
        );
        $manager->assignRole('Manager');

        $staff = User::updateOrCreate(
            ['email' => 'staff@brickbeam.com'],
            ['name' => 'Field Staff Mike', 'password' => Hash::make('password')]
        );
        $staff->assignRole('Staff');

        $financeManager = User::updateOrCreate(
            ['email' => 'finance.manager@brickbeam.com'],
            ['name' => 'Finance Manager Sarah', 'password' => Hash::make('password')]
        );
        $financeManager->assignRole('Finance Manager');

        $financeSupport = User::updateOrCreate(
            ['email' => 'finance.support@brickbeam.com'],
            ['name' => 'Finance Support Alex', 'password' => Hash::make('password')]
        );
        $financeSupport->assignRole('Finance Support');

        // 2. Assign Manager to some Projects
        $projects = Portfolio::take(5)->get();
        foreach ($projects as $project) {
            $project->update(['manager_id' => $manager->id]);

            // 3. Add Milestones
            Milestone::create([
                'portfolio_id' => $project->id,
                'title' => 'Structural Foundation',
                'deadline' => now()->addWeeks(2),
                'status' => 'pending'
            ]);

            // 4. Add Tasks
            Task::create([
                'portfolio_id' => $project->id,
                'assigned_to' => $staff->id,
                'title' => 'Excavation Work',
                'priority' => 'high',
                'status' => 'in_progress',
                'deadline' => now()->addDays(5)
            ]);
        }

        // 5. Add Vendors and Expenses
        $vendor = Vendor::create([
            'name' => 'Industrial Materials Co.',
            'category' => 'Materials',
            'email' => 'sales@industrialmaterials.com'
        ]);

        if ($projects->first()) {
            Expense::create([
                'portfolio_id' => $projects->first()->id,
                'vendor_id' => $vendor->id,
                'amount' => 5000.00,
                'category' => 'materials',
                'status' => 'pending',
                'due_date' => now()->addMonth()
            ]);
        }
    }
}
