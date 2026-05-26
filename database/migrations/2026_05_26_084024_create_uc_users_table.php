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
        Schema::create('uc_users', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('username')->unique();
            $blueprint->string('password');
            $blueprint->string('user_type')->default('COMPANIES'); // ADMIN or COMPANIES
            $blueprint->unsignedBigInteger('company_id')->nullable();
            $blueprint->timestamps();

            $blueprint->foreign('company_id')->references('id')->on('uc_companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_users');
    }
};
