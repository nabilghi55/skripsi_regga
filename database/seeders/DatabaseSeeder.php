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
    }
}
