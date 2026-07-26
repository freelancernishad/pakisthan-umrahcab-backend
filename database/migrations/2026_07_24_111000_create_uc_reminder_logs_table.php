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
        Schema::create('uc_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('type'); // BKG or SRV
            $table->integer('reminder_type'); // 1, 2, or 3
            $table->string('recipient')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_trip_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_reminder_logs');
    }
};
