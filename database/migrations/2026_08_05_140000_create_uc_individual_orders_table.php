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
        Schema::create('uc_individual_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('pickup');
            $table->string('destination');
            $table->date('date');
            $table->string('time');
            $table->string('passengers');
            $table->string('car_type');
            $table->decimal('car_price', 10, 2);
            $table->string('full_name');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('flight_no')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('Pending'); // e.g. Pending, Completed, Cancelled
            $table->string('payment_status')->default('Pending'); // e.g. Pending, Paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_individual_orders');
    }
};
