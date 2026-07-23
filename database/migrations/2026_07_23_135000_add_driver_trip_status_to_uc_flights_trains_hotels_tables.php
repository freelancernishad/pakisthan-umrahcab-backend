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
        Schema::table('uc_flights', function (Blueprint $table) {
            $table->string('driver_trip_status')->nullable()->after('driver_id');
        });

        Schema::table('uc_trains', function (Blueprint $table) {
            $table->string('driver_trip_status')->nullable()->after('driver_id');
        });

        Schema::table('uc_hotels', function (Blueprint $table) {
            $table->string('driver_trip_status')->nullable()->after('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_flights', function (Blueprint $table) {
            $table->dropColumn('driver_trip_status');
        });

        Schema::table('uc_trains', function (Blueprint $table) {
            $table->dropColumn('driver_trip_status');
        });

        Schema::table('uc_hotels', function (Blueprint $table) {
            $table->dropColumn('driver_trip_status');
        });
    }
};
