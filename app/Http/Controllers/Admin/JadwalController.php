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
        $periode = $request->input('periode'); // 1_minggu, 1_bulan, 4_bulan
        $khatibId = $request->input('khatib_id');
        $masjidId = $request->input('masjid_id');

        $query = Jadwal::with(['masjid', 'khatib']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('masjid', function ($m) use ($search) {
                    $m->where('nama', 'like', "%{$search}%");
                })->orWhereHas('khatib', function ($k) use ($search) {
                    $k->where('nama', 'like', "%{$search}%");
                });
            });
        }

        if ($periode === '1_minggu') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(7)->toDateString()]);
        } elseif ($periode === '1_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(30)->toDateString()]);
        } elseif ($periode === '4_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(120)->toDateString()]);
        }

        if ($khatibId) {
            $query->where('khatib_id', $khatibId);
        }

        if ($masjidId) {
            $query->where('masjid_id', $masjidId);
        }

        $jadwals = $query->orderBy('tanggal', 'desc')->paginate(5)->withQueryString();
        $khatibs = Khatib::all();
        $masjids = Masjid::all();

        return view('admin.jadwal.index', compact('jadwals', 'search', 'periode', 'khatibId', 'masjidId', 'khatibs', 'masjids'));
    }

    public function cetak(Request $request)
    {
        $search = $request->input('search');
        $periode = $request->input('periode');
        $khatibId = $request->input('khatib_id');
        $masjidId = $request->input('masjid_id');

        $query = Jadwal::with(['masjid', 'khatib']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('masjid', function ($m) use ($search) {
                    $m->where('nama', 'like', "%{$search}%");
                })->orWhereHas('khatib', function ($k) use ($search) {
                    $k->where('nama', 'like', "%{$search}%");
                });
            });
        }

        if ($periode === '1_minggu') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(7)->toDateString()]);
        } elseif ($periode === '1_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(30)->toDateString()]);
        } elseif ($periode === '4_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(120)->toDateString()]);
        }

        if ($khatibId) {
            $query->where('khatib_id', $khatibId);
        }

        if ($masjidId) {
            $query->where('masjid_id', $masjidId);
        }

        $jadwals = $query->orderBy('tanggal', 'asc')->get();

        return view('admin.jadwal.cetak', compact('jadwals', 'search'));
    }

    public function create()
    {
        $masjids = Masjid::all();
        $khatibs = Khatib::all(); // Show all available Khatibs

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
            'jumat_ke' => 'nullable|integer|min:1|max:5',
            'waktu_khutbah' => 'required|string',
            'masjid_id' => 'required|exists:masjids,id',
            'khatib_id' => 'required|exists:khatibs,id',
            'keterangan' => 'nullable|string',
            'catatan_saran_takmir' => 'nullable|string',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Badal,Tidak Hadir,Aktif,Perubahan Diajukan',
        ]);

        $jadwal = Jadwal::create($request->all());

        // Automatically create in-app notification for the Khatib
        $masjid = Masjid::find($request->masjid_id);
        $formattedDate = Carbon::parse($request->tanggal)->translatedFormat('d F Y');
        $pesan = "Anda dijadwalkan menjadi Khatib Jumat pada tanggal {$formattedDate} di {$masjid->nama} pukul {$request->waktu_khutbah} WIB.";

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
        $khatibs = Khatib::all();

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
            'jumat_ke' => 'nullable|integer|min:1|max:5',
            'waktu_khutbah' => 'required|string',
            'masjid_id' => 'required|exists:masjids,id',
            'khatib_id' => 'required|exists:khatibs,id',
            'keterangan' => 'nullable|string',
            'catatan_saran_takmir' => 'nullable|string',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Badal,Tidak Hadir,Aktif,Perubahan Diajukan',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Khotbah berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Khotbah berhasil dihapus.');
    }

    public function accPerubahan(Jadwal $jadwal)
    {
        $jadwal->update([
            'status' => 'Badal', // Mark it as Badal needed/empty
        ]);

        // Create Riwayat Badal entry
        \App\Models\RiwayatBadal::create([
            'tanggal_pengajuan' => Carbon::now()->toDateString(),
            'jadwal_id' => $jadwal->id,
            'khatib_id' => $jadwal->khatib_id, // Original khatib
            'masjid_id' => $jadwal->masjid_id,
            'pengganti_id' => null, // No replacement yet
            'status' => 'Belum Terverifikasi',
        ]);

        return redirect()->back()->with('success', 'Pengajuan perubahan jadwal berhasil di-ACC. Masjid ini sekarang membutuhkan Khatib Badal.');
    }

    public function badalIndex(Request $request)
    {
        $date = $request->input('tanggal');
        if (!$date) {
            // Default to next Friday
            $date = Carbon::parse('next friday')->toDateString();
        }
        $type = $request->input('type', 'masjid'); // 'masjid' or 'khatib'

        $masjidsKosong = collect();
        $khatibsTidakBertugas = collect();

        if ($type === 'masjid') {
            // Find Masjids that do NOT have any schedule on this date
            $scheduledMasjidIds = Jadwal::whereDate('tanggal', $date)->pluck('masjid_id')->toArray();
            $masjidsKosong = Masjid::whereNotIn('id', $scheduledMasjidIds)->get();
        } else {
            // Find Khatibs that do NOT have any schedule on this date
            $scheduledKhatibIds = Jadwal::whereDate('tanggal', $date)->pluck('khatib_id')->toArray();
            $khatibsTidakBertugas = Khatib::whereNotIn('id', $scheduledKhatibIds)->get();
        }

        return view('admin.jadwal.badal', compact('date', 'type', 'masjidsKosong', 'khatibsTidakBertugas'));
    }

    public function badalTambahJadwalForm(Request $request)
    {
        $masjidId = $request->input('masjid_id');
        $date = $request->input('tanggal');

        $masjid = Masjid::findOrFail($masjidId);
        
        // Find Khatibs that do NOT have any schedule on this date
        $scheduledKhatibIds = Jadwal::whereDate('tanggal', $date)->pluck('khatib_id')->toArray();
        $khatibs = Khatib::whereNotIn('id', $scheduledKhatibIds)->where('status', 'Normal')->get();

        return view('admin.jadwal.badal_form', compact('masjid', 'date', 'khatibs'));
    }

    public function badalTambahJadwalStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumat_ke' => 'nullable|integer|min:1|max:5',
            'waktu_khutbah' => 'required|string',
            'masjid_id' => 'required|exists:masjids,id',
            'khatib_id' => 'required|exists:khatibs,id',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal = Jadwal::create([
            'tanggal' => $request->tanggal,
            'jumat_ke' => $request->jumat_ke,
            'waktu_khutbah' => $request->waktu_khutbah,
            'masjid_id' => $request->masjid_id,
            'khatib_id' => $request->khatib_id,
            'keterangan' => $request->keterangan,
            'status' => 'Badal', // Mark it as Badal
        ]);

        // If there's an unverified RiwayatBadal for this masjid and date, let's update it!
        $riwayatBadal = \App\Models\RiwayatBadal::where('masjid_id', $request->masjid_id)
            ->where('status', 'Belum Terverifikasi')
            ->first();
        if ($riwayatBadal) {
            $riwayatBadal->update([
                'pengganti_id' => $request->khatib_id,
                'status' => 'Sudah Terverifikasi'
            ]);
        }

        // Create notification
        $masjid = Masjid::find($request->masjid_id);
        $khatib = Khatib::find($request->khatib_id);
        $formattedDate = Carbon::parse($request->tanggal)->translatedFormat('d F Y');
        
        Notification::create([
            'khatib_id' => $request->khatib_id,
            'jadwal_id' => $jadwal->id,
            'pesan' => "Anda ditugaskan menjadi Khatib Badal pada hari Jumat, {$formattedDate} di {$masjid->nama} pukul {$request->waktu_khutbah} WIB.",
        ]);

        // Construct WhatsApp message
        $cleanPhone = preg_replace('/[^0-9]/', '', $khatib->no_hp);
        if (strpos($cleanPhone, '0') === 0) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $waMessage = "Assalamu'alaikum Wr. Wb.\n\n"
                   . "Yth. {$khatib->nama},\n"
                   . "Anda ditugaskan menjadi Khatib Badal pada hari Jumat, {$formattedDate} di {$masjid->nama} pukul {$request->waktu_khutbah} WIB.\n\n"
                   . "Mohon kehadirannya. Terima kasih.\n"
                   . "Wassalamu'alaikum Wr. Wb.";
        $waUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($waMessage);

        return redirect()->route('admin.jadwal.index')->with([
            'success' => 'Jadwal Badal berhasil ditambahkan.',
            'wa_badal_url' => $waUrl,
            'khatib_nama' => $khatib->nama
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $periode = $request->input('periode');
        $khatibId = $request->input('khatib_id');
        $masjidId = $request->input('masjid_id');

        $query = Jadwal::with(['masjid', 'khatib']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('masjid', function ($m) use ($search) {
                    $m->where('nama', 'like', "%{$search}%");
                })->orWhereHas('khatib', function ($k) use ($search) {
                    $k->where('nama', 'like', "%{$search}%");
                });
            });
        }

        if ($periode === '1_minggu') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(7)->toDateString()]);
        } elseif ($periode === '1_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(30)->toDateString()]);
        } elseif ($periode === '4_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(120)->toDateString()]);
        }

        if ($khatibId) {
            $query->where('khatib_id', $khatibId);
        }

        if ($masjidId) {
            $query->where('masjid_id', $masjidId);
        }

        $jadwals = $query->orderBy('tanggal', 'desc')->get();

        $filename = "data-jadwal-" . now()->format('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Tanggal Masehi', 'Tanggal Hijriyah', 'Jumat Ke', 'Waktu Khutbah', 'Nama Masjid', 'Nama Khatib', 'Status', 'Keterangan'];

        $callback = function() use($jadwals, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($jadwals as $index => $j) {
                fputcsv($file, [
                    $index + 1,
                    $j->tanggal->format('Y-m-d'),
                    $j->hijri_date,
                    $j->jumat_ke ?? '-',
                    $j->waktu_khutbah,
                    $j->masjid->nama,
                    $j->khatib->nama,
                    $j->status,
                    $j->keterangan ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
