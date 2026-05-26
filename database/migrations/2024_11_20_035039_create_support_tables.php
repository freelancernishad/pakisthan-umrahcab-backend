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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['open', 'closed', 'pending', 'replay'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        // Old replies table (keeping for backward compatibility)
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('reply');
            $table->foreignId('reply_id')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        // Add self-referencing foreign key for replies
        Schema::table('replies', function (Blueprint $table) {
            $table->foreign('reply_id')->references('id')->on('replies')->onDelete('cascade');
        });

        // New support ticket replies table
        Schema::create('support_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade');
            $table->text('reply');
            $table->foreignId('reply_id')->nullable()->constrained('support_ticket_replies')->onDelete('cascade');
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_replies');
        Schema::dropIfExists('replies');
        Schema::dropIfExists('support_tickets');
    }
};
