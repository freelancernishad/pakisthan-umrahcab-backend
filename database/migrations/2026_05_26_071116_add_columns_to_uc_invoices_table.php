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
            $table->string('period')->nullable()->after('date');
            $table->string('type')->nullable()->after('status');
            $table->text('remarks')->nullable()->after('type');
            $table->string('entered_by')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_invoices', function (Blueprint $table) {
            $table->dropColumn(['period', 'type', 'remarks', 'entered_by']);
        });
    }
};
