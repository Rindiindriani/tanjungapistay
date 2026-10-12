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
            $table->string('reset_password_token', 64)->nullable()->after('password');
            $table->timestamp('reset_password_token_created_at')->nullable()->after('reset_password_token');
        });

        Schema::table('pengelola', function (Blueprint $table) {
            $table->string('reset_password_token', 64)->nullable()->after('password');
            $table->timestamp('reset_password_token_created_at')->nullable()->after('reset_password_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn(['reset_password_token', 'reset_password_token_created_at']);
        });

        Schema::table('pengelola', function (Blueprint $table) {
            $table->dropColumn(['reset_password_token', 'reset_password_token_created_at']);
        });
    }
};
