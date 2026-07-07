<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khatib;
use App\Models\Masjid;
use App\Models\Jadwal;
use App\Models\RiwayatBadal;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalKhatib = Khatib::count();
        $totalMasjid = Masjid::count();
        $totalJadwal = Jadwal::count();
        
        // Jadwal Minggu Ini (Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
        $jadwalMingguIni = Jadwal::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count();

        // Filters for Dashboard Schedule List
        $search = $request->input('search');
        $periode = $request->input('periode'); // 1_minggu, 1_bulan, 4_bulan

        $query = Jadwal::with(['masjid', 'khatib'])
            ->where('tanggal', '>=', Carbon::today()->toDateString());

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('masjid', function ($mQuery) use ($search) {
                    $mQuery->where('nama', 'like', "%{$search}%");
                })->orWhereHas('khatib', function ($kQuery) use ($search) {
                    $kQuery->where('nama', 'like', "%{$search}%");
                });
            });
        }

        if ($periode === '1_minggu') {
            $query->where('tanggal', '<=', Carbon::today()->addDays(7)->toDateString());
        } elseif ($periode === '1_bulan') {
            $query->where('tanggal', '<=', Carbon::today()->addDays(30)->toDateString());
        } elseif ($periode === '4_bulan') {
            $query->where('tanggal', '<=', Carbon::today()->addDays(120)->toDateString());
        }

        // Get schedules ordered by date ascending, then unique them by masjid_id in PHP
        $allSchedules = $query->orderBy('tanggal', 'asc')->get();
        $jadwalTerdekat = $allSchedules->unique('masjid_id')->take(10);

        // Fetch pending badals (unverified)
        $pendingBadals = RiwayatBadal::with(['khatib', 'masjid', 'pengganti'])
            ->where('status', 'Belum Terverifikasi')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch recent activity logs
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tanggal Update (Bahasa Indonesia) - get the latest update from jadwals, khatibs, and masjids
        $lastJadwal = Jadwal::max('updated_at');
        $lastKhatib = Khatib::max('updated_at');
        $lastMasjid = Masjid::max('updated_at');
        $latestUpdate = max($lastJadwal, $lastKhatib, $lastMasjid);
        $tanggalUpdate = $latestUpdate 
            ? Carbon::parse($latestUpdate)->locale('id')->translatedFormat('l, d F Y H:i') . ' WIB'
            : Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') . ' WIB';

        return view('admin.dashboard', compact(
            'totalKhatib',
            'totalMasjid',
            'totalJadwal',
            'jadwalMingguIni',
            'jadwalTerdekat',
            'pendingBadals',
            'recentActivities',
            'search',
            'periode',
            'tanggalUpdate'
        ));
    }
}
