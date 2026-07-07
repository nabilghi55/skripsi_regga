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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('khatib_id')->nullable()->change();
            $table->foreignId('masjid_id')->nullable()->after('khatib_id')->constrained('masjids')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['masjid_id']);
            $table->dropColumn('masjid_id');
            $table->foreignId('khatib_id')->nullable(false)->change();
        });
    }
};
