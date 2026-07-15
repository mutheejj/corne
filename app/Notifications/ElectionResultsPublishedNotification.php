<?php

namespace App\Notifications;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ElectionResultsPublishedNotification extends Notification
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
            ->subject("Results published: {$this->election->title}")
            ->line("The results for \"{$this->election->title}\" have been published.")
            ->action('View Results', route('voter.elections.results', $this->election))
            ->line('Thank you for participating!');
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
