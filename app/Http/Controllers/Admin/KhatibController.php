<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khatib;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KhatibController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $khatibs = Khatib::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
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
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
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

        Khatib::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'status' => $request->status,
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
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $khatib->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

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
            $khatib->user->delete(); // Cascade will delete Khatib, but let's delete explicitly to be safe
        } else {
            $khatib->delete();
        }

        return redirect()->route('admin.khatib.index')->with('success', 'Data Khatib berhasil dihapus.');
    }
}
