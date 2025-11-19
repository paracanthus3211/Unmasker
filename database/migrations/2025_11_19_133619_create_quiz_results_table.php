<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->constrained('quiz_levels')->onDelete('cascade');
            $table->integer('score');
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->integer('time_spent')->default(0);
            $table->timestamp('completed_at');
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['user_id', 'level_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_results');
    }
};