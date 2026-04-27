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
        Schema::table('portfolios', function (Blueprint $table) {
            $table->decimal('total_budget', 15, 2)->default(0)->after('budget_range');
            $table->decimal('expected_revenue', 15, 2)->default(0)->after('total_budget');
            $table->decimal('received_payment', 15, 2)->default(0)->after('expected_revenue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['total_budget', 'expected_revenue', 'received_payment']);
        });
    }
};
