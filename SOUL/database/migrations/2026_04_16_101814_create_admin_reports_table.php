<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_reports', function (Blueprint $table) {
            $table->id();

            // The admin who submitted the report
            $table->foreignId('admin_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Category of the report
            $table->enum('category', ['security', 'user_conduct', 'technical', 'other']);

            // Full description
            $table->text('description');

            // Optional file attachment path
            $table->string('attachment_path')->nullable();
            $table->string('attachment_filename')->nullable();
            $table->string('attachment_mime')->nullable();

            // Status: pending (awaiting manager review) | reviewed | dismissed
            $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending');

            // Manager notes / response
            $table->text('manager_notes')->nullable();

            // When the manager reviewed it
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_reports');
    }
};