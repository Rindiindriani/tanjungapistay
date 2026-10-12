<?php

namespace App\Console\Commands;

use App\Models\Tamu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanUnverifiedGuests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tamu:clean-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus otomatis akun tamu yang belum verifikasi email dalam 3 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = now()->subDays(3);

        // Cari tamu yang belum verifikasi email dan terdaftar sebelum cutoffDate
        $unverifiedGuests = Tamu::whereNull('email_verified_at')
            ->where('created_at', '<=', $cutoffDate)
            ->get();

        $count = 0;
        foreach ($unverifiedGuests as $tamu) {
            Log::info("Menghapus akun tamu belum terverifikasi (>3 hari): {$tamu->email} (ID: {$tamu->id_tamu})");
            $tamu->delete();
            $count++;
        }

        $this->info("Berhasil menghapus {$count} akun tamu yang belum melakukan verifikasi email dalam 3 hari.");
        return Command::SUCCESS;
    }
}
