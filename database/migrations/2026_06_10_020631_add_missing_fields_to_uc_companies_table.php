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
            $table->string('agent_username')->nullable()->after('name');
            $table->string('agent_password')->nullable()->after('agent_username');
            $table->string('logo_path')->nullable()->after('website');
            $table->string('ledger_frequency')->default('Monday')->after('reminders');
            $table->boolean('tomorrow_reminder')->default(false)->after('ledger_frequency');
            $table->boolean('exempt_bulk_lock')->default(false)->after('tomorrow_reminder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uc_companies', function (Blueprint $table) {
            $table->dropColumn([
                'agent_username',
                'agent_password',
                'logo_path',
                'ledger_frequency',
                'tomorrow_reminder',
                'exempt_bulk_lock',
            ]);
        });
    }
};
