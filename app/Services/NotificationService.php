<?php

namespace App\Services;

use App\Models\Reservasi;
use App\Mail\BookingNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Mengirim notifikasi status reservasi & pembayaran via Email & WhatsApp.
     */
    public static function sendBookingStatusNotification(Reservasi $booking, ?string $customMessage = null): array
    {
        $booking->loadMissing(['tamu', 'penginapan', 'pembayaran']);

        $statusText = match ($booking->status) {
            'confirmed' => 'TERKONFIRMASI / LUNAS',
            'postponed' => 'DITUNDA (Pending Reschedule)',
            'cancelled' => 'DIBATALKAN',
            'completed' => 'SELESAI',
            default     => 'MENUNGGU PEMBAYARAN',
        };

        $subject = "Pembaruan Status Reservasi #TA-" . str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT) . " - " . $statusText;

        // 1. Kirim Email Notifikasi
        $emailStatus = false;
        if (!empty($booking->tamu->email)) {
            try {
                Mail::to($booking->tamu->email)->send(
                    new BookingNotificationMail($booking, $subject, $customMessage, 'status_update')
                );
                $emailStatus = true;
                Log::info("NotificationService: Email berhasil terkirim ke {$booking->tamu->email}");
            } catch (\Exception $e) {
                Log::error("NotificationService Email Error: " . $e->getMessage());
            }
        }

        // 2. Kirim WhatsApp Notifikasi
        $waMessage = self::buildWhatsAppStatusMessage($booking, $statusText, $customMessage);
        $waStatus  = self::sendWhatsAppMessage($booking->tamu->no_hp, $waMessage);

        return [
            'email_sent' => $emailStatus,
            'wa_sent'    => $waStatus['sent'],
            'wa_reason'  => $waStatus['reason'] ?? null,
            'wa_message' => $waStatus['message'] ?? null,
        ];
    }

    /**
     * Mengirim notifikasi pengingat jadwal check-in (H-1) via Email & WhatsApp.
     */
    public static function sendCheckinReminder(Reservasi $booking, ?string $customMessage = null): array
    {
        $booking->loadMissing(['tamu', 'penginapan', 'pembayaran']);

        $subject = "⏰ Pengingat Check-in Besok - Reservasi #TA-" . str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT);

        // 1. Kirim Email Pengingat
        $emailStatus = false;
        if (!empty($booking->tamu->email)) {
            try {
                Mail::to($booking->tamu->email)->send(
                    new BookingNotificationMail($booking, $subject, $customMessage, 'checkin_reminder')
                );
                $emailStatus = true;
                Log::info("NotificationService: Email Pengingat Check-in terkirim ke {$booking->tamu->email}");
            } catch (\Exception $e) {
                Log::error("NotificationService Email Reminder Error: " . $e->getMessage());
            }
        }

        // 2. Kirim WA Pengingat
        $waMessage = self::buildWhatsAppReminderMessage($booking, $customMessage);
        $waStatus  = self::sendWhatsAppMessage($booking->tamu->no_hp, $waMessage);

        return [
            'email_sent' => $emailStatus,
            'wa_sent'    => $waStatus['sent'],
            'wa_reason'  => $waStatus['reason'] ?? null,
            'wa_message' => $waStatus['message'] ?? null,
        ];
    }

    /**
     * Membangun format pesan WhatsApp untuk pembaruan status.
     */
  private static function buildWhatsAppStatusMessage(Reservasi $booking, string $statusText, ?string $customMessage): string
    {
        $idFormatted = 'TA-' . str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT);
        $checkinDate = date('d M Y', strtotime($booking->tanggal_checkin));
        $checkoutDate = date('d M Y', strtotime($booking->tanggal_checkout));
        $totalPay = 'Rp ' . number_format($booking->pembayaran->total_pembayaran ?? 0, 0, ',', '.');

        // DP information (if any)
        $dpInfo = '';
        if ($booking->pembayaran && $booking->pembayaran->jenis_pembayaran === 'dp') {
            $sisa = $booking->pembayaran->sisa_pembayaran ?? 0;
            $dpInfo = "• Skema Bayar: Sudah DP 50% dan akan dibayar 50% lagi ketika berada di tempat (Sisa Rp " . number_format($sisa, 0, ',', '.') . " saat Check‑in)\n";
        }

        $msg = "📢 *PEMBARUAN STATUS RESERVASI #{$idFormatted}*\n" .
               "Pantai Tanjung Api\n\n" .
               "Halo *{$booking->tamu->nama}*,\n" .
               "Status reservasi Anda saat ini: *{$statusText}*\n\n" .
               "📌 *Rincian Pesanan:*\n" .
               "• Unit: {$booking->penginapan->nama_unit} ({$booking->jumlah_kamar} Unit)\n" .
               "• Check‑in: {$checkinDate}\n" .
               "• Check‑out: {$checkoutDate}\n" .
               "• Total Biaya: {$totalPay}\n" .
               $dpInfo . "\n";

        if ($customMessage) {
            $msg .= "✉️ *Catatan Pengelola:* {$customMessage}\n\n";
        }

        if ($booking->status === 'postponed' && $booking->batas_tunda_hingga) {
            $msg .= "⚠️ *Batas Waktu Reschedule:* " . $booking->batas_tunda_hingga->format('d M Y') . "\n\n";
        }

        $msg .= "Lihat detail invoice & tiket Anda di:\n" . route('bookings.show', $booking->id_reservasi) . "\n\n" .
                "Terima kasih telah memilih Pantai Tanjung Api!";

        return $msg;
    }

    /**
     * Membangun format pesan WhatsApp untuk pengingat check-in.
     */
    private static function buildWhatsAppReminderMessage(Reservasi $booking, ?string $customMessage): string
    {
        $idFormatted = 'TA-' . str_pad($booking->id_reservasi, 4, '0', STR_PAD_LEFT);
        $checkinDate = date('d M Y', strtotime($booking->tanggal_checkin));

        $msg = "⏰ *PENGINGAT JADWAL CHECK-IN (H-1)*\n" .
               "Pantai Tanjung Api\n\n" .
               "Halo *{$booking->tamu->nama}*,\n" .
               "Kami mengingatkan bahwa besok (*{$checkinDate}*) adalah jadwal check-in reservasi Anda (*#{$idFormatted}*) di unit *{$booking->penginapan->nama_unit}*.\n\n" .
               "Tunjukkan rincian pesanan/tiket saat kedatangan di lokasi penginapan Pantai Tanjung Api, Desa Sebubus, Sambas.\n\n";

        if ($customMessage) {
            $msg .= "✉️ *Pesan Khusus:* {$customMessage}\n\n";
        }

        $msg .= "Detail reservasi: " . route('bookings.show', $booking->id_reservasi) . "\n\n" .
                "Kami siap menyambut kedatangan Anda!";

        return $msg;
    }

    /**
     * Mengirimkan pesan teks ke nomor WhatsApp via Fonnte API.
     */
    public static function sendWhatsAppMessage(?string $targetPhone, string $message): array
    {
        if (empty($targetPhone)) {
            return ['sent' => false, 'reason' => 'NO_PHONE', 'message' => 'Nomor HP tidak ada'];
        }

        $token = env('FONNTE_TOKEN');

        if (empty($token)) {
            Log::info("NotificationService WA (Simulasi Token Kosong): Target {$targetPhone} => " . str_replace("\n", " ", $message));
            return [
                'sent'    => false,
                'reason'  => 'TOKEN_EMPTY',
                'message' => 'FONNTE_TOKEN belum diisi (Pesan dicatat di Log/Simulasi).'
            ];
        }

        $formattedPhone = trim($targetPhone);
        if (str_starts_with($formattedPhone, '0')) {
            $formattedPhone = '62' . substr($formattedPhone, 1);
        } elseif (str_starts_with($formattedPhone, '+62')) {
            $formattedPhone = substr($formattedPhone, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $formattedPhone,
                'message' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                Log::info("NotificationService WA terkirim ke {$formattedPhone}");
                return ['sent' => true, 'message' => 'Pesan WA berhasil terkirim'];
            } else {
                $errDetail = isset($result['reason']) ? $result['reason'] : json_encode($result);
                Log::warning("NotificationService WA Gagal ke {$formattedPhone}: " . $errDetail);
                return ['sent' => false, 'reason' => 'API_ERROR', 'message' => $errDetail];
            }
        } catch (\Exception $e) {
            Log::error("NotificationService WA Exception: " . $e->getMessage());
            return ['sent' => false, 'reason' => 'EXCEPTION', 'message' => $e->getMessage()];
        }
    }
}
