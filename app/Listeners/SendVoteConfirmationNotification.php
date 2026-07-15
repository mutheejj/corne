<?php

namespace App\Listeners;

use App\Events\VoteCast;
use App\Notifications\VoteConfirmationNotification;
use App\Services\NotificationService;

class SendVoteConfirmationNotification
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function handle(VoteCast $event): void
    {
        $this->notificationService->notify(
            $event->user,
            'vote_cast',
            'Vote confirmed',
            "Your vote for {$event->position->title} has been recorded. Verification code: {$event->verificationCode}",
            [
                'verification_code' => $event->verificationCode,
                'receipt_hash' => $event->receiptHash,
                'position_title' => $event->position->title,
            ],
        );

        $event->user->notify(new VoteConfirmationNotification(
            $event->verificationCode,
            $event->receiptHash,
            $event->position->title,
            $event->position->election,
        ));
    }
}
