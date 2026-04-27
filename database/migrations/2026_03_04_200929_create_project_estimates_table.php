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
        Schema::create('project_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_number')->unique();
            $table->date('estimate_date');

            // Section Totals (Denormalized for performance)
            $table->decimal('material_cost', 15, 2)->default(0);
            $table->decimal('labor_cost', 15, 2)->default(0);
            $table->decimal('equipment_cost', 15, 2)->default(0);
            $table->decimal('other_cost', 15, 2)->default(0);
            $table->json('other_costs_details')->nullable(); // misc, transport, permits

            // Financial Summary
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('contingency_percent', 5, 2)->default(0);
            $table->decimal('profit_percent', 5, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->string('status')->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_estimates');
    }
};
