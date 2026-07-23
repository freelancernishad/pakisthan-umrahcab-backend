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
            $table->unsignedBigInteger('driver_id')->nullable()->after('customer_id');
            if (Schema::hasTable('uc_drivers')) {
                $table->foreign('driver_id')->references('id')->on('uc_drivers')->onDelete('set null');
            }
        });

        Schema::table('uc_trains', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('customer_id');
            if (Schema::hasTable('uc_drivers')) {
                $table->foreign('driver_id')->references('id')->on('uc_drivers')->onDelete('set null');
            }
        });

        Schema::table('uc_hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('customer_id');
            if (Schema::hasTable('uc_drivers')) {
                $table->foreign('driver_id')->references('id')->on('uc_drivers')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_flights', function (Blueprint $table) {
            // Drop foreign key if driver_id has a foreign key constraint
            if (Schema::hasTable('uc_drivers')) {
                $table->dropForeign(['driver_id']);
            }
            $table->dropColumn('driver_id');
        });

        Schema::table('uc_trains', function (Blueprint $table) {
            if (Schema::hasTable('uc_drivers')) {
                $table->dropForeign(['driver_id']);
            }
            $table->dropColumn('driver_id');
        });

        Schema::table('uc_hotels', function (Blueprint $table) {
            if (Schema::hasTable('uc_drivers')) {
                $table->dropForeign(['driver_id']);
            }
            $table->dropColumn('driver_id');
        });
    }
};
