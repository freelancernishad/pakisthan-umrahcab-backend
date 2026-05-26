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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // original name
            $table->string('original_url'); // original image URL
            
            // Polymorphic uploader
            $table->unsignedBigInteger('uploader_id')->nullable();
            $table->string('uploader_type')->nullable();
            
            // Keeping specific foreign keys as nullable for backward compatibility if needed, 
            // or we can strictly use the polymorphic ones. 
            // Based on analysis, we have specific fields too.
            $table->unsignedBigInteger('uploaded_by_user_id')->nullable();
            $table->unsignedBigInteger('uploaded_by_admin_id')->nullable();
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('uploaded_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('uploaded_by_admin_id')->references('id')->on('admins')->onDelete('set null');
        });

        Schema::create('media_file_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_file_id');
            $table->string('label'); // original, thumbnail, 300x300
            $table->string('url');
            $table->bigInteger('size'); // bytes
            $table->string('size_type'); // "1.2 MB", "450 KB"
            $table->string('type'); // mime type
            $table->string('dimensions'); // "300x300"
            $table->timestamps();

            $table->foreign('media_file_id')->references('id')->on('media_files')->onDelete('cascade');
            
            // Performance index
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_file_versions');
        Schema::dropIfExists('media_files');
    }
};
