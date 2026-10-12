<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Penyimpanan Cache Bawaan (Default Cache Store)
    |--------------------------------------------------------------------------
    |
    | Opsi ini mengontrol penyimpanan cache bawaan yang akan digunakan oleh
    | framework. Koneksi ini digunakan jika koneksi lain tidak ditentukan secara
    | eksplisit saat menjalankan operasi cache di dalam aplikasi.
    |
    */

    'default' => env('CACHE_STORE', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Penyimpanan Cache (Cache Stores)
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat mendefinisikan semua "penyimpanan" (stores) cache untuk
    | aplikasi Anda beserta driver-nya. Anda bahkan dapat mendefinisikan beberapa
    | penyimpanan untuk driver cache yang sama guna mengelompokkan jenis item yang disimpan.
    |
    | Driver yang didukung: "array", "database", "file", "memcached",
    |                    "redis", "dynamodb", "storage", "octane",
    |                    "session", "failover", "null"
    |
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'storage' => [
            'driver' => 'storage',
            'disk' => env('CACHE_STORAGE_DISK'),
            'path' => env('CACHE_STORAGE_PATH', 'framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],

        'failover' => [
            'driver' => 'failover',
            'stores' => [
                'database',
                'array',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Awalan Kunci Cache (Cache Key Prefix)
    |--------------------------------------------------------------------------
    |
    | Saat menggunakan penyimpanan cache APC, database, memcached, Redis, dan DynamoDB,
    | mungkin ada aplikasi lain yang menggunakan cache yang sama. Untuk alasan itu,
    | Anda dapat memberikan awalan (prefix) pada setiap kunci cache untuk menghindari bentrokan.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-cache-'),

    /*
    |--------------------------------------------------------------------------
    | Kelas yang Dapat Diserialisasi (Serializable Classes)
    |--------------------------------------------------------------------------
    |
    | Nilai ini menentukan kelas-kelas yang dapat di-unserialize dari penyimpanan cache.
    | Secara bawaan, tidak ada kelas PHP yang akan di-unserialize dari cache Anda
    | untuk mencegah serangan gadget chain jika APP_KEY Anda bocor.
    |
    */

    'serializable_classes' => false,

];
