<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('user_quiz_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('quiz_level_id')->constrained()->onDelete('cascade');
        $table->integer('score')->default(0);
        $table->integer('correct_answers')->default(0); // PASTIKAN INI ADA
        $table->integer('wrong_answers')->default(0); // DAN INI
        $table->integer('completion_time')->default(0); // DAN INI
        $table->timestamp('completed_at');
        $table->timestamps();

        $table->unique(['user_id', 'quiz_level_id']);
    });
    }

    public function down()
    {
        Schema::dropIfExists('user_quiz_progress');
    }
};