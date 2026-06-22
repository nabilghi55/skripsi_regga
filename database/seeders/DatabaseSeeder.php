<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin (Pengurus CMM)
        User::create([
            'name' => 'Pengurus CMM',
            'username' => 'admin',
            'email' => 'admin@cmm.or.id',
            'password' => bcrypt('admin123'),
            'role' => 'pengurus',
        ]);

        // 2. Khatib Seeders
        $khatibData = [
            ['nama' => 'Ust. Ahmad', 'username' => 'ahmad', 'no_hp' => '081211111111', 'alamat' => 'Jl. Soekarno Hatta No. 20, Lowokwaru, Malang', 'status' => 'Aktif'],
            ['nama' => 'Ust. Hasan', 'username' => 'hasan', 'no_hp' => '081222222222', 'alamat' => 'Jl. Candi No. 15, Dau, Malang', 'status' => 'Aktif'],
            ['nama' => 'Ust. Ali', 'username' => 'ali', 'no_hp' => '081233333333', 'alamat' => 'Jl. Kawi No. 10, Sukun, Malang', 'status' => 'Aktif'],
            ['nama' => 'Ust. Budi', 'username' => 'budi', 'no_hp' => '081244444444', 'alamat' => 'Jl. Ijen No. 5, Klojen, Malang', 'status' => 'Tidak Aktif'],
        ];

        foreach ($khatibData as $data) {
            $user = User::create([
                'name' => $data['nama'],
                'username' => $data['username'],
                'email' => $data['username'] . '@example.com',
                'password' => bcrypt('password123'),
                'role' => 'khatib',
            ]);

            \App\Models\Khatib::create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
                'status' => $data['status'],
            ]);
        }

        // 3. Takmir User
        $takmirUser = User::create([
            'name' => 'Ahmad Fathoni',
            'username' => 'takmir',
            'email' => 'takmir@annur.or.id',
            'password' => bcrypt('password123'),
            'role' => 'takmir',
        ]);

        // 4. Masjid Seeders
        $masjidAnNur = \App\Models\Masjid::create([
            'kode_masjid' => 'M001',
            'nama' => 'Masjid An Nur',
            'alamat' => 'Jl. Soekarno Hatta No. 45, Lowokwaru, Malang',
            'kecamatan' => 'Lowokwaru',
            'no_hp_1' => '081234567890',
            'no_hp_2' => '081298765432',
            'google_maps_link' => 'https://maps.app.goo.gl/ab123',
            'kategori' => 'Masjid Muhammadiyah',
            'user_id' => $takmirUser->id,
        ]);

        // Seed some other masjids for admin view / selection
        $masjidAlJihad = \App\Models\Masjid::create([
            'kode_masjid' => 'M002',
            'nama' => 'Masjid Al Jihad',
            'alamat' => 'Jl. Candi No. 12, Dau, Malang',
            'kecamatan' => 'Dau',
            'no_hp_1' => '081333333333',
            'kategori' => 'Masjid Independen',
        ]);

        // 5. Jadwal Seeders
        $khatibList = \App\Models\Khatib::all();
        $thisFriday = \Carbon\Carbon::now()->startOfWeek()->addDays(4); // Friday of this week

        // Schedule for this Friday (Jadwal Terdekat)
        \App\Models\Jadwal::create([
            'tanggal' => $thisFriday->toDateString(),
            'jumat_ke' => 4,
            'waktu_khutbah' => '12:05',
            'masjid_id' => $masjidAnNur->id,
            'khatib_id' => $khatibList->first()->id, // Ust. Ahmad
            'keterangan' => 'Khotbah Jumat rutin',
            'status' => 'Aktif',
        ]);

        // Schedule for next Friday (in 1 week)
        \App\Models\Jadwal::create([
            'tanggal' => $thisFriday->copy()->addWeek()->toDateString(),
            'jumat_ke' => 5,
            'waktu_khutbah' => '12:05',
            'masjid_id' => $masjidAnNur->id,
            'khatib_id' => $khatibList->get(1)->id, // Ust. Hasan
            'keterangan' => 'Khotbah Jumat rutin',
            'status' => 'Aktif',
        ]);

        // Schedule for Friday in 3 weeks (in 1 month)
        \App\Models\Jadwal::create([
            'tanggal' => $thisFriday->copy()->addWeeks(3)->toDateString(),
            'jumat_ke' => 2,
            'waktu_khutbah' => '12:05',
            'masjid_id' => $masjidAnNur->id,
            'khatib_id' => $khatibList->get(2)->id, // Ust. Ali
            'keterangan' => 'Khotbah Jumat rutin',
            'status' => 'Aktif',
        ]);

        // Schedule for Friday in 8 weeks (in 4 months)
        \App\Models\Jadwal::create([
            'tanggal' => $thisFriday->copy()->addWeeks(8)->toDateString(),
            'jumat_ke' => 3,
            'waktu_khutbah' => '12:05',
            'masjid_id' => $masjidAnNur->id,
            'khatib_id' => $khatibList->first()->id, // Ust. Ahmad
            'keterangan' => 'Khotbah Jumat rutin',
            'status' => 'Aktif',
        ]);

        // Schedule for Friday in 18 weeks (longer period)
        \App\Models\Jadwal::create([
            'tanggal' => $thisFriday->copy()->addWeeks(18)->toDateString(),
            'jumat_ke' => 1,
            'waktu_khutbah' => '12:05',
            'masjid_id' => $masjidAnNur->id,
            'khatib_id' => $khatibList->get(1)->id, // Ust. Hasan
            'keterangan' => 'Khotbah Jumat rutin',
            'status' => 'Aktif',
        ]);

        // Schedule for other masjid
        \App\Models\Jadwal::create([
            'tanggal' => $thisFriday->toDateString(),
            'jumat_ke' => 4,
            'waktu_khutbah' => '12:00',
            'masjid_id' => $masjidAlJihad->id,
            'khatib_id' => $khatibList->get(2)->id, // Ust. Ali
            'keterangan' => 'Khotbah Jumat rutin',
            'status' => 'Aktif',
        ]);
    }
}
