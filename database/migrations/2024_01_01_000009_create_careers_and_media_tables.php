<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('department');
            $table->string('location');
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'internship']);
            $table->string('experience_level'); // Entry, Mid, Senior
            $table->json('requirements')->nullable();
            $table->json('responsibilities')->nullable();
            $table->json('benefits')->nullable();
            $table->string('salary_range')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('closing_date')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('cover_letter')->nullable();
            $table->string('resume_path');
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->json('additional_info')->nullable();
            $table->enum('status', ['new', 'reviewing', 'shortlisted', 'interviewed', 'offered', 'rejected'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('media_library', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('mime_type');
            $table->unsignedBigInteger('size'); // in bytes
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('folder')->nullable();
            $table->json('tags')->nullable();
            $table->text('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['folder']);
            $table->index(['mime_type']);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url');
            $table->string('to_url');
            $table->integer('status_code')->default(301); // 301 or 302
            $table->integer('hits')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['from_url']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('media_library');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('careers');
    }
};
