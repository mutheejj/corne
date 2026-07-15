<?php

namespace App\Notifications;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidateRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Candidate $candidate,
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
            ->subject('Your candidacy application status')
            ->line("We regret to inform you that your candidacy for {$this->candidate->position->title} in {$this->candidate->election->title} has not been approved.");

        if ($this->reason) {
            $mail->line("Reason: {$this->reason}");
        }

        $mail->line('If you believe this decision was made in error, you may appeal by contacting the election committee.')
            ->line('Thank you for your interest in serving the student community.');

        return $mail;
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
            'reason' => $this->reason,
        ];
    }
}
