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
        if (!Schema::hasColumn('uc_bookings', 'visa_type')) {
            Schema::table('uc_bookings', function (Blueprint $table) {
                $table->string('visa_type')->default('Umrah Visa')->nullable()->after('notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('uc_bookings', 'visa_type')) {
            Schema::table('uc_bookings', function (Blueprint $table) {
                $table->dropColumn('visa_type');
            });
        }
    }
};
