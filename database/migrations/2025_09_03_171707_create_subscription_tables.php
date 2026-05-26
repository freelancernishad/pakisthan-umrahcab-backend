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
        Schema::create('plan_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');

            $table->string('stripe_subscription_id')->nullable()->index(); // for recurring
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('next_billing_date')->nullable(); // for recurring

            $table->decimal('original_amount', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);

            $table->json('plan_features')->nullable();

            $table->string('status')->default('pending'); // pending, active, cancelled, expired
            $table->string('billing_interval')->nullable(); // monthly, yearly, etc.
            $table->integer('billing_cycles_completed')->default(0); // for recurring

            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payable_type', 191);
            $table->unsignedBigInteger('payable_id');
            $table->index(['payable_type', 'payable_id']);
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('payment_method')->nullable(); // stripe, paypal, card, etc.
            $table->string('transaction_id')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('status')->default('pending'); // pending, succeeded, failed
            $table->timestamp('webhook_received_at')->nullable();
            $table->string('webhook_status')->nullable();
            $table->text('webhook_signature')->nullable();
            $table->json('meta')->nullable(); // Stripe full payload or gateway response
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('plan_subscriptions');
    }
};
