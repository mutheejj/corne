<?php

namespace App\Notifications;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VoteConfirmationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $verificationCode,
        public string $receiptHash,
        public string $positionTitle,
        public Election $election,
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
        return (new MailMessage)
            ->subject("Vote confirmation — {$this->election->title}")
            ->line("Your vote for {$this->positionTitle} has been recorded.")
            ->line("Verification Code: {$this->verificationCode}")
            ->line("Receipt Hash: {$this->receiptHash}")
            ->line('Please save this information for your records.')
            ->action('Verify Your Vote', route('voter.vote-history'))
            ->line('Thank you for voting!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'verification_code' => $this->verificationCode,
            'receipt_hash' => $this->receiptHash,
            'position_title' => $this->positionTitle,
            'election_id' => $this->election->id,
        ];
    }
}
