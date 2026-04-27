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
        Schema::create('estimate_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_estimate_id')->constrained()->cascadeOnDelete();
            $table->string('equipment_name');
            $table->decimal('hours', 15, 2)->default(0);
            $table->decimal('hourly_rate', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimate_equipment');
    }
};
