<?php
// database/migrations/2025_11_20_xxxxxx_create_avatars_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('avatars')) {
            Schema::create('avatars', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('image_url');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('avatars');
    }
};