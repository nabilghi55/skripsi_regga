<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Masjid extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'kecamatan',
        'google_maps_link',
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
