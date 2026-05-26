<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('username')->nullable()->unique();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');

                // OTP verification
                $table->string('otp')->nullable();
                $table->timestamp('otp_expires_at')->nullable();

                // Email verification hash for link
                $table->string('email_verification_hash')->nullable()->unique();

                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('allowed_origins')) {
            Schema::create('allowed_origins', function (Blueprint $table) {
                $table->id();
                $table->string('origin_url')->unique();
                $table->timestamps();
            });
        }

        // Insert default record for allowed_origins
        DB::table('allowed_origins')->insertOrIgnore([
            'origin_url' => 'postman',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowed_origins');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('admins');
    }
};
