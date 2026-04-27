<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add is_public_visible to all main content tables
        $tables = ['services', 'portfolios', 'blogs', 'certifications', 'testimonials', 'faqs', 'staff'];
        foreach ($tables as $tName) {
            if (Schema::hasTable($tName) && !Schema::hasColumn($tName, 'is_public_visible')) {
                Schema::table($tName, function (Blueprint $table) use ($tName) {
                    $column = $table->boolean('is_public_visible')->default(true);

                    if (Schema::hasColumn($tName, 'status')) {
                        $column->after('status');
                    } elseif (Schema::hasColumn($tName, 'is_active')) {
                        $column->after('is_active');
                    }
                });
            }
        }

        // Add Portfolio Structure Analysis fields
        Schema::table('portfolios', function (Blueprint $table) {
            $table->json('structural_features')->nullable()->after('is_public_visible');
            $table->string('base_structure')->nullable()->after('structural_features');
            $table->string('foundation_type')->nullable()->after('base_structure');
            $table->integer('total_floors')->nullable()->after('foundation_type');
            $table->string('floor_composition')->nullable()->after('total_floors');
            $table->json('capabilities')->nullable()->after('floor_composition');
            $table->json('functional_features')->nullable()->after('capabilities');
            $table->string('technology_used')->nullable()->after('functional_features');
            $table->string('construction_technology')->nullable()->after('technology_used');
            $table->json('tools_used')->nullable()->after('construction_technology');
            $table->string('framework_type')->nullable()->after('tools_used');
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn([
                'structural_features',
                'base_structure',
                'foundation_type',
                'total_floors',
                'floor_composition',
                'capabilities',
                'functional_features',
                'technology_used',
                'construction_technology',
                'tools_used',
                'framework_type'
            ]);
        });

        $tables = ['services', 'portfolios', 'blogs', 'certifications', 'testimonials', 'faqs'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'is_public_visible')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('is_public_visible');
                });
            }
        }
    }
};
