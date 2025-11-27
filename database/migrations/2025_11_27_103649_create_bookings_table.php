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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('facility_id')->constrained()->cascadeOnDelete();

        $table->timestamp('start_time')->nullable();
        $table->timestamp('end_time')->nullable();

        $table->text('reason')->nullable();

        $table->integer('capacity_used')->default(1);

        $table->enum('status', [
            'pending',
            'approved',
            'rejected',
            'active',
            'completed',
            'cancelled'
        ])->default('pending');

        $table->foreignId('approved_by')->nullable()->constrained('users');

        $table->timestamp('check_in_time')->nullable();
        $table->boolean('checked_in')->default(false);

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('bookings');
}

};
