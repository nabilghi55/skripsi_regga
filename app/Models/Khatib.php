<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Khatib extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nbm',
        'nama',
        'no_hp',
        'no_hp_2',
        'tanggal_lahir',
        'alamat',
        'jenjang_pendidikan',
        'status',
        'foto_profile',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function riwayatBadals()
    {
        return $this->hasMany(RiwayatBadal::class, 'khatib_id');
    }

    public function riwayatBadalPengganti()
    {
        return $this->hasMany(RiwayatBadal::class, 'pengganti_id');
    }
}
