<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi Pengelola - Tanjung Api</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #0f172a;
            margin: 0;
            padding: 0;
            color: #e2e8f0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #1e293b;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 1px solid #334155;
        }
        .email-header {
            background-color: #059669;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
        }
        .email-header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .email-body {
            padding: 30px;
            line-height: 1.6;
        }
        .email-body h2 {
            font-size: 18px;
            color: #ffffff;
            margin-top: 0;
        }
        .email-body p {
            font-size: 14px;
            color: #94a3b8;
        }
        .info-box {
            background-color: #064e3b;
            border-left: 4px solid #10b981;
            padding: 14px 18px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .info-box p {
            margin: 0;
            font-size: 13px;
            color: #a7f3d0;
            font-weight: 600;
        }
        .btn-reset {
            display: inline-block;
            background-color: #10b981;
            color: #0f172a !important;
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
            color: #64748b;
            word-break: break-all;
        }
        .email-footer {
            background-color: #0f172a;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #334155;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Panel Pengelola Tanjung Api</h1>
            <p>Akses Internal Sistem</p>
        </div>
        <div class="email-body">
            <h2>Halo, {{ $pengelola->nama_pengelola }}! 🔑</h2>
            <p>Permintaan reset kata sandi telah dibuat untuk akun pengelola Anda (<strong>{{ $pengelola->email }}</strong>).</p>
            <p>Klik tombol di bawah untuk membuat kata sandi baru untuk panel admin:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn-reset">Atur Ulang Kata Sandi Admin</a>
            </div>

            <div class="info-box">
                <p>⏱️ Link ini berlaku selama <strong>60 menit</strong>. Apabila Anda tidak merasa melakukan tindakan ini, harap amankan akun Anda segera.</p>
            </div>

            <p>Atau salin link berikut ke peramban Anda:</p>
            <p class="link-alt"><a href="{{ $resetUrl }}" style="color: #38bdf8;">{{ $resetUrl }}</a></p>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Panel Pengelola Pantai Tanjung Api.
        </div>
    </div>
</body>
</html>
