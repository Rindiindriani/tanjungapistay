<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - Tanjung Api</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background-color: #059669;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }
        .email-header p {
            margin: 5px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
            line-height: 1.6;
        }
        .email-body h2 {
            font-size: 18px;
            color: #0f172a;
            margin-top: 0;
        }
        .email-body p {
            font-size: 14px;
            color: #475569;
        }
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 14px 18px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .info-box p {
            margin: 0;
            font-size: 13px;
            color: #1e40af;
            font-weight: 600;
        }
        .btn-reset {
            display: inline-block;
            background-color: #059669;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .link-alt {
            font-size: 12px;
            color: #94a3b8;
            word-break: break-all;
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 20px 30px;
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
            <p>Kawasan Konservasi Penyu, Sebubus - Paloh</p>
        </div>
        <div class="email-body">
            <h2>Halo, {{ $tamu->nama }}! 👋</h2>
            <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun tamu Anda di Tanjung Api.</p>
            <p>Klik tombol di bawah ini untuk membuat kata sandi baru:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn-reset">Atur Ulang Kata Sandi</a>
            </div>

            <div class="info-box">
                <p>⏱️ Link reset kata sandi ini berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa meminta reset kata sandi, abaikan email ini dan kata sandi Anda tidak akan berubah.</p>
            </div>

            <p>Jika tombol di atas tidak bisa diklik, salin dan tempel link berikut pada peramban web Anda:</p>
            <p class="link-alt"><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Penginapan Pantai Tanjung Api. All rights reserved.
        </div>
    </div>
</body>
</html>
