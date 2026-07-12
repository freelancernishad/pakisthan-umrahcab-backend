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
            $table->unsignedBigInteger('driver_id')->nullable()->after('customer_id');
            
            // If uc_drivers table exists, set foreign key constraint
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
        Schema::table('uc_bookings', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
};
