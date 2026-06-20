<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jumat_ke',
        'waktu_khutbah',
        'masjid_id',
        'khatib_id',
        'keterangan',
        'catatan_saran_takmir',
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

    public function riwayatBadals()
    {
        return $this->hasMany(RiwayatBadal::class);
    }

    public function getHijriDateAttribute()
    {
        if (!$this->tanggal) return '';
        
        $time = strtotime($this->tanggal->toDateString());
        $year = (int)date('Y', $time);
        $month = (int)date('m', $time);
        $day = (int)date('d', $time);

        // Tabular Islamic Calendar algorithm
        if (($year > 1582) || (($year == 1582) && ($month > 10)) || (($year == 1582) && ($month == 10) && ($day > 14))) {
            $jd = (int)((1461 * ($year + 4800 + (int)(($month - 14) / 12))) / 4) +
                  (int)((367 * ($month - 2 - 12 * ((int)(($month - 14) / 12)))) / 12) -
                  (int)((3 * ((int)(($year + 4900 + (int)(($month - 14) / 12)) / 100))) / 4) +
                  $day - 32075;
        } else {
            $jd = 367 * $year - (int)((7 * ($year + 5001 + (int)(($month - 9) / 7))) / 4) +
                  (int)((275 * $month) / 9) + $day + 1729777;
        }

        $l = $jd - 1948440 + 10632;
        $n = (int)(($l - 1) / 10631);
        $l = $l - 30 * $n + 1;
        $j = (int)((11 * $l + 3) / 30);
        $l = $l - (int)((30 * $j + 11) / 11);
        $yearHijri = 30 * $n + $j;
        $monthHijri = (int)((30 * $l + 9) / 885);
        $dayHijri = $l - (int)((29 * $monthHijri + 1) / 2) + 2; // slight adjustment

        if ($dayHijri > 30) {
            $dayHijri -= 30;
            $monthHijri++;
        }
        if ($monthHijri > 12) {
            $monthHijri = 1;
            $yearHijri++;
        }
        if ($dayHijri <= 0) {
            $monthHijri--;
            if ($monthHijri <= 0) {
                $monthHijri = 12;
                $yearHijri--;
            }
            $dayHijri = 30 + $dayHijri;
        }

        $hijriMonths = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal', 4 => 'Rabiul Akhir',
            5 => 'Jumadil Awal', 6 => 'Jumadil Akhir', 7 => 'Rajab', 8 => 'Syaban',
            9 => 'Ramadhan', 10 => 'Syawal', 11 => 'Dzulqadah', 12 => 'Dzulhijjah'
        ];

        $monthName = $hijriMonths[$monthHijri] ?? '';
        return "{$dayHijri} {$monthName} {$yearHijri} H";
    }
}
