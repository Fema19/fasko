<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'room_id')) {
                $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','room_id')) {
                $table->dropForeign(['room_id']);
                $table->dropColumn('room_id');
            }
        });
    }
};
