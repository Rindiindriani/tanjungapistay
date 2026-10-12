<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disk Sistem Berkas Bawaan (Default Filesystem Disk)
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan disk sistem berkas (filesystem disk) bawaan
    | yang harus digunakan oleh framework. Disk "local", serta berbagai disk
    | berbasis cloud tersedia untuk aplikasi Anda sebagai tempat penyimpanan berkas.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Disk Sistem Berkas (Filesystem Disks)
    |--------------------------------------------------------------------------
    |
    | Di bawah ini Anda dapat mengonfigurasi sebanyak mungkin disk sistem berkas
    | yang diperlukan, dan Anda bahkan dapat mengonfigurasi beberapa disk untuk driver yang sama.
    | Contoh-contoh untuk sebagian besar driver penyimpanan yang didukung dikonfigurasi di sini sebagai referensi.
    |
    | Driver yang didukung: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Tautan Simbolis (Symbolic Links)
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat mengonfigurasi tautan simbolis (symbolic links) yang akan dibuat
    | saat perintah Artisan `storage:link` dijalankan. Key dari array harus berupa
    | lokasi tautan dan valuenya harus berupa target lokasi penyimpanan.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
