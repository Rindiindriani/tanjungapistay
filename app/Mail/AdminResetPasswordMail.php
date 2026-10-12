<?php

namespace App\Mail;

use App\Models\Pengelola;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengelola;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Pengelola $pengelola)
    {
        $this->pengelola = $pengelola;
        $this->resetUrl = route('admin.reset-password', ['token' => $pengelola->reset_password_token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Atur Ulang Kata Sandi Pengelola - Tanjung Api',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_reset_password',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
