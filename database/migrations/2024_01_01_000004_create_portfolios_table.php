<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('client_name')->nullable();
            $table->string('location')->nullable();
            $table->string('project_type'); // Residential, Commercial, etc.
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('budget_range')->nullable(); // e.g., "$100k - $500k"
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable(); // Before/After images
            $table->json('before_after')->nullable(); // Specific before/after pairs
            $table->json('categories')->nullable(); // Array of category IDs or names
            $table->text('client_testimonial')->nullable();
            $table->string('client_position')->nullable();
            $table->json('project_details')->nullable(); // Square footage, materials, etc.
            $table->integer('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
