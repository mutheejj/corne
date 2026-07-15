<?php

namespace App\Notifications;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ElectionEndingSoonNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Election $election,
        public int $hoursRemaining,
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
            ->subject("Reminder: {$this->election->title} ends in {$this->hoursRemaining} hours")
            ->line("The election \"{$this->election->title}\" ends in {$this->hoursRemaining} hours.")
            ->line("Time remaining: {$this->hoursRemaining} hour(s)")
            ->action('Cast Your Vote Now', route('voter.elections.show', $this->election))
            ->line("Don't miss your chance to vote!");
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'election_id' => $this->election->id,
            'election_title' => $this->election->title,
            'hours_remaining' => $this->hoursRemaining,
        ];
    }
}
