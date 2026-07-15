<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Services\ElectionService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('elections:end-active')]
#[Description('End active elections whose ends_at has passed')]
class EndActiveElections extends Command
{
    public function handle(ElectionService $electionService): int
    {
        $elections = Election::active()
            ->where('ends_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($elections as $election) {
            try {
                $electionService->endElection($election);
                $count++;
            } catch (\InvalidArgumentException $e) {
                $this->error("Failed to end election {$election->title}: {$e->getMessage()}");
            }
        }

        $this->info("Ended {$count} active elections.");

        return self::SUCCESS;
    }
}
