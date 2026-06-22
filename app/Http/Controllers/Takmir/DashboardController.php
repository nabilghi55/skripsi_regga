<?php

namespace App\Http\Controllers\Takmir;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function getMasjid()
    {
        return Auth::user()->masjid;
    }

    public function index()
    {
        $masjid = $this->getMasjid();
        $thisFriday = Carbon::now()->startOfWeek()->addDays(4)->toDateString();
        
        $nextJadwal = null;
        if ($masjid) {
            // Priority: Schedule on this Friday
            $nextJadwal = Jadwal::with(['khatib'])
                ->where('masjid_id', $masjid->id)
                ->where('tanggal', $thisFriday)
                ->first();

            // Fallback: Absolute nearest schedule from today onwards if this Friday has no schedule
            if (!$nextJadwal) {
                $nextJadwal = Jadwal::with(['khatib'])
                    ->where('masjid_id', $masjid->id)
                    ->where('tanggal', '>=', Carbon::today()->toDateString())
                    ->orderBy('tanggal', 'asc')
                    ->first();
            }
        }

        // Show pop-up reminder if they just logged in and nextJadwal exists
        $showReminderPopup = session('show_reminder') && $nextJadwal;

        return view('takmir.dashboard', compact('masjid', 'nextJadwal', 'showReminderPopup'));
    }

    public function jadwalMasjid(Request $request)
    {
        $masjid = $this->getMasjid();
        $search = $request->input('search');
        $periode = $request->input('periode'); // 1_minggu, 1_bulan, 4_bulan

        $query = Jadwal::with(['khatib', 'masjid']);

        if ($masjid) {
            $query->where('masjid_id', $masjid->id);
        } else {
            $query->whereRaw('1=0');
        }

        if ($search) {
            $query->whereHas('khatib', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        if ($periode === '1_minggu') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(7)->toDateString()]);
        } elseif ($periode === '1_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(30)->toDateString()]);
        } elseif ($periode === '4_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(120)->toDateString()]);
        }

        $jadwals = $query->orderBy('tanggal', 'desc')->paginate(5)->withQueryString();

        return view('takmir.jadwal.index', compact('jadwals', 'search', 'periode', 'masjid'));
    }

    public function cetakJadwal(Request $request)
    {
        $masjid = $this->getMasjid();
        $periode = $request->input('periode'); // 1_bulan, 4_bulan
        $search = $request->input('search');

        $query = Jadwal::with(['khatib', 'masjid']);

        if ($masjid) {
            $query->where('masjid_id', $masjid->id);
        } else {
            $query->whereRaw('1=0');
        }

        if ($search) {
            $query->whereHas('khatib', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        if ($periode === '1_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(30)->toDateString()]);
        } elseif ($periode === '4_bulan') {
            $query->whereBetween('tanggal', [Carbon::today()->toDateString(), Carbon::today()->addDays(120)->toDateString()]);
        }

        $jadwals = $query->orderBy('tanggal', 'asc')->get();

        return view('takmir.jadwal.cetak', compact('jadwals', 'periode', 'masjid', 'search'));
    }

    public function profile()
    {
        $user = Auth::user();
        $masjid = $this->getMasjid();

        return view('takmir.profile', compact('user', 'masjid'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $masjid = $this->getMasjid();

        $request->validate([
            'nama' => 'required|string|max:255',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update Takmir name in users table
        $user->update([
            'name' => $request->nama,
        ]);

        // Update photo profile in masjids table
        if ($masjid && $request->hasFile('foto_profile')) {
            $path = $request->file('foto_profile')->store('profile_photos', 'public');
            $masjid->update([
                'foto_profile' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password Anda berhasil diubah.');
    }
}
