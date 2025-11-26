<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Tambahkan slug untuk SEO
            $table->string('image');
            $table->string('excerpt', 200);
            $table->text('content');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_published')->default(true); // Status publish
            $table->integer('views_count')->default(0); // Counter views
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['is_published', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};