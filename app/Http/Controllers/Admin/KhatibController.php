<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khatib;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KhatibController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $khatibs = Khatib::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nbm', 'like', "%{$search}%")
                         ->orWhere('no_hp', 'like', "%{$search}%");
        })->paginate(5)->withQueryString();

        return view('admin.khatib.index', compact('khatibs', 'search'));
    }

    public function create()
    {
        return view('admin.khatib.form', [
            'khatib' => new Khatib(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nbm' => 'nullable|string|max:50',
            'no_hp' => 'required|string|max:20',
            'no_hp_2' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'required|string',
            'status' => 'required|in:Normal,Udzur / Sakit,Off,Tugas / Izin',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Generate username
        $cleanName = str_replace(['Ust. ', 'ust. ', 'Ust ', 'ust '], '', $request->nama);
        $baseUsername = Str::slug($cleanName, '');
        if (empty($baseUsername)) {
            $baseUsername = 'khatib' . rand(10, 99);
        }
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $request->nama,
            'username' => $username,
            'email' => $username . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'khatib',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_profile')) {
            $fotoPath = $request->file('foto_profile')->store('profile_photos', 'public');
        }

        Khatib::create([
            'user_id' => $user->id,
            'nbm' => $request->nbm,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'no_hp_2' => $request->no_hp_2,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'foto_profile' => $fotoPath,
        ]);

        return redirect()->route('admin.khatib.index')->with([
            'success' => 'Data Khatib berhasil ditambahkan. Username login: ' . $username,
            'new_khatib' => [
                'nama' => $request->nama,
                'username' => $username,
                'no_hp' => $request->no_hp,
            ]
        ]);
    }

    public function show(Khatib $khatib)
    {
        $riwayat = Jadwal::with('masjid')
            ->where('khatib_id', $khatib->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $rekap = [
            'Hadir' => $riwayat->where('status', 'Hadir')->count(),
            'Izin' => $riwayat->where('status', 'Izin')->count(),
            'Sakit' => $riwayat->where('status', 'Sakit')->count(),
            'Badal' => $riwayat->where('status', 'Badal')->count(),
            'Tidak Hadir' => $riwayat->where('status', 'Tidak Hadir')->count(),
            'Total' => $riwayat->count(),
        ];

        return view('admin.khatib.show', compact('khatib', 'riwayat', 'rekap'));
    }

    public function edit(Khatib $khatib)
    {
        return view('admin.khatib.form', [
            'khatib' => $khatib,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Khatib $khatib)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nbm' => 'nullable|string|max:50',
            'no_hp' => 'required|string|max:20',
            'no_hp_2' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'required|string',
            'status' => 'required|in:Normal,Udzur / Sakit,Off,Tugas / Izin',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'nbm' => $request->nbm,
            'no_hp' => $request->no_hp,
            'no_hp_2' => $request->no_hp_2,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ];

        if ($request->hasFile('foto_profile')) {
            $data['foto_profile'] = $request->file('foto_profile')->store('profile_photos', 'public');
        }

        $khatib->update($data);

        if ($khatib->user) {
            $khatib->user->update([
                'name' => $request->nama,
            ]);
        }

        return redirect()->route('admin.khatib.index')->with('success', 'Data Khatib berhasil diperbarui.');
    }

    public function destroy(Khatib $khatib)
    {
        if ($khatib->user) {
            $khatib->user->delete();
        } else {
            $khatib->delete();
        }

        return redirect()->route('admin.khatib.index')->with('success', 'Data Khatib berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $khatibs = Khatib::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nbm', 'like', "%{$search}%");
        })->get();

        $filename = "data-khatib-" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'NBM', 'Nama Khatib', 'Alamat', 'No HP 1', 'No HP 2', 'Tanggal Lahir', 'Status'];

        $callback = function() use($khatibs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($khatibs as $index => $k) {
                fputcsv($file, [
                    $index + 1,
                    $k->nbm ?? '-',
                    $k->nama,
                    $k->alamat,
                    $k->no_hp,
                    $k->no_hp_2 ?? '-',
                    $k->tanggal_lahir ? $k->tanggal_lahir->format('Y-m-d') : '-',
                    $k->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cetak(Request $request)
    {
        $search = $request->input('search');
        $khatibs = Khatib::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nbm', 'like', "%{$search}%");
        })->get();

        return view('admin.khatib.cetak', compact('khatibs', 'search'));
    }
}
