<?php

namespace App\Http\Controllers\Khatib;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function getKhatib()
    {
        return Auth::user()->khatib;
    }

    public function index()
    {
        $khatib = $this->getKhatib();
        
        // Find nearest schedule from today
        $nextJadwal = Jadwal::with(['masjid'])
            ->where('khatib_id', $khatib->id)
            ->where('tanggal', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->first();

        // Check for unread notifications
        $unreadNotification = Notification::where('khatib_id', $khatib->id)
            ->whereNull('read_at')
            ->first();

        return view('khatib.dashboard', compact('khatib', 'nextJadwal', 'unreadNotification'));
    }

    public function jadwalSaya()
    {
        $khatib = $this->getKhatib();
        $jadwals = Jadwal::with(['masjid'])
            ->where('khatib_id', $khatib->id)
            ->where('tanggal', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('khatib.jadwal.index', compact('jadwals'));
    }

    public function riwayat()
    {
        $khatib = $this->getKhatib();
        $jadwals = Jadwal::with(['masjid'])
            ->where('khatib_id', $khatib->id)
            ->where('tanggal', '<', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('khatib.jadwal.riwayat', compact('jadwals'));
    }

    public function detailJadwal(Jadwal $jadwal)
    {
        // Ensure this schedule belongs to the authenticated Khatib
        $khatib = $this->getKhatib();
        if ($jadwal->khatib_id !== $khatib->id) {
            abort(403, 'Unauthorized.');
        }

        return view('khatib.jadwal.show', compact('jadwal'));
    }

    public function formPerubahan(Jadwal $jadwal)
    {
        $khatib = $this->getKhatib();
        if ($jadwal->khatib_id !== $khatib->id) {
            abort(403, 'Unauthorized.');
        }

        return view('khatib.jadwal.perubahan', compact('jadwal'));
    }

    public function kirimPerubahan(Request $request, Jadwal $jadwal)
    {
        $khatib = $this->getKhatib();
        if ($jadwal->khatib_id !== $khatib->id) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'alasan' => 'required|string|max:200',
        ]);

        // Update schedule status
        $jadwal->update([
            'status' => 'Perubahan Diajukan',
            'keterangan' => 'Perubahan diajukan: ' . $request->alasan
        ]);

        // Prepare WhatsApp message
        $formattedDate = $jadwal->tanggal->translatedFormat('d F Y');
        $adminPhone = '6281234567890'; // Mock Admin phone number
        $message = "Assalamu'alaikum Pengurus CMM,\n\nSaya {$khatib->nama} ingin mengajukan perubahan jadwal khotbah Jumat pada tanggal {$formattedDate} di {$jadwal->masjid->nama}.\n\nAlasan perubahan: {$request->alasan}";

        $waUrl = "https://wa.me/{$adminPhone}?text=" . urlencode($message);

        return redirect()->away($waUrl);
    }

    public function markNotifRead(Notification $notification)
    {
        $khatib = $this->getKhatib();
        if ($notification->khatib_id !== $khatib->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['read_at' => Carbon::now()]);

        return response()->json(['success' => true]);
    }

    public function profile()
    {
        $khatib = $this->getKhatib();
        return view('khatib.profile', compact('khatib'));
    }

    public function updateProfile(Request $request)
    {
        $khatib = $this->getKhatib();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'no_hp_2' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nama', 'no_hp', 'no_hp_2', 'alamat']);

        if ($request->hasFile('foto_profile')) {
            $data['foto_profile'] = $request->file('foto_profile')->store('profile_photos', 'public');
        }

        $khatib->update($data);

        // Update User name
        Auth::user()->update([
            'name' => $request->nama
        ]);

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password Anda berhasil diubah.');
    }

    public function cetakJadwal(Request $request)
    {
        $khatib = $this->getKhatib();
        $jadwals = Jadwal::with(['masjid'])
            ->where('khatib_id', $khatib->id)
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('khatib.jadwal.cetak', compact('jadwals', 'khatib'));
    }

    public function notifications()
    {
        $khatib = $this->getKhatib();
        $notifications = Notification::where('khatib_id', $khatib->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('khatib.notification.index', compact('khatib', 'notifications'));
    }
}
