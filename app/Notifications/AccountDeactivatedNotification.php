<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeactivatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ?string $reason = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Your account has been deactivated')
            ->line('Your Cornelect account has been deactivated by an administrator.');

        if ($this->reason) {
            $mail->line("Reason: {$this->reason}");
        }

        $mail->line('If you believe this is an error, please contact the university election office at admin@cornelect.ac.ke.');

        return $mail;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reason' => $this->reason,
        ];
    }
}
