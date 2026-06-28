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
        Schema::table('uc_price_lists', function (Blueprint $table) {
            $table->json('custom_prices')->nullable()->after('coach_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_price_lists', function (Blueprint $table) {
            $table->dropColumn('custom_prices');
        });
    }
};
