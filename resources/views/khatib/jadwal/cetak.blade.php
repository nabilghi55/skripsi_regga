<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Khotbah Jumat - {{ $khatib->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFFFF;
            color: #1E293B;
            padding: 40px;
            margin: 0;
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #1E293B;
            padding-bottom: 20px;
        }

        .report-header h1 {
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 5px 0;
            color: #1E293B;
            text-transform: uppercase;
        }

        .report-header h2 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 10px 0;
            color: #64748B;
        }

        .report-header p {
            font-size: 12px;
            margin: 0;
            color: #94A3B8;
        }

        .report-meta {
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .report-table th {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            padding: 12px 15px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1E293B;
            text-align: left;
        }

        .report-table td {
            border: 1px solid #CBD5E1;
            padding: 12px 15px;
            font-size: 13px;
            color: #334155;
        }

        .report-table tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
        }
        
        .badge-active {
            background-color: #DCFCE7;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }

        .badge-warning {
            background-color: #FEF3C7;
            color: #B45309;
            border: 1px solid #FDE68A;
        }

        .badge-inactive {
            background-color: #FEE2E2;
            color: #B91C1C;
            border: 1px solid #FCA5A5;
        }

        .print-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 9999;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .btn-print {
            background-color: #1B52C0;
            color: #FFFFFF;
        }

        .btn-print:hover {
            background-color: #0D41AC;
        }

        .btn-back {
            background-color: #64748B;
            color: #FFFFFF;
        }

        .btn-back:hover {
            background-color: #475569;
        }

        .report-footer {
            margin-top: 50px;
            text-align: right;
            font-size: 13px;
        }

        .signature-area {
            margin-top: 80px;
            display: inline-block;
            text-align: center;
            border-top: 1px solid #1E293B;
            padding-top: 8px;
            width: 200px;
            font-weight: 700;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
            .print-actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="report-header">
        <h1>Korps Mubaligh Masjid (CMM)</h1>
        <h2>Jadwal Tugas Khutbah Jumat Khatib</h2>
        <p>Alamat: Jl. Candi No. 15, Dau, Malang | Email: info@cmm.or.id</p>
    </div>

    <div class="report-meta">
        <div>
            <strong>Nama Khatib:</strong> {{ $khatib->nama }}<br>
            <strong>NBM:</strong> {{ $khatib->nbm ?? '-' }}
        </div>
        <div>
            <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No</th>
                <th style="width: 180px;">Hari / Tanggal</th>
                <th>Masjid Tujuan</th>
                <th>Alamat Masjid</th>
                <th style="width: 100px;">Waktu</th>
                <th style="width: 120px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $index => $j)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td style="font-weight: 600;">
                        <div>{{ $j->tanggal->translatedFormat('l, d F Y') }}</div>
                        <div style="font-size: 11px; color: #64748B; font-weight: 500; margin-top: 2px;">{{ $j->hijri_date }}</div>
                    </td>
                    <td style="font-weight: 700; color: #1B52C0;">{{ $j->masjid->nama }}</td>
                    <td>{{ $j->masjid->alamat }}</td>
                    <td>{{ \Carbon\Carbon::parse($j->waktu_khutbah)->format('H:i') }} WIB</td>
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
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748B; padding: 30px;">
                        Tidak ada data jadwal tugas khutbah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="report-footer">
        <p>Malang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <p><strong>Khatib yang bertugas,</strong></p>
        <div class="signature-area" style="margin-top: 80px;">
            ( {{ $khatib->nama }} )
        </div>
    </div>

    <!-- Floating Actions for Screen View -->
    <div class="print-actions no-print">
        <a href="{{ route('khatib.jadwalSaya') }}" class="btn btn-back">
            Kembali
        </a>
        <button onclick="window.print();" class="btn btn-print">
            Cetak PDF
        </button>
    </div>

    <script>
        // Trigger print dialog automatically when loaded
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
