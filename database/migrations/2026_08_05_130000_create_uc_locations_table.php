<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('uc_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type')->default('both'); // 'pickup', 'dropoff', or 'both'
            $table->timestamps();
        });

        $fallbackLocations = [
            "Jeddah Airport (JED) - Terminal 1",
            "Jeddah Airport (JED) - North Terminal",
            "Makkah Hotel",
            "Madinah Hotel",
            "Jeddah Hotel",
            "Makkah Haram",
            "Madinah Haram",
            "Makkah Station (Haramain)",
            "Madinah Station (Haramain)",
            "Jeddah Station (Haramain)",
            "Taif",
            "Yanbu"
        ];

        foreach ($fallbackLocations as $loc) {
            DB::table('uc_locations')->insertOrIgnore([
                'name' => $loc,
                'type' => 'both',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_locations');
    }
};
