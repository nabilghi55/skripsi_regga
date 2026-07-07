<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khatib;
use App\Models\Masjid;
use App\Models\Jadwal;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $khatibs = Khatib::where('status', 'Aktif')->get();
        $masjids = Masjid::all();
        $jadwals = Jadwal::with(['masjid', 'khatib'])->orderBy('tanggal', 'desc')->get();

        $notifications = Notification::with(['khatib', 'masjid', 'jadwal.masjid', 'jadwal.khatib'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notification.index', compact('khatibs', 'masjids', 'jadwals', 'notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:khatib,masjid',
            'khatib_id' => 'required_if:target_type,khatib|nullable|exists:khatibs,id',
            'masjid_id' => 'required_if:target_type,masjid|nullable|exists:masjids,id',
            'jadwal_id' => 'nullable|exists:jadwals,id',
            'pesan' => 'required|string|max:200',
        ]);

        $notification = Notification::create([
            'khatib_id' => $request->target_type === 'khatib' ? $request->khatib_id : null,
            'masjid_id' => $request->target_type === 'masjid' ? $request->masjid_id : null,
            'jadwal_id' => $request->jadwal_id,
            'pesan' => $request->pesan,
        ]);

        $phone = '';
        $recipientName = '';

        if ($request->target_type === 'khatib') {
            $khatib = Khatib::find($request->khatib_id);
            $phone = $khatib->no_hp;
            $recipientName = $khatib->nama;
            ActivityLog::log("Mengirim Notifikasi WhatsApp ke Khatib: " . $recipientName);
        } else {
            $masjid = Masjid::find($request->masjid_id);
            $phone = $masjid->no_hp_1 ?: $masjid->no_hp_2;
            $recipientName = "Takmir " . $masjid->nama;
            ActivityLog::log("Mengirim Notifikasi WhatsApp ke Takmir Masjid: " . $masjid->nama);
        }

        // Clean phone number (replace leading 0 or + with nothing, and prepend 62)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Format whatsapp url
        $waUrl = "https://wa.me/{$phone}?text=" . urlencode($request->pesan);

        return redirect()->away($waUrl);
    }
}
