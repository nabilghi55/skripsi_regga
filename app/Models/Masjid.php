<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Masjid extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_masjid',
        'nama',
        'alamat',
        'kecamatan',
        'no_hp_1',
        'no_hp_2',
        'google_maps_link',
        'kategori',
        'user_id',
        'foto_profile',
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function riwayatBadals()
    {
        return $this->hasMany(RiwayatBadal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
