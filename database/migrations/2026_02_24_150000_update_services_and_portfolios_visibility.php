<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('status');
            $table->text('structure_description')->nullable()->after('description');
            $table->string('timeline_summary')->nullable()->after('structure_description');
            $table->json('phases_details')->nullable()->after('timeline_summary');
            $table->string('technical_layout_image')->nullable()->after('phases_details');
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('status');
        });

        Schema::table('cost_estimator_rules', function (Blueprint $table) {
            $table->json('complexity_multipliers')->nullable()->after('sector_multipliers');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['is_public', 'structure_description', 'timeline_summary', 'phases_details', 'technical_layout_image']);
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['is_public']);
        });

        Schema::table('cost_estimator_rules', function (Blueprint $table) {
            $table->dropColumn(['complexity_multipliers']);
        });
    }
};
