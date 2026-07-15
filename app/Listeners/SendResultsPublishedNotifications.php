<?php

namespace App\Listeners;

use App\Events\ResultsPublished;
use App\Services\NotificationService;

class SendResultsPublishedNotifications
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(ResultsPublished $event): void
    {
        $this->notificationService->notifyEligibleVoters(
            $event->election,
            'results_published',
            'Results are available',
            "Results for \"{$event->election->title}\" have been published.",
        );

        $event->election->candidates()->with('user')->chunk(100, function ($candidates) use ($event) {
            foreach ($candidates as $candidate) {
                $this->notificationService->notify(
                    $candidate->user,
                    'results_published',
                    'Results are available',
                    "Results for \"{$event->election->title}\" have been published.",
                    ['election_id' => $event->election->id],
                );
            }
        });
    }
}
