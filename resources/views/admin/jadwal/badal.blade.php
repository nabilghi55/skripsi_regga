@extends('admin.layouts.admin')

@section('title', 'Manajemen Badal & Ketersediaan')
@section('page_header', 'Manajemen Badal')

@section('content')
<!-- Date Filter Selector -->
<div class="content-card" style="padding: 16px 24px; margin-bottom: 24px;">
    <form action="{{ route('admin.badal.index') }}" method="GET" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
        <input type="hidden" name="type" value="{{ $type }}">
        <div style="font-weight: 700; color: var(--text-dark); font-size: 14px;">Pilih Tanggal Jumat:</div>
        <div style="width: 200px;">
            <input type="date" name="tanggal" value="{{ $date }}" class="form-control" onchange="this.form.submit()" style="font-size: 13px;" required>
        </div>
        <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">
            Hari: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
        </div>
    </form>
</div>

<!-- Choice Boxes -->
<div class="badal-selection-container">
    <a href="{{ route('admin.badal.index', ['type' => 'masjid', 'tanggal' => $date]) }}" class="badal-selection-box {{ $type === 'masjid' ? 'active' : '' }}">
        <div class="badal-selection-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-10 9h3v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h3L12 3z"/></svg>
        </div>
        <div class="badal-selection-title">Jadwal Masjid Kosong</div>
        <div class="badal-selection-desc">Masjid yang belum terjadwal khatibnya pada tanggal terpilih.</div>
    </a>
    <a href="{{ route('admin.badal.index', ['type' => 'khatib', 'tanggal' => $date]) }}" class="badal-selection-box {{ $type === 'khatib' ? 'active' : '' }}">
        <div class="badal-selection-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="badal-selection-title">Khatib Tidak Bertugas</div>
        <div class="badal-selection-desc">Khatib yang tersedia (bebas tugas/tidak bertugas) pada tanggal terpilih.</div>
    </a>
</div>

<!-- Display Table based on Selection -->
<div class="content-card">
    @if($type === 'masjid')
        <div class="card-header-actions" style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Masjid Kosong ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h3>
        </div>

        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode</th>
                        <th>Nama Masjid</th>
                        <th>Alamat (Privasi)</th>
                        <th>No HP 1 (Privasi)</th>
                        <th>No HP 2 (Privasi)</th>
                        <th>Kategori</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($masjidsKosong as $index => $m)
                        @php
                            $maskedAlamat = strlen($m->alamat) > 10 ? substr($m->alamat, 0, 8) . '***' : $m->alamat;
                            $maskedNoHp1 = $m->no_hp_1 ? substr($m->no_hp_1, 0, 4) . '****' . substr($m->no_hp_1, -3) : '-';
                            $maskedNoHp2 = $m->no_hp_2 ? substr($m->no_hp_2, 0, 4) . '****' . substr($m->no_hp_2, -3) : '-';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><code>{{ $m->kode_masjid ?? '-' }}</code></td>
                            <td style="font-weight: 700;">{{ $m->nama }}</td>
                            <td>{{ $maskedAlamat }}</td>
                            <td>{{ $maskedNoHp1 }}</td>
                            <td>{{ $maskedNoHp2 }}</td>
                            <td>
                                <span class="badge {{ $m->kategori === 'Masjid Muhammadiyah' ? 'badge-active' : 'badge-warning' }}">
                                    {{ $m->kategori }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="{{ route('admin.badal.tambahJadwalForm', ['masjid_id' => $m->id, 'tanggal' => $date]) }}" class="btn btn-primary" style="font-size: 11px; padding: 6px 12px; width: auto; font-weight: bold; border-radius: var(--radius-sm);">
                                        Isi Badal
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Semua masjid sudah memiliki jadwal khotbah pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="card-header-actions" style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Ketersediaan & Status Khatib ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})</h3>
        </div>

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
                        <th>Status Ketersediaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khatibsTidakBertugas as $index => $k)
                        @php
                            $maskedAlamat = strlen($k->alamat) > 10 ? substr($k->alamat, 0, 8) . '***' : $k->alamat;
                            $maskedNoHp1 = strlen($k->no_hp) > 6 ? substr($k->no_hp, 0, 4) . '****' . substr($k->no_hp, -3) : $k->no_hp;
                            $maskedNoHp2 = $k->no_hp_2 
                                ? (strlen($k->no_hp_2) > 6 ? substr($k->no_hp_2, 0, 4) . '****' . substr($k->no_hp_2, -3) : $k->no_hp_2)
                                : '-';

                            // Determine status color class
                            $statusClass = 'row-status-normal';
                            if ($k->status === 'Udzur / Sakit') {
                                $statusClass = 'row-status-udzur';
                            } elseif ($k->status === 'Off') {
                                $statusClass = 'row-status-off';
                            } elseif ($k->status === 'Tugas / Izin') {
                                $statusClass = 'row-status-tugas';
                            }
                        @endphp
                        <tr class="{{ $statusClass }}">
                            <td>{{ $index + 1 }}</td>
                            <td><code>{{ $k->nbm ?? '-' }}</code></td>
                            <td style="font-weight: 700;">{{ $k->nama }}</td>
                            <td>{{ $maskedAlamat }}</td>
                            <td>{{ $maskedNoHp1 }}</td>
                            <td>{{ $maskedNoHp2 }}</td>
                            <td>
                                <span class="badge {{ $k->status === 'Normal' ? 'badge-active' : ($k->status === 'Off' ? 'badge-inactive' : 'badge-warning') }}" style="border: 1px solid rgba(0,0,0,0.05);">
                                    {{ $k->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Semua khatib sedang bertugas pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
