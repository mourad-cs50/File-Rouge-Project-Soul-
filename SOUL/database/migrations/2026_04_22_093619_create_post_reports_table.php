<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_reports', function (Blueprint $table) {
            $table->id();

            // The post being reported
            $table->foreignId('post_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // The user who filed the report
            $table->foreignId('reported_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Report category
            $table->enum('reason', ['spam', 'harassment', 'inappropriate', 'false_information', 'other']);

            // Extra context from the reporter
            $table->text('details')->nullable();

            // Moderation outcome
            $table->enum('status', ['pending', 'kept', 'deleted'])->default('pending');

            // Which admin reviewed it and when
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // One user can report the same post only once
            $table->unique(['post_id', 'reported_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reports');
    }
};