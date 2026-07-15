<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;

class ResultsService
{
    public function getElectionResults(Election $election): array
    {
        $totalVotesCast = $election->totalVotesCast();
        $totalVoters = $election->total_voters;

        $positions = $election->positions()->ordered()->get()->map(function ($position) {
            return $this->getPositionResults($position);
        })->toArray();

        return [
            'election' => $election,
            'total_voters' => $totalVoters,
            'total_votes_cast' => $totalVotesCast,
            'turnout_percentage' => $election->turnout_percentage,
            'positions' => $positions,
        ];
    }

    public function getPositionResults(Position $position): array
    {
        $totalVotes = $position->votes()->count();
        $abstentions = $position->votes()->whereNull('candidate_id')->count();

        $candidates = $position->candidates()->approved()->with('user')->get()
            ->map(function ($candidate) use ($position, $totalVotes) {
                $voteCount = $position->votes()->where('candidate_id', $candidate->id)->count();
                $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 2) : 0.0;

                return [
                    'candidate' => $candidate,
                    'vote_count' => $voteCount,
                    'vote_percentage' => $percentage,
                ];
            })
            ->sortByDesc('vote_count')
            ->values();

        $rank = 0;
        $prevCount = null;
        $candidates = $candidates->map(function ($item) use (&$rank, &$prevCount) {
            if ($prevCount === null || $item['vote_count'] !== $prevCount) {
                $rank++;
                $prevCount = $item['vote_count'];
            }
            $item['rank'] = $rank;

            return $item;
        });

        $maxVotes = $candidates->max('vote_count') ?? 0;
        $winners = $candidates->filter(fn ($c) => $c['vote_count'] === $maxVotes && $maxVotes > 0);
        $isTie = $winners->count() > 1;

        $candidates = $candidates->map(function ($item) use ($winners, $isTie) {
            $item['is_winner'] = $winners->contains('candidate.id', $item['candidate']->id) && ! $isTie;

            return $item;
        });

        return [
            'position' => $position,
            'total_votes' => $totalVotes,
            'abstentions' => $abstentions,
            'candidates' => $candidates,
            'is_tie' => $isTie,
        ];
    }

    public function getCandidateResult(Candidate $candidate): array
    {
        $position = $candidate->position;
        $positionResults = $this->getPositionResults($position);

        $candidateResult = $positionResults['candidates']->firstWhere('candidate.id', $candidate->id);

        return [
            'candidate' => $candidate,
            'vote_count' => $candidateResult['vote_count'] ?? 0,
            'vote_percentage' => $candidateResult['vote_percentage'] ?? 0.0,
            'rank' => $candidateResult['rank'] ?? 0,
            'is_winner' => $candidateResult['is_winner'] ?? false,
            'position_results' => $positionResults,
        ];
    }

    public function getElectionTurnout(Election $election): array
    {
        $totalVoters = $election->total_voters;
        $votedCount = $election->voteRecords()->distinct('user_id')->count('user_id');
        $notVoted = $totalVoters - $votedCount;

        return [
            'total_voters' => $totalVoters,
            'voted' => $votedCount,
            'not_voted' => $notVoted,
            'turnout_percentage' => $totalVoters > 0 ? round(($votedCount / $totalVoters) * 100, 2) : 0.0,
        ];
    }

    public function getVoteTimeline(Election $election): array
    {
        $votes = $election->votes()
            ->selectRaw("strftime('%Y-%m-%d %H:00', cast_at) as timestamp, COUNT(*) as votes")
            ->groupBy('timestamp')
            ->orderBy('timestamp')
            ->get();

        return $votes->map(fn ($v) => [
            'timestamp' => $v->timestamp,
            'votes' => $v->votes,
        ])->toArray();
    }

    public function exportResultsCsv(Election $election): string
    {
        $results = $this->getElectionResults($election);
        $csv = "Election: {$election->title}\n";
        $csv .= "Status: {$election->status}\n";
        $csv .= "Start: {$election->starts_at}\n";
        $csv .= "End: {$election->ends_at}\n";
        $csv .= "Total Voters: {$results['total_voters']}\n";
        $csv .= "Total Votes Cast: {$results['total_votes_cast']}\n";
        $csv .= "Turnout: {$results['turnout_percentage']}%\n\n";

        foreach ($results['positions'] as $positionResult) {
            $csv .= "Position: {$positionResult['position']['title']}\n";
            $csv .= "Total Votes: {$positionResult['total_votes']}\n";
            $csv .= "Abstentions: {$positionResult['abstentions']}\n";
            $csv .= "Rank,Candidate Name,Vote Count,Percentage,Winner\n";

            foreach ($positionResult['candidates'] as $c) {
                $winner = $c['is_winner'] ? 'Yes' : 'No';
                $csv .= "{$c['rank']},{$c['candidate']['user']['name']},{$c['vote_count']},{$c['vote_percentage']}%,{$winner}\n";
            }
            $csv .= "\n";
        }

        return $csv;
    }

    public function exportResultsPdf(Election $election): string
    {
        $results = $this->getElectionResults($election);

        return view('dashboard.admin.results-pdf', compact('election', 'results'))->render();
    }

    public function generateAuditReport(Election $election): array
    {
        $totalVotes = $election->votes()->count();
        $totalVoteRecords = $election->voteRecords()->count();
        $discrepancies = $totalVotes !== $totalVoteRecords;

        $auditLogs = $election->auditLogs()->latest()->get();

        $timeline = $this->getVoteTimeline($election);

        $integrityCheck = $this->verifyIntegrity($election);

        return [
            'election' => $election,
            'total_votes' => $totalVotes,
            'total_vote_records' => $totalVoteRecords,
            'discrepancies' => $discrepancies,
            'audit_logs' => $auditLogs,
            'timeline' => $timeline,
            'integrity_check' => $integrityCheck,
        ];
    }

    public function getLiveResults(Election $election): array
    {
        $results = $this->getElectionResults($election);
        $turnout = $this->getElectionTurnout($election);

        return [
            'election' => $election->only(['id', 'title', 'status']),
            'total_voters' => $turnout['total_voters'],
            'voted' => $turnout['voted'],
            'turnout_percentage' => $turnout['turnout_percentage'],
            'positions' => collect($results['positions'])->map(function ($pos) {
                return [
                    'position_id' => $pos['position']['id'],
                    'title' => $pos['position']['title'],
                    'total_votes' => $pos['total_votes'],
                    'abstentions' => $pos['abstentions'],
                    'candidates' => $pos['candidates']->map(fn ($c) => [
                        'candidate_id' => $c['candidate']['id'],
                        'name' => $c['candidate']['user']['name'],
                        'vote_count' => $c['vote_count'],
                        'vote_percentage' => $c['vote_percentage'],
                        'rank' => $c['rank'],
                        'is_winner' => $c['is_winner'],
                    ])->toArray(),
                ];
            })->toArray(),
        ];
    }

    private function verifyIntegrity(Election $election): bool
    {
        $votes = $election->votes()->get();

        foreach ($votes as $vote) {
            if (empty($vote->receipt_hash) || empty($vote->verification_code)) {
                return false;
            }
        }

        return true;
    }
}
