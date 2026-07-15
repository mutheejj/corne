<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use App\Services\VoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->voter = User::factory()->voter()->create();
    $this->election = Election::factory()->active()->create(['created_by' => $this->admin->id]);
    $this->position = Position::factory()->create(['election_id' => $this->election->id]);
    ElectionSetting::factory()->create(['election_id' => $this->election->id]);
    $this->candidates = Candidate::factory()->approved()->count(3)->create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
    ]);
    $this->election->voters()->attach($this->voter->id);
});

test('voter can view dashboard', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.dashboard'))
        ->assertOk();
});

test('voter can view elections list', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.elections.index'))
        ->assertOk();
});

test('voter can view election details', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.elections.show', $this->election))
        ->assertOk();
});

test('voter can view ballot', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.ballot.show', [$this->election, $this->position]))
        ->assertOk();
});

test('voter can cast vote', function () {
    $this->actingAs($this->voter)
        ->post(route('voter.votes.cast', [$this->election, $this->position]), [
            'candidate_id' => $this->candidates[0]->id,
            'position_id' => $this->position->id,
        ])
        ->assertRedirect();
});

test('voter cannot vote twice for same position', function () {
    $service = app(VoteService::class);
    $service->castVote($this->voter, $this->election, $this->position, $this->candidates[0]->id);

    $this->actingAs($this->voter)
        ->post(route('voter.votes.cast', [$this->election, $this->position]), [
            'candidate_id' => $this->candidates[1]->id,
            'position_id' => $this->position->id,
        ])
        ->assertForbidden();
});

test('voter can view vote confirmation', function () {
    $service = app(VoteService::class);
    $vote = $service->castVote($this->voter, $this->election, $this->position, $this->candidates[0]->id);

    $this->actingAs($this->voter)
        ->get(route('voter.votes.confirmation', [$this->election, $this->position, $vote->verification_code]))
        ->assertOk();
});

test('voter can verify vote', function () {
    $service = app(VoteService::class);
    $vote = $service->castVote($this->voter, $this->election, $this->position, $this->candidates[0]->id);

    $this->actingAs($this->voter)
        ->post(route('voter.verify-vote.result'), [
            'verification_code' => $vote->verification_code,
        ])
        ->assertOk();
});

test('voter can view vote history', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.vote-history'))
        ->assertOk();
});

test('voter can view profile', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.profile'))
        ->assertOk();
});

test('voter can update profile', function () {
    $this->actingAs($this->voter)
        ->put(route('voter.profile.update'), [
            'name' => 'Updated Name',
            'phone' => '0700123456',
        ])
        ->assertRedirect();

    expect($this->voter->fresh()->name)->toBe('Updated Name');
});

test('voter cannot view results before publication', function () {
    $election = Election::factory()->active()->create([
        'created_by' => $this->admin->id,
        'results_published_at' => null,
    ]);

    $this->actingAs($this->voter)
        ->get(route('voter.elections.results', $election))
        ->assertRedirect();
});

test('voter can view results after publication', function () {
    $election = Election::factory()->completed()->create([
        'created_by' => $this->admin->id,
        'results_published_at' => now(),
    ]);

    $this->actingAs($this->voter)
        ->get(route('voter.elections.results', $election))
        ->assertOk();
});

test('voter cannot access admin dashboard', function () {
    $this->actingAs($this->voter)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('voter cannot access candidate dashboard', function () {
    $this->actingAs($this->voter)
        ->get(route('candidate.dashboard'))
        ->assertForbidden();
});

test('non-eligible voter cannot see election', function () {
    $otherVoter = User::factory()->voter()->create();

    $this->actingAs($otherVoter)
        ->get(route('voter.elections.show', $this->election))
        ->assertForbidden();
});

test('voter can abstain when allowed', function () {
    $this->actingAs($this->voter)
        ->post(route('voter.votes.cast', [$this->election, $this->position]), [
            'position_id' => $this->position->id,
            'abstain' => true,
        ])
        ->assertRedirect();
});

test('voter cannot abstain when not allowed', function () {
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create([
        'election_id' => $election->id,
        'allow_abstain' => false,
    ]);
    Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $election->voters()->attach($this->voter->id);

    $this->actingAs($this->voter)
        ->post(route('voter.votes.cast', [$election, $position]), [
            'position_id' => $position->id,
            'abstain' => true,
        ])
        ->assertRedirect();
});

test('inactive voter cannot access dashboard', function () {
    $inactiveVoter = User::factory()->voter()->inactive()->create();

    $this->actingAs($inactiveVoter)
        ->get(route('voter.dashboard'))
        ->assertRedirect(route('login'));
});
