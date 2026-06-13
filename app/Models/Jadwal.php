<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'masjid_id',
        'khatib_id',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function khatib()
    {
        return $this->belongsTo(Khatib::class);
    }
}
