<?php

namespace App\Notifications;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidateDisqualifiedNotification extends Notification
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
            ->subject('Your candidacy has been disqualified')
            ->line("Your candidacy for {$this->candidate->position->title} in {$this->candidate->election->title} has been disqualified.");

        if ($this->reason) {
            $mail->line("Reason: {$this->reason}");
        }

        $mail->line('If you believe this decision was made in error, you may appeal by contacting the election committee.');

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
