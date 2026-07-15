<?php

namespace App\Listeners;

use App\Events\ElectionEnded;
use App\Services\NotificationService;

class SendElectionEndedNotifications
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(ElectionEnded $event): void
    {
        $this->notificationService->notifyEligibleVoters(
            $event->election,
            'election_ended',
            'Election has ended',
            "The election \"{$event->election->title}\" has ended. Results will be published soon.",
        );
    }
}
