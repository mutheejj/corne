<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;

class VotePolicy
{
    public function cast(User $user, Position $position): bool
    {
        $election = $position->election;

        return $user->is_voter
            && $election->status === 'active'
            && $election->voters()->where('user_id', $user->id)->exists()
            && ! $user->hasVotedForPosition($position);
    }

    public function verify(User $user, string $verificationCode): bool
    {
        return $user->voteRecords()
            ->where('verification_code', $verificationCode)
            ->exists();
    }
}
