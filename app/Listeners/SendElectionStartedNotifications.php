<?php

namespace App\Listeners;

use App\Events\ElectionStarted;
use App\Services\NotificationService;

class SendElectionStartedNotifications
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(ElectionStarted $event): void
    {
        $this->notificationService->notifyEligibleVoters(
            $event->election,
            'election_started',
            'Voting is now open',
            "The election \"{$event->election->title}\" is now active. Cast your vote now!",
        );
    }
}
