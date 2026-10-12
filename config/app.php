<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nama Aplikasi
    |--------------------------------------------------------------------------
    |
    | Nilai ini adalah nama aplikasi Anda, yang akan digunakan ketika
    | framework perlu menampilkan nama aplikasi dalam notifikasi atau
    | elemen antarmuka pengguna (UI) lainnya.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Lingkungan Aplikasi (Environment)
    |--------------------------------------------------------------------------
    |
    | Nilai ini menentukan "lingkungan" tempat aplikasi Anda saat ini
    | berjalan. Nilai ini dapat menentukan bagaimana Anda mengonfigurasi
    | berbagai layanan yang digunakan aplikasi. Atur ini di file ".env" Anda.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Mode Debug Aplikasi
    |--------------------------------------------------------------------------
    |
    | Saat aplikasi Anda berada dalam mode debug, pesan kesalahan terperinci
    | beserta stack trace akan ditampilkan pada setiap error yang terjadi.
    | Jika dinonaktifkan, halaman error generik sederhana yang akan ditampilkan.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL Aplikasi
    |--------------------------------------------------------------------------
    |
    | URL ini digunakan oleh konsol untuk menghasilkan URL secara benar saat
    | menggunakan alat baris perintah Artisan. Anda harus mengatur ini ke root
    | aplikasi agar dapat digunakan dalam perintah-perintah Artisan.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Zona Waktu Aplikasi
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan zona waktu bawaan untuk aplikasi Anda, yang
    | akan digunakan oleh fungsi tanggal dan waktu PHP. Zona waktu ini
    | secara bawaan diatur ke "UTC" karena cocok untuk sebagian besar kasus penggunaan.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Bahasa / Lokale Aplikasi
    |--------------------------------------------------------------------------
    |
    | Lokale aplikasi menentukan bahasa/lokale bawaan yang akan digunakan oleh
    | metode penerjemahan / lokalisasi Laravel. Opsi ini dapat diatur ke
    | locale mana pun yang ingin Anda sediakan string terjemahannya.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Kunci Enkripsi
    |--------------------------------------------------------------------------
    |
    | Kunci ini digunakan oleh layanan enkripsi Laravel dan harus diatur berupa
    | string acak 32 karakter untuk memastikan semua nilai terenkripsi tetap aman.
    | Anda harus mengatur kunci ini sebelum menyebarkan (deploy) aplikasi.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Driver Mode Pemeliharaan (Maintenance Mode)
    |--------------------------------------------------------------------------
    |
    | Opsi konfigurasi ini menentukan driver yang digunakan untuk mengelola status
    | "mode pemeliharaan" (maintenance mode) Laravel. Driver "cache" memungkinkan
    | mode pemeliharaan dikontrol di beberapa mesin sekaligus.
    |
    | Driver yang didukung: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
