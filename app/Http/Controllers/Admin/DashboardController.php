<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khatib;
use App\Models\Masjid;
use App\Models\Jadwal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKhatib = Khatib::count();
        $totalMasjid = Masjid::count();
        $totalJadwal = Jadwal::count();
        
        // Jadwal Minggu Ini (Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
        $jadwalMingguIni = Jadwal::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->count();

        // Jadwal Terdekat (upcoming schedules from today)
        $jadwalTerdekat = Jadwal::with(['masjid', 'khatib'])
            ->where('tanggal', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKhatib',
            'totalMasjid',
            'totalJadwal',
            'jadwalMingguIni',
            'jadwalTerdekat'
        ));
    }
}
