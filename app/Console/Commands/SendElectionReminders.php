<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Notifications\ElectionEndingSoonNotification;
use App\Services\NotificationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('notifications:election-reminders')]
#[Description('Send reminders to eligible voters who have not voted for elections ending in 24h or 1h')]
class SendElectionReminders extends Command
{
    public function handle(NotificationService $notificationService): int
    {
        $elections = Election::active()
            ->where('ends_at', '>', now())
            ->where('ends_at', '<=', now()->addHours(24))
            ->get();

        $count = 0;

        foreach ($elections as $election) {
            $hoursRemaining = (int) now()->diffInHours($election->ends_at, false);

            if (! in_array($hoursRemaining, [1, 24]) && ! ($hoursRemaining <= 1)) {
                continue;
            }

            $hoursLabel = $hoursRemaining <= 1 ? 1 : 24;

            $nonVoters = $election->voters()
                ->whereNotIn('users.id', $election->voteRecords()->pluck('user_id'))
                ->get();

            foreach ($nonVoters as $voter) {
                $notificationService->notify(
                    $voter,
                    'election_ending',
                    'Election ends soon',
                    "The election \"{$election->title}\" ends in {$hoursLabel} hour(s). Cast your vote now!",
                    ['election_id' => $election->id, 'hours_remaining' => $hoursLabel],
                );

                $voter->notify(new ElectionEndingSoonNotification($election, $hoursLabel));
                $count++;
            }
        }

        $this->info("Sent {$count} election reminder notifications.");

        return self::SUCCESS;
    }
}
