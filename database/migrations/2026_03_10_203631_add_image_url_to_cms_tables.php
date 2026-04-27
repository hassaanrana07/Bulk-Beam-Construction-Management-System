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
        Schema::table('staff', function (Blueprint $table) {
            $table->string('photo_url')->nullable()->after('photo');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('image');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('image');
        });
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('photo_url');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
};
