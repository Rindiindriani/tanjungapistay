<?php

// Mengimpor kelas-kelas konfigurasi aplikasi dari instansi Laravel 11
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Mengkonfigurasi dan mengembalikan instansi aplikasi Laravel dengan jalur dasar proyek
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Menentukan berkas rute web (routes/web.php)
        web: __DIR__.'/../routes/web.php',
        // Menentukan berkas rute konsol/command (routes/console.php)
        commands: __DIR__.'/../routes/console.php',
        // Menentukan endpoint pemeriksaan kesehatan sistem (/up)
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Mendaftarkan alias kustom untuk middleware autentikasi tamu dan admin
        $middleware->alias([
            'tamu.auth' => \App\Http\Middleware\TamuAuth::class,   // Middleware autentikasi tamu
            'admin.auth' => \App\Http\Middleware\AdminAuth::class, // Middleware autentikasi pengelola/admin
        ]);

        // Pengecualian verifikasi CSRF untuk rute Webhook Midtrans (server-to-server)
        $middleware->validateCsrfTokens(except: [
            'payment/notification',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Menentukan kondisi penanganan pengecualian (exceptions) berbasis JSON untuk API
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
