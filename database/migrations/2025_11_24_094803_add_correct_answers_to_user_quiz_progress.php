<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('user_quiz_progress', 'correct_answers')) {
            Schema::table('user_quiz_progress', function (Blueprint $table) {
                $table->integer('correct_answers')->default(0)->after('score');
            });
        }
    }

    public function down()
    {
        Schema::table('user_quiz_progress', function (Blueprint $table) {
            $table->dropColumn('correct_answers');
        });
    }
};