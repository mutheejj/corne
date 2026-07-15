<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->candidateUser = User::factory()->candidate()->create();
    $this->election = Election::factory()->active()->create(['created_by' => $this->admin->id]);
    $this->position = Position::factory()->create(['election_id' => $this->election->id]);
    $this->candidate = Candidate::factory()->approved()->create([
        'user_id' => $this->candidateUser->id,
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
    ]);
});

test('candidate can view dashboard', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('candidate.dashboard'))
        ->assertOk();
});

test('candidate can view profile', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('candidate.profile'))
        ->assertOk();
});

test('candidate can update profile', function () {
    $this->election->update(['status' => 'scheduled']);

    $this->actingAs($this->candidateUser)
        ->put(route('candidate.profile.update'), [
            'manifesto_title' => 'Updated Vision',
            'manifesto' => str_repeat('I will serve. ', 10),
            'slogan' => 'Forward Together',
        ])
        ->assertRedirect();

    expect($this->candidate->fresh()->manifesto_title)->toBe('Updated Vision');
});

test('candidate can view their election', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('candidate.election'))
        ->assertOk();
});

test('candidate can view their position', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('candidate.position'))
        ->assertOk();
});

test('candidate cannot view results before publication', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('candidate.results'))
        ->assertRedirect();
});

test('candidate can view results after publication', function () {
    $this->election->update([
        'status' => 'completed',
        'results_published_at' => now(),
    ]);

    $this->actingAs($this->candidateUser)
        ->get(route('candidate.results'))
        ->assertOk();
});

test('candidate cannot access admin dashboard', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('candidate cannot access voter dashboard', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('voter.dashboard'))
        ->assertForbidden();
});

test('candidate without profile gets redirected from dashboard', function () {
    $userWithoutCandidate = User::factory()->candidate()->create();

    $this->actingAs($userWithoutCandidate)
        ->get(route('candidate.dashboard'))
        ->assertRedirect();
});

test('candidate can withdraw', function () {
    $this->actingAs($this->candidateUser)
        ->post(route('candidate.withdraw'))
        ->assertRedirect();
});

test('candidate cannot withdraw from completed election', function () {
    $this->election->update(['status' => 'completed']);

    $this->actingAs($this->candidateUser)
        ->post(route('candidate.withdraw'))
        ->assertRedirect();
});

test('candidate dashboard shows election info', function () {
    $this->actingAs($this->candidateUser)
        ->get(route('candidate.dashboard'))
        ->assertSee($this->election->title);
});

test('candidate can view position with competitors', function () {
    Candidate::factory()->approved()->count(2)->create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
    ]);

    $this->actingAs($this->candidateUser)
        ->get(route('candidate.position'))
        ->assertOk();
});

test('inactive candidate cannot access dashboard', function () {
    $inactiveCandidate = User::factory()->candidate()->inactive()->create();

    $this->actingAs($inactiveCandidate)
        ->get(route('candidate.dashboard'))
        ->assertRedirect(route('login'));
});
