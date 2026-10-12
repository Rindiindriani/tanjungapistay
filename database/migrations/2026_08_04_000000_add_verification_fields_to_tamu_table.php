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
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->string('email_verification_token', 64)->nullable()->after('email_verified_at');
            $table->timestamp('no_hp_verified_at')->nullable()->after('no_hp');
            $table->string('no_hp_verification_code', 6)->nullable()->after('no_hp_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn([
                'email_verified_at',
                'email_verification_token',
                'no_hp_verified_at',
                'no_hp_verification_code',
            ]);
        });
    }
};
