<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan - Tanjung Api</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 40px;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
            font-weight: 500;
        }
        .header .divider {
            height: 3px;
            background-color: #333;
            margin-top: 15px;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
        }
        .meta-info {
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #444;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .total-row {
            font-weight: bold;
            background-color: #eaeaea !important;
        }
        .total-price {
            color: #10b981;
            font-weight: 800;
        }
        .signature-container {
            margin-top: 50px;
            float: right;
            text-align: center;
            width: 250px;
        }
        .signature-date {
            margin-bottom: 60px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            body {
                margin: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Keuangan Reservasi Penginapan</h1>
        <h1>Pantai Tanjung Api</h1>
        <p>Kawasan Konservasi Penyu, Desa Sebubus, Kecamatan Paloh, Kabupaten Sambas</p>
        <div class="divider"></div>
    </div>

    <div class="meta-info">
        @if($startDate && $endDate)
            <span>Periode Laporan: {{ date('d F Y', strtotime($startDate)) }} s.d {{ date('d F Y', strtotime($endDate)) }}</span>
        @else
            <span>Periode Laporan: Semua Periode (Hingga Hari Ini)</span>
        @endif
        <span style="float: right;">Tanggal Cetak: {{ date('d F Y') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 12%;">ID Booking</th>
                <th style="width: 20%;">Nama Pelanggan</th>
                <th style="width: 22%;">Unit Akomodasi</th>
                <th style="width: 12%; text-align: center;">Jml Tamu</th>
                <th style="width: 13%;">Tgl Check-In</th>
                <th style="width: 13%;">Tgl Check-Out</th>
                <th style="width: 14%; text-align: right;">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">#TA-{{ str_pad($report->id_reservasi, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $report->tamu->nama }}</td>
                    <td>{{ $report->penginapan->nama_unit }} ({{ $report->jumlah_kamar }} Qty)</td>
                    <td style="text-align: center; font-weight: bold;">{{ $report->jumlah_tamu }} Orang</td>
                    <td>{{ date('d M Y', strtotime($report->tanggal_checkin)) }}</td>
                    <td>{{ date('d M Y', strtotime($report->tanggal_checkout)) }}</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($report->pembayaran->total_pembayaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999; padding: 20px;">Tidak ada transaksi pembayaran lunas dalam periode ini.</td>
                </tr>
            @endforelse
            
            @if(!$reports->isEmpty())
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; font-weight: bold; font-size: 12px; text-transform: uppercase;">Total Pengunjung:</td>
                    <td style="text-align: center; font-weight: bold; color: #2563eb;">{{ number_format($totalPengunjung, 0, ',', '.') }} Orang</td>
                    <td colspan="2" style="text-align: right; font-weight: bold; font-size: 12px; text-transform: uppercase;">Total Pemasukan Bersih:</td>
                    <td style="text-align: right;" class="total-price">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>


    <div class="signature-container">
        <div class="signature-date">Sebubus, {{ date('d F Y') }}</div>
        <div style="margin-bottom: 60px;">Mengetahui,<br><strong>Pengelola Tanjung Api</strong></div>
        <div class="signature-name">Rindi Admin</div>
        <div style="font-size: 11px; color: #666; margin-top: 2px;">Administrator Panel</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
