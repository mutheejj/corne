<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\VoteRecord;
use Illuminate\Support\Carbon;

it('has correct fillable attributes', function () {
    $user = User::factory()->create();

    expect($user->getFillable())->toContain('name');
    expect($user->getFillable())->toContain('email');
    expect($user->getFillable())->toContain('password');
    expect($user->getFillable())->toContain('role');
    expect($user->getFillable())->toContain('student_id');
    expect($user->getFillable())->toContain('phone');
    expect($user->getFillable())->toContain('faculty');
    expect($user->getFillable())->toContain('department');
    expect($user->getFillable())->toContain('course');
    expect($user->getFillable())->toContain('year_of_study');
    expect($user->getFillable())->toContain('avatar');
    expect($user->getFillable())->toContain('is_active');
});

it('hashes password on save', function () {
    $user = User::factory()->create(['password' => 'secret123']);

    expect($user->password)->not->toBe('secret123');
    expect(Hash::check('secret123', $user->password))->toBeTrue();
});

it('casts email_verified_at to datetime', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);
});

it('can check if admin', function () {
    $admin = User::factory()->admin()->create();
    $voter = User::factory()->voter()->create();

    expect($admin->is_admin)->toBeTrue();
    expect($voter->is_admin)->toBeFalse();
});

it('can check if voter', function () {
    $voter = User::factory()->voter()->create();
    $admin = User::factory()->admin()->create();

    expect($voter->is_voter)->toBeTrue();
    expect($admin->is_voter)->toBeFalse();
});

it('can check if candidate', function () {
    $candidate = User::factory()->candidate()->create();
    $voter = User::factory()->voter()->create();

    expect($candidate->is_candidate)->toBeTrue();
    expect($voter->is_candidate)->toBeFalse();
});

it('has candidates relationship', function () {
    $user = User::factory()->candidate()->create();
    Candidate::factory()->create(['user_id' => $user->id]);

    expect($user->candidates)->toHaveCount(1);
});

it('has elections relationship', function () {
    $user = User::factory()->voter()->create();
    $election = Election::factory()->active()->create();
    $election->voters()->attach($user->id);

    expect($user->elections)->toHaveCount(1);
});

it('has vote records relationship', function () {
    $user = User::factory()->voter()->create();
    $election = Election::factory()->active()->create();
    $position = Position::factory()->create(['election_id' => $election->id]);

    VoteRecord::create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'position_id' => $position->id,
        'verification_code' => 'TESTCODE12345678',
        'receipt_hash' => 'hash123',
        'voted_at' => now(),
    ]);

    expect($user->voteRecords)->toHaveCount(1);
});

it('can check if voted in election', function () {
    $user = User::factory()->voter()->create();
    $election = Election::factory()->active()->create();
    $position = Position::factory()->create(['election_id' => $election->id]);

    expect($user->hasVotedIn($election))->toBeFalse();

    VoteRecord::create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'position_id' => $position->id,
        'verification_code' => 'TESTCODE12345678',
        'receipt_hash' => 'hash123',
        'voted_at' => now(),
    ]);

    expect($user->hasVotedIn($election))->toBeTrue();
});

it('can check if voted for position', function () {
    $user = User::factory()->voter()->create();
    $election = Election::factory()->active()->create();
    $position = Position::factory()->create(['election_id' => $election->id]);

    expect($user->hasVotedForPosition($position))->toBeFalse();

    VoteRecord::create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'position_id' => $position->id,
        'verification_code' => 'TESTCODE12345678',
        'receipt_hash' => 'hash123',
        'voted_at' => now(),
    ]);

    expect($user->hasVotedForPosition($position))->toBeTrue();
});
