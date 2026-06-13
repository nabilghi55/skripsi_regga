<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Khatib;
use App\Models\Masjid;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jadwals = Jadwal::with(['masjid', 'khatib'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('masjid', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhereHas('khatib', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('admin.jadwal.index', compact('jadwals', 'search'));
    }

    public function create()
    {
        $masjids = Masjid::all();
        $khatibs = Khatib::where('status', 'Aktif')->get();

        return view('admin.jadwal.form', [
            'jadwal' => new Jadwal(),
            'isEdit' => false,
            'masjids' => $masjids,
            'khatibs' => $khatibs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'masjid_id' => 'required|exists:masjids,id',
            'khatib_id' => 'required|exists:khatibs,id',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal = Jadwal::create([
            'tanggal' => $request->tanggal,
            'masjid_id' => $request->masjid_id,
            'khatib_id' => $request->khatib_id,
            'keterangan' => $request->keterangan,
            'status' => 'Aktif',
        ]);

        // Automatically create in-app notification for the Khatib
        $masjid = Masjid::find($request->masjid_id);
        $formattedDate = Carbon::parse($request->tanggal)->translatedFormat('d F Y');
        $pesan = "Anda dijadwalkan menjadi Khatib Jumat pada tanggal {$formattedDate} di {$masjid->nama} pukul 08.00 WIB.";

        Notification::create([
            'khatib_id' => $request->khatib_id,
            'jadwal_id' => $jadwal->id,
            'pesan' => $pesan,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Khotbah berhasil ditambahkan dan notifikasi dikirim ke Khatib.');
    }

    public function edit(Jadwal $jadwal)
    {
        $masjids = Masjid::all();
        $khatibs = Khatib::where('status', 'Aktif')->get();

        return view('admin.jadwal.form', [
            'jadwal' => $jadwal,
            'isEdit' => true,
            'masjids' => $masjids,
            'khatibs' => $khatibs,
        ]);
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'masjid_id' => 'required|exists:masjids,id',
            'khatib_id' => 'required|exists:khatibs,id',
            'keterangan' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Khotbah berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Khotbah berhasil dihapus.');
    }
}
