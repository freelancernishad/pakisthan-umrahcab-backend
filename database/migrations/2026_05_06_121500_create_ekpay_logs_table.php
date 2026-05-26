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
        if (!Schema::hasTable('ekpay_logs')) {
            Schema::create('ekpay_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                
                $table->string('trnx_id')->unique()->index();
                $table->string('trns_date')->nullable();
                $table->decimal('amount', 12, 2);
                $table->string('status')->default('pending'); // pending, succeeded, failed
                
                $table->string('secure_token')->nullable();
                $table->string('pi_name')->nullable(); // Payment instrument name
                $table->string('pi_type')->nullable();
                
                $table->json('request_payload')->nullable();
                $table->json('response_payload')->nullable();
                $table->json('ipn_payload')->nullable();
                $table->json('redirect_urls')->nullable();
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekpay_logs');
    }
};
