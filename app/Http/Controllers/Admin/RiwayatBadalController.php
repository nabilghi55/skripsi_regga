<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatBadal;
use App\Models\Khatib;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RiwayatBadalController extends Controller
{
    public function index()
    {
        $riwayatBadals = RiwayatBadal::with(['khatib', 'masjid', 'pengganti'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.jadwal.riwayat_index', compact('riwayatBadals'));
    }

    public function edit($id)
    {
        $riwayat = RiwayatBadal::findOrFail($id);
        
        // Find Khatibs available on this day
        $date = $riwayat->jadwal ? $riwayat->jadwal->tanggal->toDateString() : Carbon::now()->toDateString();
        $scheduledKhatibIds = Jadwal::whereDate('tanggal', $date)->pluck('khatib_id')->toArray();
        $khatibs = Khatib::whereNotIn('id', $scheduledKhatibIds)->where('status', 'Normal')->get();

        return view('admin.jadwal.riwayat_edit', compact('riwayat', 'khatibs'));
    }

    public function update(Request $request, $id)
    {
        $riwayat = RiwayatBadal::findOrFail($id);
        
        $request->validate([
            'pengganti_id' => 'nullable|exists:khatibs,id',
            'status' => 'required|in:Belum Terverifikasi,Sudah Terverifikasi',
        ]);

        $riwayat->update([
            'pengganti_id' => $request->pengganti_id,
            'status' => $request->status,
        ]);

        // If there is an associated schedule, update its Khatib and status
        if ($riwayat->jadwal_id && $request->pengganti_id) {
            $jadwal = Jadwal::find($riwayat->jadwal_id);
            if ($jadwal) {
                $jadwal->update([
                    'khatib_id' => $request->pengganti_id,
                    'status' => 'Badal'
                ]);
            }
        }

        return redirect()->route('admin.riwayatBadal.index')->with('success', 'Riwayat Badal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $riwayat = RiwayatBadal::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('admin.riwayatBadal.index')->with('success', 'Riwayat Badal berhasil dihapus.');
    }
}
