<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Capability Scope
            if (!Schema::hasColumn('services', 'capability_features')) {
                $table->json('capability_features')->nullable()->after('technical_layout_image');
            }
            if (!Schema::hasColumn('services', 'capability_deliverables')) {
                $table->json('capability_deliverables')->nullable()->after('capability_features');
            }
            if (!Schema::hasColumn('services', 'capability_exclusions')) {
                $table->json('capability_exclusions')->nullable()->after('capability_deliverables');
            }
            if (!Schema::hasColumn('services', 'capability_tools')) {
                $table->json('capability_tools')->nullable()->after('capability_exclusions');
            }

            if (Schema::hasColumn('services', 'capability_functional_scope')) {
                $table->renameColumn('capability_functional_scope', 'capability_scope_description');
            } elseif (!Schema::hasColumn('services', 'capability_scope_description')) {
                $table->text('capability_scope_description')->nullable()->after('capability_tools');
            }

            // Product Structure Analysis
            if (!Schema::hasColumn('services', 'structural_type')) {
                $table->string('structural_type')->nullable()->after('capability_scope_description');
            }
            if (!Schema::hasColumn('services', 'technical_breakdown')) {
                $table->text('technical_breakdown')->nullable()->after('structural_type');
            }
            if (!Schema::hasColumn('services', 'materials_used')) {
                $table->json('materials_used')->nullable()->after('technical_breakdown');
            }

            if (Schema::hasColumn('services', 'architecture_layout_image')) {
                $table->renameColumn('architecture_layout_image', 'architecture_layout');
            } elseif (!Schema::hasColumn('services', 'architecture_layout')) {
                $table->json('architecture_layout')->nullable()->after('materials_used');
            }
        });

        // Ensure proper types for renamed columns
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'architecture_layout')) {
                $table->text('architecture_layout')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $cols = [
                'capability_features',
                'capability_deliverables',
                'capability_exclusions',
                'capability_tools',
                'capability_scope_description',
                'structural_type',
                'technical_breakdown',
                'materials_used',
                'architecture_layout'
            ];

            foreach ($cols as $col) {
                if (Schema::hasColumn('services', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
