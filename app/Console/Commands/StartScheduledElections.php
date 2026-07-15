<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Services\ElectionService;
use App\Services\EligibilityService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('elections:start-scheduled')]
#[Description('Start scheduled elections whose starts_at has passed')]
class StartScheduledElections extends Command
{
    public function handle(ElectionService $electionService, EligibilityService $eligibilityService): int
    {
        $elections = Election::scheduled()
            ->where('starts_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($elections as $election) {
            try {
                $electionService->startElection($election);
                $eligibilityService->notifyEligibleVoters($election);
                $count++;
            } catch (\InvalidArgumentException $e) {
                $this->error("Failed to start election {$election->title}: {$e->getMessage()}");
            }
        }

        $this->info("Started {$count} scheduled elections.");

        return self::SUCCESS;
    }
}
