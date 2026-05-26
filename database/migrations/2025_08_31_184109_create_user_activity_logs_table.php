<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('activity');        // e.g., "Login", "Profile Update"
            $table->string('category')->nullable(); // e.g., "Authentication", "Profile", "Transaction"
            $table->string('ip')->nullable();
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->text('details')->nullable(); // JSON for extra info
            $table->boolean('is_success')->default(true); // success/failure
            $table->timestamps();
            
            // Index for querying logs
            $table->index(['user_id', 'activity', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
