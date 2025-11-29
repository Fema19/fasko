<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role jika belum ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin','guru','siswa'])->default('siswa')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','role')) {
                $table->dropColumn('role');
            }
        });
    }
};
