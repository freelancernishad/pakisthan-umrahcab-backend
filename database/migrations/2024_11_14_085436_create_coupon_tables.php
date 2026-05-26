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
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique(); // Coupon code, e.g., "SUMMER2024"
                $table->string('type')->default('percentage'); // Type: percentage or fixed
                $table->decimal('value', 8, 2); // Coupon value, e.g., 10% or 20$
                $table->dateTime('valid_from')->nullable(); // Coupon validity start date
                $table->dateTime('valid_until')->nullable(); // Coupon validity end date
                $table->boolean('is_active')->default(true); // Is coupon active
                $table->integer('usage_limit')->nullable(); // Maximum uses
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_associations')) {
            Schema::create('coupon_associations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('coupon_id');
                $table->unsignedBigInteger('item_id'); // This will store the ID of the user, package, or service
                $table->enum('item_type', ['user', 'package', 'service', 'plan']); // The type of item
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('coupon_usages')) {
            Schema::create('coupon_usages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained(); // Track which user used the coupon
                $table->timestamp('used_at')->useCurrent(); // Date and time when the coupon was used
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupon_associations');
        Schema::dropIfExists('coupons');
    }
};
