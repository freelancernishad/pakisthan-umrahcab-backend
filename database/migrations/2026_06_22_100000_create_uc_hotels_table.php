<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uc_hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->integer('active')->default(1);
            $table->timestamps();
        });

        // Seed default hotels
        DB::table('uc_hotels')->insert([
            ['name' => 'Makkah Clock Royal Tower (Fairmont)', 'city' => 'Makkah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pullman ZamZam Makkah', 'city' => 'Makkah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Swissôtel Makkah', 'city' => 'Makkah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hilton Suites Makkah', 'city' => 'Makkah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Anjum Hotel Makkah', 'city' => 'Makkah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oberoi Madinah', 'city' => 'Madinah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Madinah Hilton', 'city' => 'Madinah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Anwar Al Madinah Mövenpick', 'city' => 'Madinah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pullman Zamzam Madinah', 'city' => 'Madinah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dar Al Taqwa Hotel Madinah', 'city' => 'Madinah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jeddah Hilton', 'city' => 'Jeddah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'InterContinental Jeddah', 'city' => 'Jeddah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sheraton Jeddah Hotel', 'city' => 'Jeddah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rosewood Jeddah', 'city' => 'Jeddah', 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('uc_hotels');
    }
};

