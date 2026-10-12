<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bookings:cancel-expired';

    /**
     * The console command description.
     */
    protected $description = 'Membatalkan otomatis reservasi yang belum dibayar lebih dari 24 jam';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = Reservasi::autoCancelExpired();

        if ($count > 0) {
            $this->info("✅ {$count} reservasi kadaluarsa berhasil dibatalkan.");
        } else {
            $this->info("✅ Tidak ada reservasi kadaluarsa.");
        }
    }
}
