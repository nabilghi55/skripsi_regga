@extends('admin.layouts.admin')

@section('title', 'Data Khatib')
@section('page_header', 'Data Khatib')

@section('content')
@if(session('new_khatib'))
    @php
        $newKhatib = session('new_khatib');
        $cleanPhone = preg_replace('/[^0-9]/', '', $newKhatib['no_hp']);
        if (strpos($cleanPhone, '0') === 0) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        
        $waMessage = "Assalamu'alaikum Wr. Wb.\n\n"
                   . "Yth. " . $newKhatib['nama'] . ",\n"
                   . "Berikut adalah informasi akun Anda untuk mengakses website SIKJ CMM:\n\n"
                   . "🔗 Link Login: " . route('login') . "\n"
                   . "👤 Username: *" . $newKhatib['username'] . "*\n"
                   . "🔑 Password: *password123*\n\n"
                   . "Mohon untuk segera login dan memperbarui password Anda demi keamanan akun.\n\n"
                   . "Terima kasih.\n"
                   . "Wassalamu'alaikum Wr. Wb.";
        $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . rawurlencode($waMessage);
    @endphp
    <div class="content-card" style="border-left: 5px solid #25D366; background-color: rgba(37, 211, 102, 0.05); margin-bottom: 20px; padding: 20px;">
        <div style="display: flex; align-items: flex-start; gap: 15px;">
            <div style="background-color: #25D366; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px; font-weight: bold;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
            </div>
            <div style="flex-grow: 1;">
                <h4 style="margin: 0 0 5px 0; color: #1E293B; font-weight: 800; font-size: 16px;">Kirim Akses Login Ustad Baru</h4>
                <p style="margin: 0 0 15px 0; color: #64748B; font-size: 13px;">
                    Data Ustad <strong>{{ $newKhatib['nama'] }}</strong> berhasil ditambahkan. Anda dapat langsung mengirimkan kredensial akun login ke nomor WhatsApp Ustad bersangkutan.
                </p>
                <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 12px 16px; margin-bottom: 15px; font-size: 13px; max-width: 300px;">
                    <div style="margin-bottom: 5px;"><strong>Username:</strong> <code>{{ $newKhatib['username'] }}</code></div>
                    <div><strong>Password:</strong> <code>password123</code></div>
                </div>
                <a href="{{ $waUrl }}" target="_blank" class="btn btn-whatsapp" style="display: inline-flex; width: auto; font-size: 13px; padding: 10px 18px; gap: 8px; border-radius: var(--radius-sm); text-decoration: none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                    Kirim Akses via WhatsApp
                </a>
            </div>
        </div>
    </div>
@endif

<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 25px;">
        <!-- Search bar -->
        <form action="{{ route('admin.khatib.index') }}" method="GET" class="search-box">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NBM..." class="search-input">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </form>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Export Excel Button -->
            <a href="{{ route('admin.khatib.export', ['search' => $search]) }}" class="btn btn-secondary" style="width: auto; padding: 10px 16px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Export Excel</span>
            </a>

            <!-- Download PDF Button -->
            <a href="{{ route('admin.khatib.cetak', ['search' => $search]) }}" target="_blank" class="btn btn-secondary" style="width: auto; padding: 10px 16px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak PDF</span>
            </a>

            <!-- Tambah button -->
            <a href="{{ route('admin.khatib.create') }}" class="btn btn-primary btn-add">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Data
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NBM</th>
                    <th>Nama Khatib</th>
                    <th>Alamat (Privasi)</th>
                    <th>No HP 1 (Privasi)</th>
                    <th>No HP 2 (Privasi)</th>
                    <th>Tgl Lahir</th>
                    <th>Status</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($khatibs as $index => $k)
                    @php
                        // Privacy masking logic
                        $maskedAlamat = strlen($k->alamat) > 10 ? substr($k->alamat, 0, 8) . '***' : $k->alamat;
                        $maskedNoHp1 = strlen($k->no_hp) > 6 ? substr($k->no_hp, 0, 4) . '****' . substr($k->no_hp, -3) : $k->no_hp;
                        $maskedNoHp2 = $k->no_hp_2 
                            ? (strlen($k->no_hp_2) > 6 ? substr($k->no_hp_2, 0, 4) . '****' . substr($k->no_hp_2, -3) : $k->no_hp_2)
                            : '-';
                    @endphp
                    <tr>
                        <td>{{ $khatibs->firstItem() + $index }}</td>
                        <td><code style="font-weight: bold; color: var(--text-dark);">{{ $k->nbm ?? '-' }}</code></td>
                        <td style="font-weight: 700;">
                            <!-- Link to Khatib detail page -->
                            <a href="{{ route('admin.khatib.show', $k->id) }}" style="color: var(--primary); text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                {{ $k->nama }}
                            </a>
                        </td>
                        <td><span title="{{ $k->alamat }}">{{ $maskedAlamat }}</span></td>
                        <td><span>{{ $maskedNoHp1 }}</span></td>
                        <td><span>{{ $maskedNoHp2 }}</span></td>
                        <td>{{ $k->tanggal_lahir ? $k->tanggal_lahir->translatedFormat('d M Y') : '-' }}</td>
                        <td>
                            <span class="badge {{ $k->status === 'Normal' ? 'badge-active' : ($k->status === 'Off' ? 'badge-inactive' : 'badge-warning') }}">
                                {{ $k->status }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons" style="justify-content: center;">
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $k->no_hp);
                                    if (strpos($cleanPhone, '0') === 0) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                    
                                    $waMessage = "Assalamu'alaikum Wr. Wb.\n\n"
                                               . "Yth. " . $k->nama . ",\n"
                                               . "Berikut adalah informasi akun Anda untuk mengakses website SIKJ CMM:\n\n"
                                               . "🔗 Link Login: " . route('login') . "\n"
                                               . "👤 Username: *" . ($k->user->username ?? '') . "*\n"
                                               . "🔑 Password: *password123*\n\n"
                                               . "Mohon untuk segera login dan memperbarui password Anda demi keamanan akun.\n\n"
                                               . "Terima kasih.\n"
                                               . "Wassalamu'alaikum Wr. Wb.";
                                    $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . rawurlencode($waMessage);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="btn-action" title="Kirim Kredensial WA" style="color: #25D366;" onmouseover="this.style.color='#128C7E'; this.style.backgroundColor='rgba(37, 211, 102, 0.1)';" onmouseout="this.style.color='#25D366'; this.style.backgroundColor='';">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.khatib.show', $k->id) }}" class="btn-action" title="Detail Riwayat & Kehadiran" style="color: var(--primary);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('admin.khatib.edit', $k->id) }}" class="btn-action" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.khatib.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data khatib ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Tidak ada data khatib ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($khatibs->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $khatibs->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
