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
        Schema::table('reservasi', function (Blueprint $table) {
            // Mengubah tipe enum status atau mendefinisikan ulang agar mendukung status 'postponed'
            $table->enum('status', ['pending', 'confirmed', 'postponed', 'cancelled', 'completed'])
                  ->default('pending')
                  ->change();

            $table->timestamp('tanggal_penundaan')->nullable()->after('alasan_pembatalan');
            $table->timestamp('batas_tunda_hingga')->nullable()->after('tanggal_penundaan');
            $table->text('alasan_penundaan')->nullable()->after('batas_tunda_hingga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_penundaan',
                'batas_tunda_hingga',
                'alasan_penundaan',
            ]);

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
                  ->default('pending')
                  ->change();
        });
    }
};
