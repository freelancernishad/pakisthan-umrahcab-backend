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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('duration');
            $table->decimal('original_price', 10, 2);
            $table->decimal('discounted_price', 10, 2);
            $table->decimal('monthly_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2);
            $table->json('features'); // To store features as JSON
            $table->timestamps();
        });

        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., view_contact, live_passes
            $table->string('title_template'); // e.g., "View upto :value Contact Numbers"
            $table->string('unit')->nullable(); // Optional unit like "contacts", "passes"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('plans');
    }
};
