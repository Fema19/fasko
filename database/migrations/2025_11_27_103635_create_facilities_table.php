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
    Schema::create('facilities', function (Blueprint $table) {
        $table->id();

        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->string('location')->nullable();
        $table->enum('condition', ['baik', 'rusak', 'perawatan', 'hilang'])->default('baik');
        $table->text('description')->nullable();
        $table->string('photo')->nullable();

        // Tambahan agar satu fasilitas bisa dipakai banyak kelas
        $table->integer('capacity')->default(1);

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('facilities');
}


};
