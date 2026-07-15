<?php

namespace App\Listeners;

use App\Events\CandidateRejected;
use App\Notifications\CandidateRejectedNotification;
use App\Services\NotificationService;

class SendCandidateRejectedNotification
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(CandidateRejected $event): void
    {
        $this->notificationService->notify(
            $event->candidate->user,
            'candidate_rejected',
            'Candidacy rejected',
            "Your candidacy for {$event->candidate->position->title} has been rejected.",
            ['candidate_id' => $event->candidate->id, 'reason' => $event->reason],
        );

        $event->candidate->user->notify(new CandidateRejectedNotification($event->candidate, $event->reason));
    }
}
