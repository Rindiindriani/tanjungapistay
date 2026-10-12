<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pengirim Email Bawaan (Default Mailer)
    |--------------------------------------------------------------------------
    |
    | Opsi ini mengontrol pengirim email bawaan (default mailer) yang digunakan
    | untuk mengirim semua pesan email kecuali pengirim lain ditentukan secara
    | eksplisit saat mengirim pesan. Semua mailer tambahan dapat dikonfigurasi
    | di dalam array "mailers". Contoh setiap jenis mailer telah disediakan.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Mailer (Mailer Configurations)
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat mengonfigurasi semua mailer yang digunakan oleh aplikasi Anda
    | beserta pengaturannya masing-masing. Beberapa contoh telah dikonfigurasi untuk
    | Anda dan Anda bebas menambahkan mailer sendiri sesuai kebutuhan aplikasi Anda.
    |
    | Laravel mendukung berbagai driver "transport" email yang dapat digunakan
    | saat mengirimkan email. Anda dapat menentukan mana yang Anda gunakan untuk
    | mailer Anda di bawah ini. Anda juga dapat menambahkan mailer tambahan jika diperlukan.
    |
    | Yang didukung: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |                "postmark", "resend", "log", "array",
    |                "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
            'retry_after' => 60,
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Alamat Pengirim Global ("From" Address)
    |--------------------------------------------------------------------------
    |
    | Anda mungkin ingin semua email yang dikirim oleh aplikasi Anda dikirim dari
    | alamat yang sama. Di sini Anda dapat menentukan nama dan alamat yang
    | digunakan secara global untuk semua email yang dikirim oleh aplikasi Anda.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

];
