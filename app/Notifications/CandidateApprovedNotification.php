<?php

namespace App\Notifications;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidateApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Candidate $candidate,
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
            ->subject('Your candidacy has been approved')
            ->line("Congratulations! Your candidacy for {$this->candidate->position->title} in {$this->candidate->election->title} has been approved.")
            ->line('You can now manage your campaign profile and interact with voters.')
            ->action('View Dashboard', route('candidate.dashboard'))
            ->line('Good luck with your campaign!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'candidate_id' => $this->candidate->id,
            'position' => $this->candidate->position->title,
            'election' => $this->candidate->election->title,
        ];
    }
}
