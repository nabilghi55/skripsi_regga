<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatBadal extends Model
{
    use HasFactory;

    protected $table = 'riwayat_badals';

    protected $fillable = [
        'tanggal_pengajuan',
        'jadwal_id',
        'khatib_id',
        'masjid_id',
        'pengganti_id',
        'status',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function khatib()
    {
        return $this->belongsTo(Khatib::class, 'khatib_id');
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function pengganti()
    {
        return $this->belongsTo(Khatib::class, 'pengganti_id');
    }
}
