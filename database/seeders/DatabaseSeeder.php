<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles and Permissions
        $this->call(RolePermissionSeeder::class);

        // Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@brickbeam.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole('Super Admin');

        // Admin Manager
        $adminManager = User::updateOrCreate(
            ['email' => 'manager@brickbeam.com'],
            [
                'name' => 'Admin Manager',
                'password' => Hash::make('password'),
            ]
        );
        $adminManager->assignRole('Manager');

        // Editor
        $editor = User::updateOrCreate(
            ['email' => 'editor@brickbeam.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('password'),
            ]
        );
        $editor->assignRole('Staff');

        // Support
        $support = User::updateOrCreate(
            ['email' => 'support@brickbeam.com'],
            [
                'name' => 'Support',
                'password' => Hash::make('password'),
            ]
        );
        $support->assignRole('Staff');

        // Financial Support
        $financialSupport = User::updateOrCreate(
            ['email' => 'finance@brickbeam.com'],
            [
                'name' => 'Financial Support',
                'password' => Hash::make('password'),
            ]
        );
        $financialSupport->assignRole('Finance Support');

        // Seed Global Settings
        $this->call(GlobalSettingSeeder::class);

        $this->call([
            PageSeeder::class,
            ServiceSeeder::class,
            PortfolioSeeder::class,
            BlogSeeder::class,
            TestimonialSeeder::class,
            LocationSeeder::class,
            CostEstimatorSeeder::class,
            StaffSeeder::class,
            LeadSeeder::class,
        ]);
    }
}
