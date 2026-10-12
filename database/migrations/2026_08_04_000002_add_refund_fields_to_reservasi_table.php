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
            $table->integer('persentase_refund')->nullable()->after('status');
            $table->decimal('jumlah_refund', 12, 2)->default(0)->after('persentase_refund');
            $table->text('alasan_pembatalan')->nullable()->after('jumlah_refund');
            $table->string('bank_refund', 50)->nullable()->after('alasan_pembatalan');
            $table->string('rekening_refund', 50)->nullable()->after('bank_refund');
            $table->string('nama_rekening_refund', 100)->nullable()->after('rekening_refund');
            $table->enum('status_refund', ['none', 'pending_refund', 'refunded', 'no_refund'])->default('none')->after('nama_rekening_refund');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn([
                'persentase_refund',
                'jumlah_refund',
                'alasan_pembatalan',
                'bank_refund',
                'rekening_refund',
                'nama_rekening_refund',
                'status_refund',
            ]);
        });
    }
};
