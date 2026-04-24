<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tag')->nullable();                          // e.g. "Creative", "Development"
            $table->foreignId('admin_id')                              // responsible admin
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
        });

        // Add section_id to users so each user can belong to a section
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('section_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('sections')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Section::class);
            $table->dropColumn('section_id');
        });

        Schema::dropIfExists('sections');
    }
};