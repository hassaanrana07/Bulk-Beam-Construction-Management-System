<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ─── Case Study fields on portfolios ──────────────────────────────────
        Schema::table('portfolios', function (Blueprint $table) {
            // Already exists: project_type, execution_status, start_date, completion_date
            $table->string('case_study_category')->nullable()->after('project_type');
            $table->string('case_study_scope')->nullable()->after('case_study_category');
            $table->string('case_study_sector')->nullable()->after('case_study_scope');
            $table->string('cs_phase_1')->nullable()->after('case_study_sector');
            $table->string('cs_phase_2')->nullable()->after('cs_phase_1');
            $table->string('cs_phase_3')->nullable()->after('cs_phase_2');
            $table->string('cs_phase_4')->nullable()->after('cs_phase_3');
            $table->string('cs_phase_5')->nullable()->after('cs_phase_4');
            $table->integer('cs_duration_weeks')->nullable()->after('cs_phase_5');
            $table->string('cs_team')->nullable()->after('cs_duration_weeks');
            $table->string('cs_total_value')->nullable()->after('cs_team');
        });

        // ─── Operations / Vacations fields on services ─────────────────────
        Schema::table('services', function (Blueprint $table) {
            $table->text('operations_description')->nullable()->after('description');
            $table->json('operations_bullets')->nullable()->after('operations_description');
            $table->string('operations_timeline')->nullable()->after('operations_bullets');
            $table->string('operations_team')->nullable()->after('operations_timeline');
            $table->text('vacations_description')->nullable()->after('operations_team');
            $table->json('vacations_bullets')->nullable()->after('vacations_description');
            $table->string('vacations_timeline')->nullable()->after('vacations_bullets');
        });

        // ─── Enhanced formula fields on cost_estimator_rules ───────────────
        Schema::table('cost_estimator_rules', function (Blueprint $table) {
            $table->decimal('material_cost_factor', 5, 2)->default(1.0)->after('base_rate_per_sqft');
            $table->decimal('labor_cost_factor', 5, 2)->default(1.0)->after('material_cost_factor');
            $table->json('sector_multipliers')->nullable()->after('labor_cost_factor'); // { residential: 1.0, commercial: 1.3, industrial: 1.6 }
            $table->integer('timeline_weeks_per_1000sqft')->default(8)->after('sector_multipliers');
            $table->string('sector')->nullable()->after('category'); // default sector for this rule
            $table->string('scope_level')->nullable()->after('sector'); // standard|premium|luxury
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn([
                'case_study_category',
                'case_study_scope',
                'case_study_sector',
                'cs_phase_1',
                'cs_phase_2',
                'cs_phase_3',
                'cs_phase_4',
                'cs_phase_5',
                'cs_duration_weeks',
                'cs_team',
                'cs_total_value',
            ]);
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'operations_description',
                'operations_bullets',
                'operations_timeline',
                'operations_team',
                'vacations_description',
                'vacations_bullets',
                'vacations_timeline',
            ]);
        });
        Schema::table('cost_estimator_rules', function (Blueprint $table) {
            $table->dropColumn([
                'material_cost_factor',
                'labor_cost_factor',
                'sector_multipliers',
                'timeline_weeks_per_1000sqft',
                'sector',
                'scope_level',
            ]);
        });
    }
};
