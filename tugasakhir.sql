-- SQL Dump untuk Aplikasi Sistem Informasi & Reservasi Penginapan Pantai Tanjung Api
-- Database: `tugasakhir`
-- Catatan: Pengecekan foreign key dinonaktifkan sementara untuk menjamin proses import 100% tanpa error urutan tabel.

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- 1. Struktur Tabel `pengelola` (Admin)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pengelola`;
CREATE TABLE `pengelola` (
  `id_pengelola` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pengelola` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_password_token` varchar(255) DEFAULT NULL,
  `reset_password_token_created_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pengelola`),
  UNIQUE KEY `pengelola_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Default `pengelola` (Password: admin123)
INSERT INTO `pengelola` (`id_pengelola`, `nama_pengelola`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Rindi Admin', 'admin@tanjungapi.com', '$2y$12$NqL1K4yA1E2w5m4l8K3Z4.qG8z5X.7oP6uQ8vR9sT0uV1wX2yZ3a4', NOW(), NOW());

-- --------------------------------------------------------
-- 2. Struktur Tabel `penginapan` (Unit Penginapan)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `penginapan`;
CREATE TABLE `penginapan` (
  `id_unit` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengelola` bigint(20) UNSIGNED NOT NULL,
  `nama_unit` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `fasilitas` text NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `tipe_unit` varchar(255) NOT NULL DEFAULT 'villa',
  `jumlah_unit` int(11) NOT NULL DEFAULT 1,
  `foto` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_unit`),
  KEY `penginapan_id_pengelola_foreign` (`id_pengelola`),
  CONSTRAINT `penginapan_id_pengelola_foreign` FOREIGN KEY (`id_pengelola`) REFERENCES `pengelola` (`id_pengelola`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Default `penginapan`
INSERT INTO `penginapan` (`id_unit`, `id_pengelola`, `nama_unit`, `alamat`, `fasilitas`, `harga`, `tipe_unit`, `jumlah_unit`, `foto`, `created_at`, `updated_at`) VALUES
(1, 1, 'Villa Atap Daun', 'Pantai Tanjung Api, Desa Sebubus, Paloh', '1 Kamar Tidur Utama, Kamar Mandi Dalam, Kipas Angin, Teras Pantai, Sarapan 2 Pax, Kapasitas 2-3 orang', 500000.00, 'villa', 2, 'villa_daun.png', NOW(), NOW()),
(2, 1, 'Villa Atap Seng Merah', 'Pantai Tanjung Api, Desa Sebubus, Paloh', '1 Kamar Tidur Utama, Kamar Mandi Dalam, AC, TV, Air Mineral, Teras Nyaman, Sarapan 2 Pax, Kapasitas 2 orang', 450000.00, 'villa', 2, 'villa_seng.png', NOW(), NOW()),
(3, 1, 'Villa Atap Kerucut', 'Pantai Tanjung Api, Desa Sebubus, Paloh', 'Desain Kerucut Unik, 1 King Bed, Kamar Mandi Dalam dengan Shower, AC, TV, Teras Atas, Sarapan 2 Pax, Kapasitas 2-3 orang', 600000.00, 'villa', 2, 'villa_kerucut.png', NOW(), NOW()),
(4, 1, 'Tenda Camping Dome', 'Area Camp Pantai Tanjung Api, Desa Sebubus, Paloh', 'Tenda Dome Waterproof, Matras Camping, Sleeping Bag, Akses Toilet Umum Bersih, Kapasitas 2-4 orang', 100000.00, 'tenda', 10, 'tenda.png', NOW(), NOW());

-- --------------------------------------------------------
-- 3. Struktur Tabel `tamu` (Pengguna/Tamu)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tamu`;
CREATE TABLE `tamu` (
  `id_tamu` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `no_hp` varchar(255) NOT NULL,
  `no_hp_verification_code` varchar(255) DEFAULT NULL,
  `no_hp_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `sumber` varchar(255) NOT NULL DEFAULT 'web',
  `foto` varchar(255) DEFAULT NULL,
  `reset_password_token` varchar(255) DEFAULT NULL,
  `reset_password_token_created_at` timestamp NULL DEFAULT NULL,
  `no_ktp` varchar(20) DEFAULT NULL,
  `kewarganegaraan` varchar(10) DEFAULT 'WNI',
  `no_paspor` varchar(50) DEFAULT NULL,
  `negara` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kabupaten_kota` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `alamat_ktp` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tamu`),
  UNIQUE KEY `tamu_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Struktur Tabel `reservasi` (Pemesanan Unit)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `reservasi`;
CREATE TABLE `reservasi` (
  `id_reservasi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_tamu` bigint(20) UNSIGNED NOT NULL,
  `id_unit` bigint(20) UNSIGNED NOT NULL,
  `tanggal_checkin` date NOT NULL,
  `tanggal_checkout` date NOT NULL,
  `jumlah_kamar` int(11) NOT NULL DEFAULT 1,
  `status` enum('pending','confirmed','postponed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `alasan_pembatalan` text DEFAULT NULL,
  `persentase_refund` int(11) NOT NULL DEFAULT 0,
  `jumlah_refund` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status_refund` enum('no_refund','pending','refunded') NOT NULL DEFAULT 'no_refund',
  `bukti_refund` varchar(255) DEFAULT NULL,
  `tanggal_refund` timestamp NULL DEFAULT NULL,
  `tanggal_penundaan` timestamp NULL DEFAULT NULL,
  `batas_tunda_hingga` timestamp NULL DEFAULT NULL,
  `alasan_penundaan` text DEFAULT NULL,
  `sumber` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_reservasi`),
  KEY `reservasi_id_tamu_foreign` (`id_tamu`),
  KEY `reservasi_id_unit_foreign` (`id_unit`),
  CONSTRAINT `reservasi_id_tamu_foreign` FOREIGN KEY (`id_tamu`) REFERENCES `tamu` (`id_tamu`) ON DELETE CASCADE,
  CONSTRAINT `reservasi_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `penginapan` (`id_unit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Struktur Tabel `pembayaran`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_reservasi` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pembayaran` timestamp NULL DEFAULT NULL,
  `total_pembayaran` decimal(12,2) NOT NULL,
  `jenis_pembayaran` enum('full','dp') NOT NULL DEFAULT 'full',
  `nominal_dp` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sisa_pembayaran` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status_pelunasan` enum('lunas','belum_lunas') NOT NULL DEFAULT 'lunas',
  `metode_pembayaran` varchar(255) DEFAULT NULL,
  `status_pembayaran` varchar(255) NOT NULL DEFAULT 'belum lunas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `pembayaran_id_reservasi_foreign` (`id_reservasi`),
  CONSTRAINT `pembayaran_id_reservasi_foreign` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasi` (`id_reservasi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Struktur Tabel `payment_gateway`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `payment_gateway`;
CREATE TABLE `payment_gateway` (
  `id_gateway` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_gateway` varchar(255) NOT NULL,
  `info_gateway` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_gateway`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Default `payment_gateway`
INSERT INTO `payment_gateway` (`id_gateway`, `nama_gateway`, `info_gateway`, `created_at`, `updated_at`) VALUES
(1, 'Midtrans Sandbox Simulation', 'Simulasi gerbang pembayaran otomatis menggunakan sandbox environment. Mendukung pembayaran virtual account bank dan e-wallet.', NOW(), NOW());

-- --------------------------------------------------------
-- 7. Struktur Tabel `detail_pembayaran`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `detail_pembayaran`;
CREATE TABLE `detail_pembayaran` (
  `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pembayaran` bigint(20) UNSIGNED NOT NULL,
  `id_gateway` bigint(20) UNSIGNED NOT NULL,
  `jenis_pembayaran` varchar(255) NOT NULL,
  `jumlah_pembayaran` decimal(12,2) NOT NULL,
  `tanggal_detail` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `detail_pembayaran_id_pembayaran_foreign` (`id_pembayaran`),
  KEY `detail_pembayaran_id_gateway_foreign` (`id_gateway`),
  CONSTRAINT `detail_pembayaran_id_gateway_foreign` FOREIGN KEY (`id_gateway`) REFERENCES `payment_gateway` (`id_gateway`) ON DELETE CASCADE,
  CONSTRAINT `detail_pembayaran_id_pembayaran_foreign` FOREIGN KEY (`id_pembayaran`) REFERENCES `pembayaran` (`id_pembayaran`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. Struktur Tabel `foto_penginapan`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `foto_penginapan`;
CREATE TABLE `foto_penginapan` (
  `id_foto` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unit` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(255) NOT NULL,
  `is_utama` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_foto`),
  KEY `foto_penginapan_id_unit_foreign` (`id_unit`),
  CONSTRAINT `foto_penginapan_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `penginapan` (`id_unit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
