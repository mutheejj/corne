<?php

namespace App\Services;

use App\Models\Election;
use App\Models\ElectionVoter;
use App\Models\Notification;
use App\Models\User;

class EligibilityService
{
    public function isEligible(User $user, Election $election): bool
    {
        if ($user->role !== 'voter') {
            return false;
        }

        if (! $user->is_active) {
            return false;
        }

        if ($user->email_verified_at === null) {
            return false;
        }

        if (! $election->voters()->where('user_id', $user->id)->exists()) {
            return false;
        }

        if ($election->faculty_id && $user->faculty !== $election->faculty?->name) {
            return false;
        }

        if ($election->department_id && $user->department !== $election->department?->name) {
            return false;
        }

        return true;
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

        return $count;
    }

    public function addAllVoters(Election $election): int
    {
        return $this->addVoters($election, []);
    }

    public function removeVoter(Election $election, User $user): bool
    {
        return $election->voters()->detach($user->id) > 0;
    }

    public function notifyEligibleVoters(Election $election): void
    {
        $election->voters()->chunk(100, function ($voters) use ($election) {
            foreach ($voters as $voter) {
                Notification::create([
                    'user_id' => $voter->id,
                    'type' => 'election',
                    'title' => "Election Started: {$election->title}",
                    'message' => "The election '{$election->title}' is now active. Cast your vote now!",
                    'data' => ['election_id' => $election->id],
                ]);

                ElectionVoter::where('election_id', $election->id)
                    ->where('user_id', $voter->id)
                    ->update(['notified' => true]);
            }
        });
    }

    public function getEligibleCount(Election $election): int
    {
        return $election->voters()->where('is_active', true)->count();
    }
}
