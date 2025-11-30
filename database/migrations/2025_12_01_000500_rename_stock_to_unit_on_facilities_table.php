<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika kolom unit belum ada tapi stock ada, ubah nama kolom
        if (!Schema::hasColumn('facilities', 'unit') && Schema::hasColumn('facilities', 'stock')) {
            // Gunakan SQL langsung untuk menghindari ketergantungan DBAL
            DB::statement('ALTER TABLE facilities CHANGE stock unit INT NULL');
        } elseif (!Schema::hasColumn('facilities', 'unit')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->integer('unit')->nullable()->after('capacity');
            });
        }
    }

    public function down(): void
    {
        // Kembalikan ke kolom stock jika unit ada dan stock tidak ada
        if (Schema::hasColumn('facilities', 'unit') && !Schema::hasColumn('facilities', 'stock')) {
            DB::statement('ALTER TABLE facilities CHANGE unit stock INT NULL');
        }
    }
};
