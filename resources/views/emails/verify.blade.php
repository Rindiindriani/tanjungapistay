<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Akun Tanjung Api</title>
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
        .warning-box {
            background-color: #fffbe6;
            border-left: 4px solid #f59e0b;
            padding: 14px 18px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .warning-box p {
            margin: 0;
            font-size: 13px;
            color: #b45309;
            font-weight: 600;
        }
        .btn-verify {
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
            <p>Terima kasih telah mendaftar di sistem informasi dan reservasi penginapan Pantai Tanjung Api.</p>
            <p>Untuk menyelesaikan pendaftaran dan mengaktifkan akun Anda secara penuh, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="btn-verify">Verifikasi Email Saya</a>
            </div>

            <div class="warning-box">
                <p>⚠️ PENTING: Anda harus mengklik link verifikasi di atas dalam waktu <strong>3 Hari</strong>. Jika tidak diverifikasi dalam 3 hari, akun Anda akan terhapus secara otomatis oleh sistem.</p>
            </div>

            <p>Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin dan menempelkan link berikut pada peramban web Anda:</p>
            <p class="link-alt"><a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a></p>

            <p>Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.</p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Penginapan Pantai Tanjung Api. All rights reserved.
        </div>
    </div>
</body>
</html>
