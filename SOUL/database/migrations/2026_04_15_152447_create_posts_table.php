<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Who posted it
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Which section this post belongs to
            $table->foreignId('section_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Content type: text | image | video | audio
            $table->enum('type', ['text', 'image', 'video', 'audio']);

            // Text body (required for text posts, optional caption for media posts)
            $table->text('body')->nullable();

            // Media file path (stored via Storage)
            $table->string('media_path')->nullable();

            // Original filename
            $table->string('media_filename')->nullable();

            // MIME type e.g. image/jpeg, video/mp4, audio/mpeg
            $table->string('media_mime')->nullable();

            // File size in bytes
            $table->unsignedBigInteger('media_size')->nullable();

            // Duration in seconds (for video/audio)
            $table->unsignedInteger('media_duration')->nullable();

            // Thumbnail path (auto-generated for video)
            $table->string('thumbnail_path')->nullable();

            // Moderation status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Who deleted/rejected it and when
            $table->foreignId('deleted_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('deleted_at_by_admin')->nullable();

            $table->timestamps();
            $table->softDeletes(); // soft delete so deleted posts are recoverable
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};