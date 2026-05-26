<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('stripe_logs')) {
            Schema::create('stripe_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('type')->nullable();
                $table->string('stripe_customer_id')->nullable();
                $table->string('session_id')->nullable();
                $table->string('subscription_id')->nullable();
                $table->string('payment_intent_id')->nullable();
                $table->decimal('amount', 12, 2)->nullable();
                $table->string('currency', 10)->nullable();
                $table->string('status')->nullable();
                $table->json('payload')->nullable();
                $table->json('meta_data')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_logs');
    }
};
