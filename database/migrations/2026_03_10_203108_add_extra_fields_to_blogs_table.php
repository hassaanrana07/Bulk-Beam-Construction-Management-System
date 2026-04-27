<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('author_name')->nullable()->after('author_id');
            $table->string('author_role')->nullable()->after('author_name');
            $table->text('image_url')->nullable()->after('featured_image');
            $table->string('tags')->nullable()->after('content');
            $table->string('seo_title')->nullable()->after('tags');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('reading_time')->nullable()->after('seo_description');
            // 'excerpt' and 'is_featured' and 'published_at' already exist
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'author_name',
                'author_role',
                'image_url',
                'tags',
                'seo_title',
                'seo_description',
                'reading_time',
            ]);
        });
    }
};
