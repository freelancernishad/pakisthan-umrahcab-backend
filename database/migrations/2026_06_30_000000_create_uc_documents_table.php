<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uc_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('title')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('uc_customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uc_documents');
    }
};
