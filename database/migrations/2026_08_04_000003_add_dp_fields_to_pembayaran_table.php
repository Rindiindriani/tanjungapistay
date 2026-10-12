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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->enum('jenis_pembayaran', ['full', 'dp'])->default('full')->after('metode_pembayaran');
            $table->decimal('nominal_dp', 12, 2)->default(0)->after('jenis_pembayaran');
            $table->decimal('sisa_pembayaran', 12, 2)->default(0)->after('nominal_dp');
            $table->enum('status_pelunasan', ['lunas', 'belum_lunas'])->default('lunas')->after('sisa_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_pembayaran',
                'nominal_dp',
                'sisa_pembayaran',
                'status_pelunasan',
            ]);
        });
    }
};
