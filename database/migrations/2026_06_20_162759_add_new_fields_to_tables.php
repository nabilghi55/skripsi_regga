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
        // 1. Update khatibs table
        Schema::table('khatibs', function (Blueprint $table) {
            $table->string('nbm')->nullable()->after('user_id');
            $table->string('no_hp_2')->nullable()->after('no_hp');
            $table->date('tanggal_lahir')->nullable()->after('no_hp_2');
            $table->string('foto_profile')->nullable()->after('status');
        });

        // 2. Update masjids table
        Schema::table('masjids', function (Blueprint $table) {
            $table->string('kode_masjid')->nullable()->after('id');
            $table->string('no_hp_1')->nullable()->after('alamat');
            $table->string('no_hp_2')->nullable()->after('no_hp_1');
            $table->string('kategori')->default('Masjid Muhammadiyah')->after('google_maps_link');
        });

        // 3. Update jadwals table
        Schema::table('jadwals', function (Blueprint $table) {
            $table->integer('jumat_ke')->nullable()->after('tanggal');
            $table->time('waktu_khutbah')->default('12:00')->after('jumat_ke');
            $table->text('catatan_saran_takmir')->nullable()->after('keterangan');
        });

        // 4. Create riwayat_badals table
        Schema::create('riwayat_badals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengajuan');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('khatib_id')->constrained('khatibs')->onDelete('cascade'); // original khatib
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('pengganti_id')->nullable()->constrained('khatibs')->onDelete('cascade'); // replacement khatib
            $table->string('status')->default('Belum Terverifikasi'); // Belum Terverifikasi, Sudah Terverifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_badals');

        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn(['jumat_ke', 'waktu_khutbah', 'catatan_saran_takmir']);
        });

        Schema::table('masjids', function (Blueprint $table) {
            $table->dropColumn(['kode_masjid', 'no_hp_1', 'no_hp_2', 'kategori']);
        });

        Schema::table('khatibs', function (Blueprint $table) {
            $table->dropColumn(['nbm', 'no_hp_2', 'tanggal_lahir', 'foto_profile']);
        });
    }
};
