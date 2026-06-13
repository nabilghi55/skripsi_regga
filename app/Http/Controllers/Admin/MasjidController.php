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
        $masjids = Masjid::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('kecamatan', 'like', "%{$search}%");
        })->paginate(5)->withQueryString();

        return view('admin.masjid.index', compact('masjids', 'search'));
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
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:255',
            'google_maps_link' => 'nullable|url',
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
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:255',
            'google_maps_link' => 'nullable|url',
        ]);

        $masjid->update($request->all());

        return redirect()->route('admin.masjid.index')->with('success', 'Data Masjid berhasil diperbarui.');
    }

    public function destroy(Masjid $masjid)
    {
        $masjid->delete();

        return redirect()->route('admin.masjid.index')->with('success', 'Data Masjid berhasil dihapus.');
    }
}
