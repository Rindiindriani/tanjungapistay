<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            padding: 32px 24px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .email-header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 24px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .message-box {
            background-color: #f1f5f9;
            border-left: 4px solid #059669;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
            color: #334155;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }
        .info-table th {
            background-color: #f8fafc;
            color: #64748b;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }
        .info-table td.label {
            color: #64748b;
            font-weight: 600;
            width: 35%;
        }
        .info-table td.value {
            font-weight: 700;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge-confirmed { background-color: #d1fae5; color: #065f46; }
        .badge-postponed { background-color: #fef3c7; color: #92400e; }
        .badge-cancelled { background-color: #fee2e2; color: #991b1b; }
        .badge-pending { background-color: #fff7ed; color: #9a3412; }
        .btn-container {
            text-align: center;
            margin: 28px 0 10px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: #059669;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Pantai Tanjung Api</h1>
            <p>Sistem Informasi & Reservasi Penginapan</p>
        </div>

        <div class="email-body">
            <div class="greeting">Halo, {{ $booking->tamu->nama }}! 👋</div>
            
            <p style="font-size: 14px; margin: 0;">
                @if($type === 'checkin_reminder')
                    Ini adalah pengingat bahwa jadwal check-in penginapan Anda di Pantai Tanjung Api akan dilaksanakan <strong>besok hari</strong>.
                @elseif($type === 'payment_confirmation')
                    Terima kasih! Pembayaran untuk reservasi Anda di Pantai Tanjung Api telah berhasil diverifikasi.
                @else
                    Terdapat pembaruan informasi mengenai reservasi penginapan Anda dengan ID <strong>#TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</strong>.
                @endif
            </p>

            @if($customMessage)
                <div class="message-box">
                    <strong>Pesan Pengelola:</strong><br>
                    {{ $customMessage }}
                </div>
            @endif

            <table class="info-table">
                <thead>
                    <tr>
                        <th colspan="2">Rincian Reservasi #TA-{{ str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">Unit Penginapan</td>
                        <td class="value">{{ $booking->penginapan->nama_unit ?? 'Unit' }} ({{ $booking->jumlah_kamar }} Unit)</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Check-In</td>
                        <td class="value">{{ date('d M Y', strtotime($booking->tanggal_checkin)) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Check-Out</td>
                        <td class="value">{{ date('d M Y', strtotime($booking->tanggal_checkout)) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status Reservasi</td>
                        <td class="value">
                            @if($booking->status === 'confirmed')
                                <span class="badge badge-confirmed">Aktif / Terkonfirmasi</span>
                            @elseif($booking->status === 'postponed')
                                <span class="badge badge-postponed">Ditunda (Maks. 1 Bulan)</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="badge badge-cancelled">Dibatalkan</span>
                            @else
                                <span class="badge badge-pending">Menunggu Pembayaran</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Total Pembayaran</td>
                        <td class="value" style="color: #059669;">Rp {{ number_format($booking->pembayaran->total_pembayaran ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @if($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp')
    <tr>
        <td class="label">Skema Bayar</td>
        <td class="value">Sudah DP 50% dan akan dibayar 50% lagi ketika berada di tempat (Sisa Rp {{ number_format($booking->pembayaran->sisa_pembayaran, 0, ',', '.') }} saat Check-in)</td>
    </tr>
@endif
                </tbody>
            </table>

            <div class="btn-container">
                <a href="{{ route('bookings.show', $booking->id_reservasi) }}" class="btn">Lihat Rincian Reservasi</a>
            </div>
        </div>

        <div class="email-footer">
            <p style="margin: 0 0 6px 0;"><strong>Pantai Tanjung Api - Wisata & Konservasi Penyu</strong></p>
            <p style="margin: 0;">Desa Sebubus, Kecamatan Paloh, Kabupaten Sambas, Kalimantan Barat</p>
        </div>
    </div>
</body>
</html>
