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
        Schema::table('masjids', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('kode_masjid')->constrained('users')->onDelete('cascade');
            $table->string('foto_profile')->nullable()->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masjids', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'foto_profile']);
        });
    }
};
