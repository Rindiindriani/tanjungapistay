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
        Schema::table('penginapan', function (Blueprint $table) {
            // Mengubah tipe kolom tipe_unit menjadi string agar dapat diisi tipe unit apa saja secara dinamis
            $table->string('tipe_unit', 50)->default('villa')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penginapan', function (Blueprint $table) {
            $table->enum('tipe_unit', ['villa', 'tenda'])->default('villa')->change();
        });
    }
};
