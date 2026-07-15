<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class VoteService
{
    public function castVote(User $user, Election $election, Position $position, ?int $candidateId = null, bool $abstain = false): Vote
    {
        if ($election->status !== 'active') {
            throw new \DomainException('Election is not active.');
        }

        if (! $election->voters()->where('user_id', $user->id)->exists()) {
            throw new \DomainException('User is not eligible to vote in this election.');
        }

        if ($this->hasVotedForPosition($user, $position)) {
            throw new \DomainException('User has already voted for this position.');
        }

        if ($abstain) {
            $settings = $election->settings()->first();
            if (! $settings || ! $settings->allow_abstain) {
                throw new \DomainException('Abstention is not allowed for this election.');
            }
            $candidateId = null;
        } else {
            if ($candidateId === null) {
                throw new \DomainException('A candidate must be selected.');
            }

            $candidate = Candidate::approved()
                ->where('id', $candidateId)
                ->where('position_id', $position->id)
                ->first();

            if (! $candidate) {
                throw new \DomainException('Candidate is not approved or does not belong to this position.');
            }
        }

        $verificationCode = $this->generateVerificationCode();
        $receiptHash = $this->generateReceiptHash($election, $position, $candidateId, $verificationCode);
        $encryptedChoice = $this->encryptChoice($candidateId, $verificationCode);

        $vote = Vote::create([
            'election_id' => $election->id,
            'position_id' => $position->id,
            'candidate_id' => $candidateId,
            'verification_code' => $verificationCode,
            'receipt_hash' => $receiptHash,
            'encrypted_choice' => $encryptedChoice,
            'cast_at' => now(),
        ]);

        VoteRecord::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'position_id' => $position->id,
            'verification_code' => $verificationCode,
            'receipt_hash' => $receiptHash,
            'voted_at' => now(),
        ]);

        AuditLog::log('vote.cast', "User voted in position {$position->title} of election {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $vote;
    }

    public function generateVerificationCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $code = '';
            for ($i = 0; $i < 16; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (Vote::where('verification_code', $code)->exists());

        return $code;
    }

    public function generateReceiptHash(Election $election, Position $position, ?int $candidateId, string $verificationCode): string
    {
        $data = implode('|', [
            $election->id,
            $position->id,
            $candidateId,
            $verificationCode,
            microtime(true),
            config('app.key'),
        ]);

        return hash('sha256', $data);
    }

    public function encryptChoice(?int $candidateId, string $verificationCode): string
    {
        $key = config('app.key');
        $iv = substr(hash('sha256', $verificationCode), 0, 16);
        $encrypted = openssl_encrypt((string) $candidateId, 'AES-256-CBC', $key, 0, $iv);

        return $encrypted !== false ? $encrypted : '';
    }

    public function decryptChoice(string $encryptedChoice, string $verificationCode): ?int
    {
        $key = config('app.key');
        $iv = substr(hash('sha256', $verificationCode), 0, 16);
        $decrypted = openssl_decrypt($encryptedChoice, 'AES-256-CBC', $key, 0, $iv);

        return $decrypted !== false ? (int) $decrypted : null;
    }

    /**
     * @return array{election_title: string, position_title: string, receipt_hash: string, cast_at: Carbon, counted: bool}|null
     */
    public function verifyVote(string $verificationCode): ?array
    {
        $vote = Vote::where('verification_code', $verificationCode)->first();

        if (! $vote) {
            return null;
        }

        return [
            'election_title' => $vote->election->title,
            'position_title' => $vote->position->title,
            'receipt_hash' => $vote->receipt_hash,
            'cast_at' => $vote->cast_at,
            'counted' => true,
        ];
    }

    public function hasVotedForPosition(User $user, Position $position): bool
    {
        return $user->voteRecords()->where('position_id', $position->id)->exists();
    }

    public function hasVotedInElection(User $user, Election $election): bool
    {
        return $user->voteRecords()->where('election_id', $election->id)->exists();
    }

    public function getVoteHistory(User $user): Collection
    {
        return $user->voteRecords()
            ->with(['election', 'position'])
            ->latest('voted_at')
            ->get();
    }

    public function tallyPosition(Position $position): Collection
    {
        return $position->votes()
            ->selectRaw('candidate_id, COUNT(*) as vote_count')
            ->groupBy('candidate_id')
            ->orderByDesc('vote_count')
            ->get()
            ->map(function ($item) use ($position) {
                $totalVotes = $position->votes()->count();

                return [
                    'candidate_id' => $item->candidate_id,
                    'vote_count' => $item->vote_count,
                    'vote_percentage' => $totalVotes > 0 ? round(($item->vote_count / $totalVotes) * 100, 2) : 0.0,
                ];
            });
    }

    public function tallyElection(Election $election): array
    {
        $results = [];

        foreach ($election->positions()->ordered()->get() as $position) {
            $tally = $this->tallyPosition($position);
            $totalVotes = $position->votes()->count();
            $abstentions = $position->votes()->whereNull('candidate_id')->count();

            $candidates = $position->candidates()->approved()->with('user')->get()
                ->map(function ($candidate) use ($tally, $totalVotes) {
                    $voteCount = $tally->firstWhere('candidate_id', $candidate->id)['vote_count'] ?? 0;
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

            $results[] = [
                'position' => $position,
                'total_votes' => $totalVotes,
                'abstentions' => $abstentions,
                'candidates' => $candidates,
                'is_tie' => $isTie,
            ];
        }

        return $results;
    }
}
