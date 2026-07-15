<?php

namespace App\Listeners;

use App\Events\CandidateApproved;
use App\Notifications\CandidateApprovedNotification;
use App\Services\NotificationService;

class SendCandidateApprovedNotification
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(CandidateApproved $event): void
    {
        $this->notificationService->notify(
            $event->candidate->user,
            'candidate_approved',
            'Candidacy approved',
            "Your candidacy for {$event->candidate->position->title} has been approved.",
            ['candidate_id' => $event->candidate->id],
        );

        $event->candidate->user->notify(new CandidateApprovedNotification($event->candidate));
    }
}
