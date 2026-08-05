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
        Schema::table('uc_driver_entries', function (Blueprint $table) {
            $table->string('manual_vehicle')->nullable()->after('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_driver_entries', function (Blueprint $table) {
            $table->dropColumn('manual_vehicle');
        });
    }
};
