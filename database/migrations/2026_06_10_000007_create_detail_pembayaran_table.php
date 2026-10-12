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
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pembayaran');
            $table->unsignedBigInteger('id_gateway')->nullable();
            $table->string('jenis_pembayaran', 15); // transfer, e-wallet
            $table->decimal('jumlah_pembayaran', 12, 2);
            $table->date('tanggal_detail');
            $table->timestamps();

            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran')->onDelete('cascade');
            $table->foreign('id_gateway')->references('id_gateway')->on('payment_gateway')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayaran');
    }
};
