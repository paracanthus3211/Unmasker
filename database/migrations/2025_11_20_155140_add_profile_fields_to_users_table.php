<?php
// database/migrations/2025_11_20_xxxxxx_add_profile_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambahkan field role jika belum ada
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('is_active');
            });
        }

        // Tambahkan field avatar_id jika belum ada
        if (!Schema::hasColumn('users', 'avatar_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('avatar_id')->nullable()->after('role')->constrained('avatars')->onDelete('set null');
            });
        }

        // Tambahkan field birth_date jika belum ada
        if (!Schema::hasColumn('users', 'birth_date')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('birth_date')->nullable()->after('avatar_id');
            });
        }

        // Tambahkan field phone jika belum ada
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('birth_date');
            });
        }

        // Tambahkan field phone_verified_at jika belum ada
        if (!Schema::hasColumn('users', 'phone_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('phone_verified_at')->nullable()->after('phone');
            });
        }
    }

    public function down()
    {
        // Hapus field jika rollback (opsional)
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'avatar_id', 
                'birth_date', 
                'phone', 
                'phone_verified_at'
            ]);
        });
    }
};