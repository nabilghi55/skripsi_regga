@extends('admin.layouts.admin')

@section('title', 'Jadwal Khotbah')
@section('page_header', 'Jadwal Khotbah')

@section('content')
<!-- WhatsApp Badal Automatic Message Banner -->
@if(session('wa_badal_url'))
    <div class="content-card" style="border-left: 5px solid #25D366; background-color: rgba(37, 211, 102, 0.05); margin-bottom: 20px; padding: 20px;">
        <div style="display: flex; align-items: flex-start; gap: 15px;">
            <div style="background-color: #25D366; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px; font-weight: bold;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
            </div>
            <div style="flex-grow: 1;">
                <h4 style="margin: 0 0 5px 0; color: #1E293B; font-weight: 800; font-size: 16px;">Kirim Pesan Badal via WhatsApp</h4>
                <p style="margin: 0 0 15px 0; color: #64748B; font-size: 13px;">
                    Jadwal Badal untuk Ustad <strong>{{ session('khatib_nama') }}</strong> berhasil dibuat. Silakan klik tombol di bawah untuk mengirim pesan penugasan otomatis ke nomor HP Khatib.
                </p>
                <a href="{{ session('wa_badal_url') }}" target="_blank" class="btn btn-whatsapp" style="display: inline-flex; width: auto; font-size: 13px; padding: 10px 18px; gap: 8px; border-radius: var(--radius-sm); text-decoration: none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                    Kirim Pesan Badal
                </a>
            </div>
        </div>
    </div>
@endif

<div class="content-card">
    <!-- Advanced Filter & Actions Section -->
    <div class="card-header-actions" style="margin-bottom: 25px;">
        <form action="{{ route('admin.jadwal.index') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-grow: 1; flex-wrap: wrap;">
            <div style="flex-grow: 1; min-width: 150px; position: relative;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari masjid atau khatib..." class="form-control" style="padding-left: 40px; font-size: 13px;">
                <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            
            <div style="width: 130px;">
                <select name="periode" class="form-control" onchange="this.form.submit()" style="font-size: 13px;">
                    <option value="">Pilih Periode</option>
                    <option value="1_minggu" {{ $periode === '1_minggu' ? 'selected' : '' }}>1 Minggu</option>
                    <option value="1_bulan" {{ $periode === '1_bulan' ? 'selected' : '' }}>1 Bulan</option>
                    <option value="4_bulan" {{ $periode === '4_bulan' ? 'selected' : '' }}>4 Bulan</option>
                </select>
            </div>

            <div style="width: 140px;">
                <select name="masjid_id" class="form-control" onchange="this.form.submit()" style="font-size: 13px;">
                    <option value="">Pilih Masjid</option>
                    @foreach($masjids as $m)
                        <option value="{{ $m->id }}" {{ $masjidId == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width: 140px;">
                <select name="khatib_id" class="form-control" onchange="this.form.submit()" style="font-size: 13px;">
                    <option value="">Pilih Khatib</option>
                    @foreach($khatibs as $k)
                        <option value="{{ $k->id }}" {{ $khatibId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            @if($search || $periode || $khatibId || $masjidId)
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary" style="width: auto; padding: 10px 14px; font-size: 13px;">Reset</a>
            @endif
        </form>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Export Excel Button -->
            <a href="{{ route('admin.jadwal.export', ['search' => $search, 'periode' => $periode, 'khatib_id' => $khatibId, 'masjid_id' => $masjidId]) }}" class="btn btn-secondary" style="width: auto; padding: 10px 16px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Export Excel</span>
            </a>

            <!-- Download PDF/Cetak Button -->
            <a href="{{ route('admin.jadwal.cetak', ['search' => $search, 'periode' => $periode, 'khatib_id' => $khatibId, 'masjid_id' => $masjidId]) }}" target="_blank" class="btn btn-secondary" style="width: auto; padding: 10px 16px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak PDF</span>
            </a>

            <!-- Tambah button -->
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary btn-add">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal Masehi / Hijriyah</th>
                    <th>Jumat Ke</th>
                    <th>Waktu Khutbah</th>
                    <th>Masjid</th>
                    <th>Khatib</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $index => $j)
                    <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.jadwal.edit', $j->id) }}'" title="Klik untuk mengedit jadwal">
                        <td onclick="event.stopPropagation();">{{ $jadwals->firstItem() + $index }}</td>
                        <td style="font-weight: 600; color: var(--primary);">
                            <div>{{ $j->tanggal->translatedFormat('d F Y') }}</div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ $j->hijri_date }}</div>
                        </td>
                        <td style="font-weight: 700;">Jumat ke-{{ $j->jumat_ke ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($j->waktu_khutbah)->format('H:i') }} WIB</td>
                        <td>
                            <div style="font-weight: 700;">{{ $j->masjid->nama }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $j->masjid->kecamatan }}</div>
                        </td>
                        <td style="font-weight: 600;">{{ $j->khatib->nama }}</td>
                        <td onclick="event.stopPropagation();">
                            <span class="badge {{ $j->status === 'Hadir' || $j->status === 'Aktif' ? 'badge-active' : ($j->status === 'Perubahan Diajukan' ? 'badge-warning' : 'badge-inactive') }}">
                                {{ $j->status }}
                            </span>
                        </td>
                        <td>{{ $j->keterangan ?? '-' }}</td>
                        <td onclick="event.stopPropagation();">
                            <div class="action-buttons" style="justify-content: center;">
                                @if($j->status === 'Perubahan Diajukan')
                                    <!-- Verify / ACC button -->
                                    <form action="{{ route('admin.jadwal.acc', $j->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan perubahan jadwal ini?')">
                                        @csrf
                                        <button type="submit" class="btn-action" title="ACC Perubahan" style="color: var(--success); background-color: var(--success-light);">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.jadwal.edit', $j->id) }}" class="btn-action" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal khotbah ini?')">
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
                            Tidak ada data jadwal ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($jadwals->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $jadwals->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
