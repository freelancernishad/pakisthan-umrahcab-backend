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
        Schema::table('uc_bookings', function (Blueprint $table) {
            $table->boolean('reminder1_sent')->default(false)->after('driver_trip_status');
            $table->boolean('reminder2_sent')->default(false)->after('reminder1_sent');
            $table->boolean('reminder3_sent')->default(false)->after('reminder2_sent');
        });

        Schema::table('uc_services', function (Blueprint $table) {
            $table->boolean('reminder1_sent')->default(false)->after('time');
            $table->boolean('reminder2_sent')->default(false)->after('reminder1_sent');
            $table->boolean('reminder3_sent')->default(false)->after('reminder2_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_bookings', function (Blueprint $table) {
            $table->dropColumn(['reminder1_sent', 'reminder2_sent', 'reminder3_sent']);
        });

        Schema::table('uc_services', function (Blueprint $table) {
            $table->dropColumn(['reminder1_sent', 'reminder2_sent', 'reminder3_sent']);
        });
    }
};
