<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tamu;
use Illuminate\Support\Facades\DB;

class CleanGuestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guests:clean {--force : Jalankan tanpa konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan seluruh data pengguna/tamu yang terdaftar beserta relasinya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Tamu::count();

        if ($count === 0) {
            $this->info('Tidak ada data akun tamu yang terdaftar di database.');
            return 0;
        }

        if (!$this->option('force')) {
            if (!$this->confirm("PERINGATAN: Ada {$count} akun tamu terdaftar. Apakah Anda yakin ingin menghapus semuanya?")) {
                $this->warn('Operasi dibatalkan.');
                return 0;
            }
        }

        DB::transaction(function () {
            Tamu::query()->delete();
        });

        $this->info("Berhasil membersihkan {$count} akun tamu beserta riwayat pemesanannya.");
        return 0;
    }
}
