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
        Schema::create('uc_driver_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('uc_drivers')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('uc_fleet')->onDelete('set null');
            $table->date('date');
            $table->string('trip')->nullable();
            $table->string('hotel_drop_off')->nullable();
            $table->string('agent')->nullable();
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('voucher', 10, 2)->default(0);
            $table->decimal('cash', 10, 2)->default(0);
            $table->decimal('fuel', 10, 2)->default(0);
            $table->decimal('parking', 10, 2)->default(0);
            $table->decimal('wash', 10, 2)->default(0);
            $table->decimal('oil_change', 10, 2)->default(0);
            $table->decimal('car_maintenance', 10, 2)->default(0);
            $table->decimal('waqas_received', 10, 2)->default(0);
            $table->decimal('mic', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('is_locked')->default(true); // By default, locked once submitted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_driver_entries');
    }
};
