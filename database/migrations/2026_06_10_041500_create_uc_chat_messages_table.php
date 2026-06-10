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
        Schema::create('uc_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('uc_companies')->onDelete('cascade');
            $table->string('sender_type'); // 'company' or 'admin'
            $table->unsignedBigInteger('sender_id'); // company_id or admin_id
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('reply_to_id')->nullable()->constrained('uc_chat_messages')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_chat_messages');
    }
};
