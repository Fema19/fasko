<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('repair_reports', function (Blueprint $table) {
        $table->id();

        $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // opsional: laporan kerusakan terkait booking
        $table->foreignId('booking_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->text('description');
        $table->string('photo')->nullable();

        $table->enum('status', [
            'pending',
            'in_progress',
            'fixed'
        ])->default('pending');

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('repair_reports');
}

};
