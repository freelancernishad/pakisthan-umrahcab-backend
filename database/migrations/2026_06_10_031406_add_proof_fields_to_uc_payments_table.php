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
        Schema::table('uc_payments', function (Blueprint $table) {
            $table->string('transaction_ref')->nullable()->after('currency');
            $table->text('proof_details')->nullable()->after('transaction_ref');
            $table->string('proof_file')->nullable()->after('proof_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_payments', function (Blueprint $table) {
            $table->dropColumn(['transaction_ref', 'proof_details', 'proof_file']);
        });
    }
};
