<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cost_estimator_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Residential Construction"
            $table->string('category'); // e.g., "residential", "commercial"
            $table->decimal('base_rate_per_sqft', 10, 2); // Base cost per square foot
            $table->json('multipliers')->nullable(); // Additional cost factors
            $table->json('options')->nullable(); // Selectable options with costs
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('cost_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('project_type');
            $table->decimal('square_footage', 10, 2);
            $table->json('selected_options')->nullable();
            $table->decimal('estimated_cost', 12, 2);
            $table->json('calculation_breakdown')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('pdf_generated')->default(false);
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_estimates');
        Schema::dropIfExists('cost_estimator_rules');
    }
};
