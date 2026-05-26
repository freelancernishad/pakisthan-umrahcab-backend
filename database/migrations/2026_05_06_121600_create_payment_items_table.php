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
        if (!Schema::hasTable('payment_items')) {
            Schema::create('payment_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained()->onDelete('cascade');
                
                $table->string('item_id')->nullable(); // Generic item ID (e.g. fee_id, product_id, service_id)
                $table->string('name')->nullable();    // Generic item name (e.g. Tuition Fee, Web Hosting)
                $table->string('type')->nullable();    // Generic item type
                $table->decimal('amount', 12, 2)->default(0);
                $table->integer('quantity')->default(1);
                
                $table->string('status')->default('pending'); // pending, Paid, failed
                
                $table->json('meta')->nullable();      // For project-specific data (e.g. month, year, variant)
                
                $table->date('date')->nullable();
                $table->time('time')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_items');
    }
};
