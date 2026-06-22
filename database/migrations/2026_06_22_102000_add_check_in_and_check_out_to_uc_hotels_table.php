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
        Schema::table('uc_hotels', function (Blueprint $table) {
            $table->string('check_in')->nullable()->after('city');
            $table->string('check_out')->nullable()->after('check_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_hotels', function (Blueprint $table) {
            $table->dropColumn(['check_in', 'check_out']);
        });
    }
};
