<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Pengelola (Admin)
        DB::table('pengelola')->insert([
            'id_pengelola' => 1,
            'nama_pengelola' => 'Rindi Admin',
            'email' => 'admin@tanjungapi.com',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seed Penginapan (Lodging Units)
        DB::table('penginapan')->insert([
            [
                'id_unit' => 1,
                'id_pengelola' => 1,
                'nama_unit' => 'Villa Atap Daun',
                'alamat' => 'Pantai Tanjung Api, Desa Sebubus, Paloh',
                'fasilitas' => '1 Kamar Tidur Utama, Kamar Mandi Dalam, Kipas Angin, Teras Pantai, Sarapan 2 Pax, Kapasitas 2-3 orang',
                'harga' => 500000.00,
                'tipe_unit' => 'villa',
                'foto' => 'villa_daun.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 2,
                'id_pengelola' => 1,
                'nama_unit' => 'Villa Atap Seng Merah',
                'alamat' => 'Pantai Tanjung Api, Desa Sebubus, Paloh',
                'fasilitas' => '1 Kamar Tidur Utama, Kamar Mandi Dalam, AC, TV, Air Mineral, Teras Nyaman, Sarapan 2 Pax, Kapasitas 2 orang',
                'harga' => 450000.00,
                'tipe_unit' => 'villa',
                'foto' => 'villa_seng.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 3,
                'id_pengelola' => 1,
                'nama_unit' => 'Villa Atap Kerucut',
                'alamat' => 'Pantai Tanjung Api, Desa Sebubus, Paloh',
                'fasilitas' => 'Desain Kerucut Unik, 1 King Bed, Kamar Mandi Dalam dengan Shower, AC, TV, Teras Atas, Sarapan 2 Pax, Kapasitas 2-3 orang',
                'harga' => 600000.00,
                'tipe_unit' => 'villa',
                'foto' => 'villa_kerucut.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_unit' => 4,
                'id_pengelola' => 1,
                'nama_unit' => 'Tenda Camping Dome',
                'alamat' => 'Area Camp Pantai Tanjung Api, Desa Sebubus, Paloh',
                'fasilitas' => 'Tenda Dome Waterproof, Matras Camping, Sleeping Bag, Akses Toilet Umum Bersih, Kapasitas 2-4 orang',
                'harga' => 100000.00,
                'tipe_unit' => 'tenda',
                'foto' => 'tenda.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Seed Payment Gateway info
        DB::table('payment_gateway')->insert([
            'id_gateway' => 1,
            'nama_gateway' => 'Midtrans Sandbox Simulation',
            'info_gateway' => 'Simulasi gerbang pembayaran otomatis menggunakan sandbox environment. Mendukung pembayaran virtual account bank dan e-wallet.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
