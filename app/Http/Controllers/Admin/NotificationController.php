<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khatib;
use App\Models\Jadwal;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $khatibs = Khatib::where('status', 'Aktif')->get();
        $jadwals = Jadwal::with(['masjid'])->orderBy('tanggal', 'desc')->get();

        return view('admin.notification.index', compact('khatibs', 'jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'khatib_id' => 'required|exists:khatibs,id',
            'jadwal_id' => 'nullable|exists:jadwals,id',
            'pesan' => 'required|string|max:160',
        ]);

        $notification = Notification::create([
            'khatib_id' => $request->khatib_id,
            'jadwal_id' => $request->jadwal_id,
            'pesan' => $request->pesan,
        ]);

        $khatib = Khatib::find($request->khatib_id);
        
        // Clean phone number (replace leading 0 with 62)
        $phone = $khatib->no_hp;
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Format whatsapp url
        $waUrl = "https://wa.me/{$phone}?text=" . urlencode($request->pesan);

        return redirect()->away($waUrl);
    }
}
