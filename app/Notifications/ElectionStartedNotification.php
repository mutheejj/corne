<?php

namespace App\Notifications;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ElectionStartedNotification extends Notification
{
    use Queueable;

    public function __construct(
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
            ->subject("Voting is now open: {$this->election->title}")
            ->line("The election \"{$this->election->title}\" is now active.")
            ->line("Positions: {$this->election->positions()->count()}")
            ->line("Deadline: {$this->election->ends_at->format('M j, Y g:i A')}")
            ->action('Cast Your Vote', route('voter.elections.show', $this->election))
            ->line('Your vote matters!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'election_id' => $this->election->id,
            'election_title' => $this->election->title,
        ];
    }
}
