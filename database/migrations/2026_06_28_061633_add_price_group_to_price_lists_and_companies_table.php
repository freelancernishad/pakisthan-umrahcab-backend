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
            $table->string('group_name')->default('Standard')->after('route')->index();
        });

        Schema::table('uc_companies', function (Blueprint $table) {
            $table->string('price_group')->default('Standard')->after('name')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_price_lists', function (Blueprint $table) {
            $table->dropColumn('group_name');
        });

        Schema::table('uc_companies', function (Blueprint $table) {
            $table->dropColumn('price_group');
        });
    }
};
