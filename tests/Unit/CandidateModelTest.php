<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;

it('has correct fillable attributes', function () {
    $candidate = Candidate::factory()->create();

    expect($candidate->getFillable())->toContain('user_id');
    expect($candidate->getFillable())->toContain('election_id');
    expect($candidate->getFillable())->toContain('position_id');
    expect($candidate->getFillable())->toContain('manifesto_title');
    expect($candidate->getFillable())->toContain('manifesto');
    expect($candidate->getFillable())->toContain('slogan');
    expect($candidate->getFillable())->toContain('status');
    expect($candidate->getFillable())->toContain('photo');
});

it('has user relationship', function () {
    $user = User::factory()->candidate()->create();
    $candidate = Candidate::factory()->create(['user_id' => $user->id]);

    expect($candidate->user->id)->toBe($user->id);
});

it('has election relationship', function () {
    $election = Election::factory()->create();
    $candidate = Candidate::factory()->create(['election_id' => $election->id]);

    expect($candidate->election->id)->toBe($election->id);
});

it('has position relationship', function () {
    $position = Position::factory()->create();
    $candidate = Candidate::factory()->create(['position_id' => $position->id]);

    expect($candidate->position->id)->toBe($position->id);
});

it('can check if approved', function () {
    $approved = Candidate::factory()->approved()->create();
    $pending = Candidate::factory()->pending()->create();

    expect($approved->is_approved)->toBeTrue();
    expect($pending->is_approved)->toBeFalse();
});

it('can check if pending', function () {
    $pending = Candidate::factory()->pending()->create();
    $approved = Candidate::factory()->approved()->create();

    expect($pending->is_pending)->toBeTrue();
    expect($approved->is_pending)->toBeFalse();
});

it('calculates vote count', function () {
    $election = Election::factory()->active()->create();
    $position = Position::factory()->create(['election_id' => $election->id]);
    $candidate = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    Vote::create([
        'election_id' => $election->id,
        'position_id' => $position->id,
        'candidate_id' => $candidate->id,
        'verification_code' => 'CODE1234567890AB',
        'receipt_hash' => hash('sha256', 'test1'),
        'cast_at' => now(),
    ]);

    Vote::create([
        'election_id' => $election->id,
        'position_id' => $position->id,
        'candidate_id' => $candidate->id,
        'verification_code' => 'CODE2234567890AB',
        'receipt_hash' => hash('sha256', 'test2'),
        'cast_at' => now(),
    ]);

    expect($candidate->vote_count)->toBe(2);
});

it('calculates vote percentage', function () {
    $election = Election::factory()->active()->create();
    $position = Position::factory()->create(['election_id' => $election->id]);
    $candidate1 = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);
    $candidate2 = Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    Vote::create([
        'election_id' => $election->id,
        'position_id' => $position->id,
        'candidate_id' => $candidate1->id,
        'verification_code' => 'CODE1234567890AB',
        'receipt_hash' => hash('sha256', 'test1'),
        'cast_at' => now(),
    ]);

    Vote::create([
        'election_id' => $election->id,
        'position_id' => $position->id,
        'candidate_id' => $candidate1->id,
        'verification_code' => 'CODE2234567890AB',
        'receipt_hash' => hash('sha256', 'test2'),
        'cast_at' => now(),
    ]);

    Vote::create([
        'election_id' => $election->id,
        'position_id' => $position->id,
        'candidate_id' => $candidate2->id,
        'verification_code' => 'CODE3234567890AB',
        'receipt_hash' => hash('sha256', 'test3'),
        'cast_at' => now(),
    ]);

    expect($candidate1->vote_percentage)->toBe(66.67);
});
