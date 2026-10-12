<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Driver Sesi Bawaan (Default Session Driver)
    |--------------------------------------------------------------------------
    |
    | Opsi ini menentukan driver sesi bawaan yang digunakan untuk permintaan (requests)
    | yang masuk. Laravel mendukung berbagai opsi penyimpanan untuk menyimpan data sesi.
    | Penyimpanan database adalah pilihan bawaan yang sangat baik.
    |
    | Yang didukung: "file", "cookie", "database", "memcached",
    |                "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Masa Pakai Sesi (Session Lifetime)
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan jumlah menit yang Anda inginkan agar sesi
    | diperbolehkan tetap idle sebelum kadaluwarsa. Jika Anda ingin sesi
    | langsung kadaluwarsa saat peramban (browser) ditutup, Anda dapat
    | menentukannya melalui opsi konfigurasi expire_on_close.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Enkripsi Sesi (Session Encryption)
    |--------------------------------------------------------------------------
    |
    | Opsi ini memungkinkan Anda menentukan dengan mudah bahwa semua data sesi Anda
    | harus dienkripsi sebelum disimpan. Semua enkripsi dilakukan secara otomatis
    | oleh Laravel dan Anda dapat menggunakan sesi seperti biasa.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Lokasi Berkas Sesi (Session File Location)
    |--------------------------------------------------------------------------
    |
    | Saat menggunakan driver sesi "file", berkas sesi ditempatkan di disk.
    | Lokasi penyimpanan bawaan didefinisikan di sini; namun, Anda bebas
    | menyediakan lokasi lain tempat berkas tersebut harus disimpan.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Koneksi Database Sesi (Session Database Connection)
    |--------------------------------------------------------------------------
    |
    | Saat menggunakan driver sesi "database" atau "redis", Anda dapat menentukan
    | koneksi yang harus digunakan untuk mengelola sesi ini. Ini harus sesuai
    | dengan koneksi dalam opsi konfigurasi database Anda.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Tabel Database Sesi (Session Database Table)
    |--------------------------------------------------------------------------
    |
    | Saat menggunakan driver sesi "database", Anda dapat menentukan tabel yang
    | digunakan untuk menyimpan sesi. Tentu saja, nilai bawaan yang wajar telah didefinisikan
    | untuk Anda; namun, Anda dipersilakan mengubahnya ke tabel lain.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Penyimpanan Cache Sesi (Session Cache Store)
    |--------------------------------------------------------------------------
    |
    | Saat menggunakan salah satu backend sesi berbasis cache framework, Anda dapat
    | mendefinisikan penyimpanan cache yang harus digunakan untuk menyimpan data sesi
    | antar permintaan. Ini harus cocok dengan salah satu penyimpanan cache Anda yang terdefinisi.
    |
    | Memengaruhi: "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Lotere Pembersihan Sesi (Session Sweeping Lottery)
    |--------------------------------------------------------------------------
    |
    | Beberapa driver sesi harus secara manual membersihkan lokasi penyimpanannya untuk
    | menghapus sesi lama dari penyimpanan. Berikut adalah peluang terjadinya pembersihan
    | pada permintaan yang diberikan. Secara bawaan, peluangnya adalah 2 dari 100.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Nama Cookie Sesi (Session Cookie Name)
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat mengubah nama cookie sesi yang dibuat oleh framework.
    | Biasanya, Anda tidak perlu mengubah nilai ini karena tidak memberikan
    | peningkatan keamanan yang signifikan.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')).'-session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Jalur Cookie Sesi (Session Cookie Path)
    |--------------------------------------------------------------------------
    |
    | Jalur cookie sesi menentukan jalur (path) yang mana cookie tersebut dianggap
    | tersedia. Biasanya, ini adalah jalur root aplikasi Anda, tetapi Anda bebas
    | mengonfigurasinya jika diperlukan.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Domain Cookie Sesi (Session Cookie Domain)
    |--------------------------------------------------------------------------
    |
    | Nilai ini menentukan domain dan subdomain yang mana cookie sesi dapat diakses.
    | Secara bawaan, cookie akan tersedia untuk domain root tanpa subdomain.
    | Biasanya, nilai ini tidak perlu diubah.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Cookie Khusus HTTPS (HTTPS Only Cookies)
    |--------------------------------------------------------------------------
    |
    | Dengan mengatur opsi ini ke true, cookie sesi hanya akan dikirim kembali
    | ke server jika peramban memiliki koneksi HTTPS. Ini mencegah cookie dikirimkan
    | kepada Anda ketika tidak dapat dilakukan secara aman.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | Hanya Akses HTTP (HTTP Access Only)
    |--------------------------------------------------------------------------
    |
    | Mengatur nilai ini ke true akan mencegah JavaScript mengakses nilai cookie,
    | dan cookie hanya dapat diakses melalui protokol HTTP. Sangat kecil kemungkinan
    | Anda perlu menonaktifkan opsi ini.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Cookie Same-Site (Same-Site Cookies)
    |--------------------------------------------------------------------------
    |
    | Opsi ini menentukan bagaimana cookie Anda berprilaku saat terjadi permintaan lintas situs
    | (cross-site requests), dan dapat digunakan untuk memitigasi serangan CSRF. Secara bawaan,
    | kami mengatur nilai ini ke "lax" untuk mengizinkan permintaan lintas situs yang aman.
    |
    | Lihat: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Yang didukung: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Cookie Terpartisi (Partitioned Cookies)
    |--------------------------------------------------------------------------
    |
    | Mengatur nilai ini ke true akan mengaitkan cookie ke situs tingkat atas untuk
    | konteks lintas situs. Cookie terpartisi diterima oleh peramban ketika ditandai
    | "secure" dan atribut Same-Site diatur ke "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Serialisasi Sesi (Session Serialization)
    |--------------------------------------------------------------------------
    |
    | Nilai ini mengontrol strategi serialisasi untuk data sesi, yang secara bawaan
    | menggunakan JSON. Mengatur opsi ini ke "php" memungkinkan penyimpanan objek PHP
    | dalam sesi tetapi dapat membuat aplikasi rentan terhadap serangan serialisasi
    | "gadget chain" jika APP_KEY bocor.
    |
    | Yang didukung: "json", "php"
    |
    */

    'serialization' => 'json',

];
