<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foto_penginapan', function (Blueprint $table) {
            $table->id('id_foto');
            $table->unsignedBigInteger('id_unit');
            $table->string('foto', 255);
            $table->boolean('is_utama')->default(false);
            $table->timestamps();

            $table->foreign('id_unit')->references('id_unit')->on('penginapan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_penginapan');
    }
};
