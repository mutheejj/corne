<?php

namespace App\Policies;

use App\Models\Election;
use App\Models\User;

class ElectionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Election $election): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($user->is_candidate && $election->candidates()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return in_array($election->status, ['active', 'completed']);
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Election $election): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Election $election): bool
    {
        return $user->is_admin;
    }

    public function start(User $user, Election $election): bool
    {
        return $user->is_admin;
    }

    public function end(User $user, Election $election): bool
    {
        return $user->is_admin;
    }

    public function publishResults(User $user, Election $election): bool
    {
        return $user->is_admin;
    }

    public function vote(User $user, Election $election): bool
    {
        return $user->is_voter
            && $election->status === 'active'
            && $election->voters()->where('user_id', $user->id)->exists()
            && ! $election->hasVoted($user);
    }
}
