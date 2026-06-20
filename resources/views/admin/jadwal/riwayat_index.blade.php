@extends('admin.layouts.admin')

@section('title', 'Riwayat Badal')
@section('page_header', 'Riwayat Badal')

@section('content')
<div class="content-card">
    <div class="card-header-actions" style="margin-bottom: 25px;">
        <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Riwayat Perubahan & Badal</h3>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Nama Khatib Utama</th>
                    <th>Masjid</th>
                    <th>Status</th>
                    <th>Khatib Pengganti (Badal)</th>
                    <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatBadals as $index => $r)
                    <tr>
                        <td>{{ $riwayatBadals->firstItem() + $index }}</td>
                        <td style="font-weight: 600; color: var(--primary);">
                            {{ $r->tanggal_pengajuan->translatedFormat('d F Y') }}
                        </td>
                        <td style="font-weight: 700;">{{ $r->khatib->nama }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $r->masjid->nama }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $r->masjid->kecamatan }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $r->status === 'Sudah Terverifikasi' ? 'badge-active' : 'badge-warning' }}">
                                {{ $r->status }}
                            </span>
                        </td>
                        <td style="font-weight: 600; color: var(--success);">
                            {{ $r->pengganti ? $r->pengganti->nama : 'Belum Ada (Menunggu Badal)' }}
                        </td>
                        <td>
                            <div class="action-buttons" style="justify-content: center;">
                                <a href="{{ route('admin.riwayatBadal.edit', $r->id) }}" class="btn-action" title="Edit Riwayat">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form action="{{ route('admin.riwayatBadal.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat badal ini?')">
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
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Tidak ada riwayat pengajuan badal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($riwayatBadals->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $riwayatBadals->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
