<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable(); // Icon class or image path
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable(); // Array of image paths
            $table->json('benefits')->nullable(); // Array of benefits
            $table->json('process_steps')->nullable(); // Array of process steps
            $table->json('faq')->nullable(); // Array of FAQ items
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
        Schema::dropIfExists('services');
    }
};
