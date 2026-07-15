<?php

namespace App\Policies;

use App\Models\Candidate;
use App\Models\User;

class CandidatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Candidate $candidate): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Candidate $candidate): bool
    {
        return $candidate->user_id === $user->id || $user->is_admin;
    }

    public function delete(User $user, Candidate $candidate): bool
    {
        return $user->is_admin;
    }

    public function approve(User $user, Candidate $candidate): bool
    {
        return $user->is_admin;
    }

    public function reject(User $user, Candidate $candidate): bool
    {
        return $user->is_admin;
    }

    public function disqualify(User $user, Candidate $candidate): bool
    {
        return $user->is_admin;
    }
}
