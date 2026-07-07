<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'khatib_id',
        'masjid_id',
        'jadwal_id',
        'pesan',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function khatib()
    {
        return $this->belongsTo(Khatib::class);
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
