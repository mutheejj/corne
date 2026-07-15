<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use Illuminate\Support\Carbon;

it('has correct fillable attributes', function () {
    $election = Election::factory()->create();

    expect($election->getFillable())->toContain('title');
    expect($election->getFillable())->toContain('slug');
    expect($election->getFillable())->toContain('description');
    expect($election->getFillable())->toContain('status');
    expect($election->getFillable())->toContain('type');
    expect($election->getFillable())->toContain('starts_at');
    expect($election->getFillable())->toContain('ends_at');
    expect($election->getFillable())->toContain('is_anonymous');
    expect($election->getFillable())->toContain('require_2fa');
    expect($election->getFillable())->toContain('created_by');
});

it('casts dates correctly', function () {
    $election = Election::factory()->create();

    expect($election->starts_at)->toBeInstanceOf(Carbon::class);
    expect($election->ends_at)->toBeInstanceOf(Carbon::class);
    expect($election->is_anonymous)->toBeBool();
    expect($election->require_2fa)->toBeBool();
});

it('has positions relationship', function () {
    $election = Election::factory()->create();
    Position::factory()->count(3)->create(['election_id' => $election->id]);

    expect($election->positions)->toHaveCount(3);
});

it('has candidates relationship', function () {
    $election = Election::factory()->create();
    Candidate::factory()->count(2)->create(['election_id' => $election->id]);

    expect($election->candidates)->toHaveCount(2);
});

it('has votes relationship', function () {
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
        'receipt_hash' => hash('sha256', 'test'),
        'cast_at' => now(),
    ]);

    expect($election->votes)->toHaveCount(1);
});

it('has voters relationship', function () {
    $election = Election::factory()->create();
    $voter = User::factory()->voter()->create();
    $election->voters()->attach($voter->id);

    expect($election->voters)->toHaveCount(1);
});

it('can check if ongoing', function () {
    $active = Election::factory()->active()->create();
    $completed = Election::factory()->completed()->create();

    expect($active->is_ongoing)->toBeTrue();
    expect($completed->is_ongoing)->toBeFalse();
});

it('can check if completed', function () {
    $completed = Election::factory()->completed()->create();
    $active = Election::factory()->active()->create();

    expect($completed->is_completed)->toBeTrue();
    expect($active->is_completed)->toBeFalse();
});

it('can check if upcoming', function () {
    $draft = Election::factory()->draft()->create();
    $completed = Election::factory()->completed()->create();

    expect($draft->is_upcoming)->toBeTrue();
    expect($completed->is_upcoming)->toBeFalse();
});

it('calculates turnout percentage', function () {
    $election = Election::factory()->active()->create();
    $voters = User::factory()->voter()->count(10)->create();
    $election->voters()->attach($voters->pluck('id'));
    $position = Position::factory()->create(['election_id' => $election->id]);

    VoteRecord::create([
        'user_id' => $voters[0]->id,
        'election_id' => $election->id,
        'position_id' => $position->id,
        'verification_code' => 'CODE1234567890AB',
        'receipt_hash' => 'hash',
        'voted_at' => now(),
    ]);

    expect($election->turnout_percentage)->toBe(10.0);
});

it('gets time remaining', function () {
    $election = Election::factory()->active()->create([
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addHours(2),
    ]);

    expect($election->time_remaining)->toBeGreaterThan(0);
    expect($election->time_remaining)->toBeInt();
});
