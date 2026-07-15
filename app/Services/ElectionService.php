<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ElectionService
{
    public function createElection(array $data): Election
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['created_by'] = $data['created_by'] ?? auth()->id();

        $election = Election::create($data);

        $election->settings()->create();

        AuditLog::log('election.create', "Created election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
            'new_values' => $election->toArray(),
        ]);

        return $election;
    }

    public function updateElection(Election $election, array $data): Election
    {
        $old = $election->toArray();

        $election->update($data);

        AuditLog::log('election.update', "Updated election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
            'old_values' => $old,
            'new_values' => $election->fresh()->toArray(),
        ]);

        return $election->fresh();
    }

    public function deleteElection(Election $election): bool
    {
        if ($election->status !== 'draft') {
            throw new \InvalidArgumentException('Only draft elections can be deleted.');
        }

        AuditLog::log('election.delete', "Deleted election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
            'old_values' => $election->toArray(),
        ]);

        return $election->delete();
    }

    public function startElection(Election $election): Election
    {
        if (! $this->canStart($election)) {
            throw new \InvalidArgumentException('Election cannot be started.');
        }

        $election->update(['status' => 'active']);

        AuditLog::log('election.start', "Started election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $election->fresh();
    }

    public function endElection(Election $election): Election
    {
        if (! $this->canEnd($election)) {
            throw new \InvalidArgumentException('Election cannot be ended.');
        }

        $election->update(['status' => 'completed']);

        AuditLog::log('election.end', "Ended election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $election->fresh();
    }

    public function pauseElection(Election $election): Election
    {
        if ($election->status !== 'active') {
            throw new \InvalidArgumentException('Only active elections can be paused.');
        }

        $election->update(['status' => 'paused']);

        AuditLog::log('election.pause', "Paused election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $election->fresh();
    }

    public function resumeElection(Election $election): Election
    {
        if ($election->status !== 'paused') {
            throw new \InvalidArgumentException('Only paused elections can be resumed.');
        }

        $election->update(['status' => 'active']);

        AuditLog::log('election.resume', "Resumed election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $election->fresh();
    }

    public function cancelElection(Election $election): Election
    {
        $election->update(['status' => 'cancelled']);

        AuditLog::log('election.cancel', "Cancelled election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $election->fresh();
    }

    public function publishResults(Election $election): Election
    {
        if (! $this->canPublishResults($election)) {
            throw new \InvalidArgumentException('Results cannot be published for this election.');
        }

        $election->update(['results_published_at' => now()]);

        AuditLog::log('election.publish_results', "Published results for election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $election->fresh();
    }

    public function addVoters(Election $election, array $criteria): int
    {
        $query = User::voters()->active();

        if (! empty($criteria['faculty'])) {
            $query->where('faculty', $criteria['faculty']);
        }

        if (! empty($criteria['department'])) {
            $query->where('department', $criteria['department']);
        }

        if (! empty($criteria['year_of_study'])) {
            $query->where('year_of_study', $criteria['year_of_study']);
        }

        if (! empty($criteria['user_ids'])) {
            $query->whereIn('id', $criteria['user_ids']);
        }

        $count = 0;
        $query->chunk(100, function ($voters) use ($election, &$count) {
            foreach ($voters as $voter) {
                $election->voters()->syncWithoutDetaching([$voter->id]);
                $count++;
            }
        });

        AuditLog::log('election.add_voters', "Added {$count} voters to election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $count;
    }

    public function removeVoter(Election $election, User $user): bool
    {
        $result = $election->voters()->detach($user->id);

        AuditLog::log('election.remove_voter', "Removed voter from election: {$election->title}", [
            'model_type' => Election::class,
            'model_id' => $election->id,
        ]);

        return $result > 0;
    }

    public function getEligibleVoters(Election $election): Collection
    {
        return $election->voters()->where('is_active', true)->get();
    }

    public function getElectionResults(Election $election): array
    {
        $results = [];

        foreach ($election->positions()->ordered()->get() as $position) {
            $candidates = $position->candidates()->approved()->get()->map(function ($candidate) {
                return [
                    'candidate_id' => $candidate->id,
                    'name' => $candidate->user->name,
                    'slogan' => $candidate->slogan,
                    'photo_url' => $candidate->photo_url,
                    'vote_count' => $candidate->vote_count,
                    'vote_percentage' => $candidate->vote_percentage,
                ];
            })->sortByDesc('vote_count')->values();

            $results[] = [
                'position_id' => $position->id,
                'title' => $position->title,
                'total_votes' => $position->total_votes,
                'candidates' => $candidates,
            ];
        }

        return $results;
    }

    public function canStart(Election $election): bool
    {
        return in_array($election->status, ['draft', 'scheduled'])
            && $election->starts_at <= now()
            && $election->positions()->exists()
            && $election->candidates()->approved()->exists();
    }

    public function canEnd(Election $election): bool
    {
        return in_array($election->status, ['active', 'paused']);
    }

    public function canPublishResults(Election $election): bool
    {
        return $election->status === 'completed' && $election->results_published_at === null;
    }
}
