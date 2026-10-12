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
        Schema::create('penginapan', function (Blueprint $table) {
            $table->id('id_unit');
            $table->unsignedBigInteger('id_pengelola')->nullable();
            $table->string('nama_unit', 30);
            $table->string('alamat', 100);
            $table->string('fasilitas', 300);
            $table->decimal('harga', 12, 2);
            $table->enum('tipe_unit', ['villa', 'tenda']);
            $table->string('foto', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_pengelola')->references('id_pengelola')->on('pengelola')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penginapan');
    }
};
