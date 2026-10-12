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
        Schema::table('tamu', function (Blueprint $table) {
            $table->string('kewarganegaraan', 10)->default('WNI')->after('no_ktp');
            $table->string('no_paspor', 50)->nullable()->after('kewarganegaraan');
            $table->string('provinsi', 100)->nullable()->after('alamat_ktp');
            $table->string('kabupaten_kota', 100)->nullable()->after('provinsi');
            $table->string('kecamatan', 100)->nullable()->after('kabupaten_kota');
            $table->string('negara', 100)->nullable()->after('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn([
                'kewarganegaraan',
                'no_paspor',
                'provinsi',
                'kabupaten_kota',
                'kecamatan',
                'negara'
            ]);
        });
    }
};
