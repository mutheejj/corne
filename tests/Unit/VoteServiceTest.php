<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use App\Services\VoteService;

it('can cast a vote', function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create(['election_id' => $election->id]);
    $candidate = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $voter = User::factory()->voter()->create();
    $election->voters()->attach($voter->id);

    $service = app(VoteService::class);
    $vote = $service->castVote($voter, $election, $position, $candidate->id);

    expect($vote->candidate_id)->toBe($candidate->id);
    expect($vote->verification_code)->toHaveLength(16);
});

it('prevents double voting', function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create(['election_id' => $election->id]);
    $candidates = Candidate::factory()->approved()->count(2)->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $voter = User::factory()->voter()->create();
    $election->voters()->attach($voter->id);

    $service = app(VoteService::class);
    $service->castVote($voter, $election, $position, $candidates[0]->id);

    expect(fn () => $service->castVote($voter, $election, $position, $candidates[1]->id))
        ->toThrow(DomainException::class);
});

it('encrypts vote choices', function () {
    $service = app(VoteService::class);
    $encrypted = $service->encryptChoice(42, 'TESTCODE12345678');

    expect($encrypted)->not->toBe('42');
    expect($service->decryptChoice($encrypted, 'TESTCODE12345678'))->toBe(42);
});

it('can verify vote by code', function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create(['election_id' => $election->id]);
    $candidate = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $voter = User::factory()->voter()->create();
    $election->voters()->attach($voter->id);

    $service = app(VoteService::class);
    $vote = $service->castVote($voter, $election, $position, $candidate->id);
    $result = $service->verifyVote($vote->verification_code);

    expect($result)->not->toBeNull();
    expect($result['election_title'])->toBe($election->title);
});

it('can tally position votes', function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create(['election_id' => $election->id]);
    $candidates = Candidate::factory()->approved()->count(2)->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $voters = User::factory()->voter()->count(3)->create();
    $election->voters()->attach($voters->pluck('id'));

    $service = app(VoteService::class);
    $service->castVote($voters[0], $election, $position, $candidates[0]->id);
    $service->castVote($voters[1], $election, $position, $candidates[0]->id);
    $service->castVote($voters[2], $election, $position, $candidates[1]->id);

    $tally = $service->tallyPosition($position);

    expect($tally)->toHaveCount(2);
    expect($tally->firstWhere('candidate_id', $candidates[0]->id)['vote_count'])->toBe(2);
});

it('can tally election votes', function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    ElectionSetting::factory()->create(['election_id' => $election->id]);
    $candidates = Candidate::factory()->approved()->count(2)->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $voters = User::factory()->voter()->count(2)->create();
    $election->voters()->attach($voters->pluck('id'));

    $service = app(VoteService::class);
    $service->castVote($voters[0], $election, $position, $candidates[0]->id);
    $service->castVote($voters[1], $election, $position, $candidates[1]->id);

    $tally = $service->tallyElection($election);

    expect($tally)->toHaveCount(1);
    expect($tally[0]['total_votes'])->toBe(2);
});
