<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;

function adminUser(): User
{
    return User::factory()->admin()->create();
}

function voterUser(): User
{
    return User::factory()->voter()->create();
}

function candidateUser(): User
{
    return User::factory()->candidate()->create();
}

function activeElection(): Election
{
    return Election::factory()->active()->create();
}

function electionWithPositions(): array
{
    $election = Election::factory()->active()->create();
    $positions = Position::factory()->count(3)->create(['election_id' => $election->id]);

    return [$election, $positions];
}

function electionWithCandidates(): array
{
    [$election, $positions] = electionWithPositions();
    $candidates = Candidate::factory()->approved()->count(5)->create([
        'election_id' => $election->id,
        'position_id' => $positions[0]->id,
    ]);

    return [$election, $positions, $candidates];
}

function actingAsAdmin(): TestCase
{
    return test()->actingAs(adminUser());
}

function actingAsVoter(): TestCase
{
    return test()->actingAs(voterUser());
}

function actingAsCandidate(): TestCase
{
    return test()->actingAs(candidateUser());
}
