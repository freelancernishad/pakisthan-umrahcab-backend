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
        Schema::table('uc_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('individual_order_id')->nullable()->after('customer_id');
            
            // Add foreign key constraint if preferred
            $table->foreign('individual_order_id')
                  ->references('id')
                  ->on('uc_individual_orders')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_invoices', function (Blueprint $table) {
            $table->dropForeign(['individual_order_id']);
            $table->dropColumn('individual_order_id');
        });
    }
};
