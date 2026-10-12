<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Nilai Bawaan Autentikasi (Authentication Defaults)
    |--------------------------------------------------------------------------
    |
    | Opsi ini menentukan "guard" autentikasi dan "broker" atur ulang kata sandi
    | bawaan untuk aplikasi Anda. Anda dapat mengubah nilai-nilai ini sesuai
    | kebutuhan, namun ini adalah awal yang sempurna untuk sebagian besar aplikasi.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guard Autentikasi (Authentication Guards)
    |--------------------------------------------------------------------------
    |
    | Selanjutnya, Anda dapat mendefinisikan setiap guard autentikasi untuk aplikasi
    | Anda. Tentu saja, konfigurasi bawaan yang hebat telah didefinisikan untuk Anda
    | yang memanfaatkan penyimpanan sesi (session storage) ditambah penyedia pengguna Eloquent.
    |
    | Semua guard autentikasi memiliki penyedia pengguna (user provider), yang mendefinisikan
    | bagaimana pengguna secara aktual diambil dari database atau sistem penyimpanan lainnya.
    | Biasanya, Eloquent yang digunakan.
    |
    | Yang didukung: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Penyedia Pengguna (User Providers)
    |--------------------------------------------------------------------------
    |
    | Semua guard autentikasi memiliki penyedia pengguna, yang mendefinisikan bagaimana
    | pengguna secara aktual diambil dari database atau sistem penyimpanan lainnya yang
    | digunakan oleh aplikasi. Biasanya, Eloquent yang digunakan.
    |
    | Jika Anda memiliki beberapa tabel atau model pengguna, Anda dapat mengonfigurasi
    | beberapa penyedia untuk merepresentasikan model / tabel tersebut. Penyedia ini
    | kemudian dapat ditetapkan ke guard autentikasi tambahan yang Anda definisikan.
    |
    | Yang didukung: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atur Ulang Kata Sandi (Resetting Passwords)
    |--------------------------------------------------------------------------
    |
    | Opsi konfigurasi ini menentukan perilaku fungsi atur ulang kata sandi Laravel,
    | termasuk tabel yang digunakan untuk menyimpan token dan penyedia pengguna yang
    | dipanggil untuk mengambil data pengguna secara aktual.
    |
    | Waktu kadaluwarsa (expire) adalah jumlah menit setiap token atur ulang dianggap valid.
    | Fitur keamanan ini menjaga token agar berumur pendek sehingga memiliki lebih sedikit
    | waktu untuk ditebak. Anda dapat mengubahnya sesuai kebutuhan.
    |
    | Pengaturan pembatasan (throttle) adalah jumlah detik yang harus ditunggu pengguna
    | sebelum menghasilkan token atur ulang kata sandi berikutnya. Ini mencegah pengguna
    | menghasilkan sejumlah besar token atur ulang kata sandi secara cepat.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenggat Waktu Konfirmasi Kata Sandi (Password Confirmation Timeout)
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat mendefinisikan jumlah detik sebelum jendela konfirmasi kata sandi
    | kadaluwarsa dan pengguna diminta untuk memasukkan kembali kata sandi mereka melalui
    | layar konfirmasi. Secara bawaan, tenggat waktu ini berlangsung selama tiga jam.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
