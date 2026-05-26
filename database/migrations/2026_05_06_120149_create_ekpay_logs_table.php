<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('ekpay.table_names.logs', 'ekpay_logs');

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                
                $table->string('trnx_id')->unique()->index();
                $table->string('trns_date')->nullable();
                $table->decimal('amount', 12, 2);
                $table->string('status')->default('pending');
                
                $table->string('secure_token')->nullable();
                $table->string('pi_name')->nullable();
                $table->string('pi_type')->nullable();
                
                $table->json('request_payload')->nullable();
                $table->json('response_payload')->nullable();
                $table->json('ipn_payload')->nullable();
                $table->json('redirect_urls')->nullable();
                
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $tableName = config('ekpay.table_names.logs', 'ekpay_logs');
        Schema::dropIfExists($tableName);
    }
};
