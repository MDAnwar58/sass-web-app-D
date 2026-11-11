<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('lms_sections')->cascadeOnDelete();
            $table->string('title');
            $table->enum('type', ['video', 'pdf', 'text', 'quiz'])->default('video');
            $table->text('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->boolean('is_free_preview')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_lessons');
    }
};
