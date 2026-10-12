<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Reservasi;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Auto-Cancel Reservasi Kadaluarsa (Belum Bayar > 24 Jam)
|--------------------------------------------------------------------------
| Scheduler ini berjalan setiap menit untuk memeriksa dan membatalkan
| reservasi yang sudah lebih dari 24 jam dalam status "pending" tanpa
| konfirmasi pembayaran.
|
| Jalankan: php artisan schedule:run   (atau setup cron di server)
|--------------------------------------------------------------------------
*/
Schedule::command('bookings:cancel-expired')->everyMinute();

/*
|--------------------------------------------------------------------------
| Auto-Complete Reservasi yang Sudah Checkout
|--------------------------------------------------------------------------
*/
Schedule::call(function () {
    Reservasi::autoCheckout();
})->daily();

