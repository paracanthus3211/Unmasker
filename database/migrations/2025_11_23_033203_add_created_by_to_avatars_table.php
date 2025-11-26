<?php
// database/migrations/2025_11_23_xxxxxx_add_created_by_to_avatars_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('avatars', function (Blueprint $table) {
            if (!Schema::hasColumn('avatars', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('is_active')->constrained('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('avatars', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};