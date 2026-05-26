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
        Schema::table('uc_companies', function (Blueprint $table) {
            $table->string('statement_status')->default('Pending')->after('reminders');
            $table->text('remarks')->nullable()->after('statement_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_companies', function (Blueprint $table) {
            $table->dropColumn(['statement_status', 'remarks']);
        });
    }
};
