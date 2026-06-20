<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;

class MasjidController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kecamatan = $request->input('kecamatan');

        $masjids = Masjid::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_masjid', 'like', "%{$search}%");
        })
        ->when($kecamatan, function ($query, $kecamatan) {
            return $query->where('kecamatan', $kecamatan);
        })
        ->paginate(5)->withQueryString();

        $kecamatans = ['Lowokwaru', 'Dau', 'Sukun', 'Klojen', 'Kedungkandang', 'Blimbing'];

        return view('admin.masjid.index', compact('masjids', 'search', 'kecamatan', 'kecamatans'));
    }

    public function create()
    {
        return view('admin.masjid.form', [
            'masjid' => new Masjid(),
            'isEdit' => false,
            'kecamatans' => ['Lowokwaru', 'Dau', 'Sukun', 'Klojen', 'Kedungkandang', 'Blimbing'],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_masjid' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:255',
            'no_hp_1' => 'nullable|string|max:20',
            'no_hp_2' => 'nullable|string|max:20',
            'google_maps_link' => 'nullable|url',
            'kategori' => 'required|string|in:Masjid Muhammadiyah,Masjid Independen',
        ]);

        Masjid::create($request->all());

        return redirect()->route('admin.masjid.index')->with('success', 'Data Masjid berhasil ditambahkan.');
    }

    public function edit(Masjid $masjid)
    {
        return view('admin.masjid.form', [
            'masjid' => $masjid,
            'isEdit' => true,
            'kecamatans' => ['Lowokwaru', 'Dau', 'Sukun', 'Klojen', 'Kedungkandang', 'Blimbing'],
        ]);
    }

    public function update(Request $request, Masjid $masjid)
    {
        $request->validate([
            'kode_masjid' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:255',
            'no_hp_1' => 'nullable|string|max:20',
            'no_hp_2' => 'nullable|string|max:20',
            'google_maps_link' => 'nullable|url',
            'kategori' => 'required|string|in:Masjid Muhammadiyah,Masjid Independen',
        ]);

        $masjid->update($request->all());

        return redirect()->route('admin.masjid.index')->with('success', 'Data Masjid berhasil diperbarui.');
    }

    public function destroy(Masjid $masjid)
    {
        $masjid->delete();

        return redirect()->route('admin.masjid.index')->with('success', 'Data Masjid berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $kecamatan = $request->input('kecamatan');

        $masjids = Masjid::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_masjid', 'like', "%{$search}%");
        })
        ->when($kecamatan, function ($query, $kecamatan) {
            return $query->where('kecamatan', $kecamatan);
        })
        ->get();

        $filename = "data-masjid-" . now()->format('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Kode Masjid', 'Nama Masjid', 'Alamat', 'Kecamatan', 'No HP 1', 'No HP 2', 'Kategori'];

        $callback = function() use($masjids, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($masjids as $index => $m) {
                fputcsv($file, [
                    $index + 1,
                    $m->kode_masjid ?? '-',
                    $m->nama,
                    $m->alamat,
                    $m->kecamatan,
                    $m->no_hp_1 ?? '-',
                    $m->no_hp_2 ?? '-',
                    $m->kategori
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cetak(Request $request)
    {
        $search = $request->input('search');
        $kecamatan = $request->input('kecamatan');

        $masjids = Masjid::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode_masjid', 'like', "%{$search}%");
        })
        ->when($kecamatan, function ($query, $kecamatan) {
            return $query->where('kecamatan', $kecamatan);
        })
        ->get();

        return view('admin.masjid.cetak', compact('masjids', 'search', 'kecamatan'));
    }
}
