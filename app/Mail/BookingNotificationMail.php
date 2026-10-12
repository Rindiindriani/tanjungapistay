<?php

namespace App\Mail;

use App\Models\Reservasi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $emailSubject;
    public $customMessage;
    public $type; // 'status_update', 'checkin_reminder', 'payment_confirmation'

    /**
     * Create a new message instance.
     */
    public function __construct(Reservasi $booking, string $emailSubject, ?string $customMessage = null, string $type = 'status_update')
    {
        $this->booking       = $booking;
        $this->emailSubject  = $emailSubject;
        $this->customMessage = $customMessage;
        $this->type          = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
