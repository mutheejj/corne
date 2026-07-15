<?php

namespace App\Policies;

use App\Models\Election;
use App\Models\Position;
use App\Models\User;

class PositionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Position $position): bool
    {
        return true;
    }

    public function create(User $user, Election $election): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Position $position): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Position $position): bool
    {
        return $user->is_admin;
    }
}
