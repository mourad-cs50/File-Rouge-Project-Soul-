<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->enum('type', ['admin', 'member'])->default('member');
            $table->string('subject');
            $table->text('body');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['new', 'pending', 'in_progress', 'resolved', 'closed'])->default('new');
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('complaint_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('filename');
            $table->string('path');
            $table->unsignedBigInteger('size')->nullable(); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_attachments');
        Schema::dropIfExists('complaints');
    }
};