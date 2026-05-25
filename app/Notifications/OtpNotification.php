<?php

namespace App\Notifications;

use App\Models\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Otp $otp
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi OTP — ZLM.ID')
            ->view('emails.otp', [
                'name' => $notifiable->name,
                'otp' => $this->otp->otp,
                'expiresAt' => $this->otp->expires_at,
            ]);
    }
}
