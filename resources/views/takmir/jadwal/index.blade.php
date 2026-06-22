@extends('takmir.layouts.takmir')

@section('title', 'Jadwal Masjid')

@section('content')
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
        <div>
            <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin: 0; text-transform: uppercase;">
                JADWAL MASJID BINAAN
            </h3>
            <span style="font-size: 13px; font-weight: bold; color: var(--primary);">
                {{ $masjid ? $masjid->nama : 'Semua Masjid Binaan' }}
            </span>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('takmir.jadwal.cetak', ['periode' => '1_bulan', 'search' => $search]) }}" target="_blank" class="btn btn-secondary" style="font-size: 11px; padding: 8px 14px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; width: auto; height: auto;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                CETAK 1 BULAN
            </a>
            <a href="{{ route('takmir.jadwal.cetak', ['periode' => '4_bulan', 'search' => $search]) }}" target="_blank" class="btn btn-primary" style="font-size: 11px; padding: 8px 14px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; width: auto; height: auto;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                CETAK 4 BULAN
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('takmir.jadwal') }}" method="GET" style="margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="search">Cari Khatib</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}" placeholder="Ketik nama khatib...">
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="periode">Periode</label>
                <select name="periode" id="periode" class="form-control">
                    <option value="">Semua Periode</option>
                    <option value="1_minggu" {{ $periode === '1_minggu' ? 'selected' : '' }}>1 Minggu Terdekat</option>
                    <option value="1_bulan" {{ $periode === '1_bulan' ? 'selected' : '' }}>1 Bulan Terdekat</option>
                    <option value="4_bulan" {{ $periode === '4_bulan' ? 'selected' : '' }}>4 Bulan Terdekat</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="height: 42px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; flex: 1;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>CARI</span>
                </button>
                @if($search || $periode)
                    <a href="{{ route('takmir.jadwal') }}" class="btn btn-secondary" style="height: 42px; width: 42px; display: inline-flex; align-items: center; justify-content: center; padding: 0; text-decoration: none;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">NO</th>
                    <th>TANGGAL</th>
                    <th>NAMA KHOTIB</th>
                    <th style="width: 130px; text-align: center;">STATUS</th>
                    <th>KETERANGAN</th>
                    <th style="width: 140px; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $index => $j)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: var(--text-muted);">
                            {{ $jadwals->firstItem() + $index }}
                        </td>
                        <td>
                            <div style="font-weight: 800; color: var(--text-dark);">
                                {{ $j->tanggal->translatedFormat('l, d F Y') }}
                            </div>
                            <div style="font-size: 11px; font-weight: 600; color: var(--text-muted); margin-top: 2px;">
                                {{ $j->hijri_date }}
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--text-dark);">
                            {{ $j->khatib->nama }}
                        </td>
                        <td style="text-align: center;">
                            @php
                                $badgeClass = 'badge-active';
                                if (in_array(strtolower($j->status), ['izin', 'sakit', 'tidak hadir'])) {
                                    $badgeClass = 'badge-inactive';
                                } elseif (strtolower($j->status) === 'perubahan diajukan') {
                                    $badgeClass = 'badge-warning';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $j->status }}
                            </span>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $j->keterangan ?? '-' }}
                            @if($j->catatan_saran_takmir)
                                <div style="font-size: 12px; margin-top: 6px; color: var(--primary); font-weight: 600; background-color: var(--primary-light); padding: 6px 10px; border-radius: var(--radius-sm); border-left: 3px solid var(--primary);">
                                    <strong>Saran Takmir:</strong> "{{ $j->catatan_saran_takmir }}"
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <button onclick="openSaranModal('{{ route('takmir.jadwal.saran', $j->id) }}', '{{ $j->tanggal->translatedFormat('d F Y') }}', '{{ addslashes($j->catatan_saran_takmir) }}')" class="btn btn-primary btn-detail-sm" style="padding: 6px 10px; font-size: 11px; font-weight: bold; width: auto; height: auto; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                SARAN
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px 10px;">
                            Tidak ada jadwal khotbah yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($jadwals->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $jadwals->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<!-- Modal for suggestions -->
<div class="modal-overlay" id="saran-modal" style="display: none;">
    <div class="modal-card" style="max-width: 400px; padding: 24px; text-align: left;">
        <button class="modal-close" id="saran-close-btn">&times;</button>
        <h3 class="modal-title" style="font-size: 16px; font-weight: 800; margin-bottom: 15px; text-transform: uppercase;">MASUKKAN CATATAN / SARAN</h3>
        <form id="saran-form" method="POST" action="">
            @csrf
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="margin-bottom: 8px;">Jadwal Hari: <span id="saran-date-label" style="font-weight: bold; color: var(--primary);"></span></label>
                <textarea name="catatan_saran_takmir" id="modal_saran_input" rows="4" class="form-control" placeholder="Tulis catatan pelaksanaan sholat jumat atau saran untuk Khatib/Admin di sini..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">SIMPAN</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openSaranModal(actionUrl, currentDate, currentSaran) {
    document.getElementById('saran-form').action = actionUrl;
    document.getElementById('saran-date-label').innerText = currentDate;
    document.getElementById('modal_saran_input').value = currentSaran || '';
    document.getElementById('saran-modal').style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('saran-modal');
    const closeBtn = document.getElementById('saran-close-btn');

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }
});
</script>
@endsection
