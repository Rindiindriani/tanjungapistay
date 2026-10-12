<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendCheckinReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bookings:send-checkin-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Kirim notifikasi otomatis pengingat check-in (H-1) via Email & WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Ambil semua reservasi terkonfirmasi yang jadwal check-in nya besok (H-1)
        $bookings = Reservasi::with(['tamu', 'penginapan', 'pembayaran'])
            ->where('status', 'confirmed')
            ->whereDate('tanggal_checkin', $tomorrow)
            ->get();

        if ($bookings->isEmpty()) {
            $this->info("✅ Tidak ada reservasi yang check-in besok ({$tomorrow}).");
            return;
        }

        $count = 0;
        foreach ($bookings as $booking) {
            NotificationService::sendCheckinReminder($booking);
            $count++;
        }

        $this->info("🚀 Berhasil mengirimkan pengingat check-in ke {$count} tamu untuk besok ({$tomorrow}).");
    }
}
