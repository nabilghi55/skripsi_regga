<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Khatib;
use App\Models\Masjid;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function riwayatKhotib(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Jadwal::with(['khatib', 'masjid']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('khatib', function ($k) use ($search) {
                    $k->where('nama', 'like', "%{$search}%");
                })->orWhereHas('masjid', function ($m) use ($search) {
                    $m->where('nama', 'like', "%{$search}%");
                });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $jadwals = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        $statuses = ['Hadir', 'Izin', 'Sakit', 'Badal', 'Tidak Hadir', 'Aktif'];

        return view('admin.riwayat.khatib', compact('jadwals', 'statuses', 'search', 'status'));
    }

    public function riwayatTakmir(Request $request)
    {
        $search = $request->input('search');
        $masjidId = $request->input('masjid_id');

        $query = Jadwal::with(['khatib', 'masjid']);

        if ($masjidId) {
            $query->where('masjid_id', $masjidId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('khatib', function ($k) use ($search) {
                    $k->where('nama', 'like', "%{$search}%");
                })->orWhereHas('masjid', function ($m) use ($search) {
                    $m->where('nama', 'like', "%{$search}%");
                })->orWhere('catatan_saran_takmir', 'like', "%{$search}%");
            });
        }

        $jadwals = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        $masjids = Masjid::all();

        return view('admin.riwayat.takmir', compact('jadwals', 'masjids', 'search', 'masjidId'));
    }

    public function riwayatAktivitas(Request $request)
    {
        $search = $request->input('search');

        $query = ActivityLog::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('activity', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.riwayat.aktivitas', compact('logs', 'search'));
    }
}
