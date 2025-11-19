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
        Schema::create('user_quiz_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('quiz_level_id')->constrained()->onDelete('cascade');
        $table->integer('score');
        $table->integer('correct_answers');
        $table->integer('wrong_answers');
        $table->integer('completion_time'); // dalam detik
        $table->timestamp('completed_at');
        $table->timestamps();
        
        $table->unique(['user_id', 'quiz_level_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_quiz_progress');
    }
};
