<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use App\Services\ElectionService;
use App\Services\PositionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->election = Election::factory()->draft()->create([
        'created_by' => $this->admin->id,
        'starts_at' => now()->subHour(),
    ]);
    $this->position = Position::factory()->create(['election_id' => $this->election->id]);
});

test('election can be created via service', function () {
    $this->actingAs($this->admin);

    $service = app(ElectionService::class);
    $election = $service->createElection([
        'title' => 'New Election',
        'description' => 'Test election',
        'type' => 'general',
        'starts_at' => now()->addDays(1),
        'ends_at' => now()->addDays(7),
        'is_anonymous' => true,
        'require_2fa' => false,
    ]);

    expect($election)->toBeInstanceOf(Election::class);
    expect($election->slug)->toBe('new-election');
});

test('election can be started with positions and approved candidates', function () {
    $this->actingAs($this->admin);
    Candidate::factory()->approved()->create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
    ]);

    $service = app(ElectionService::class);
    $election = $service->startElection($this->election);

    expect($election->status)->toBe('active');
});

test('election cannot be started without positions', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->draft()->create([
        'created_by' => $this->admin->id,
        'starts_at' => now()->subHour(),
    ]);

    $service = app(ElectionService::class);

    expect(fn () => $service->startElection($election))
        ->toThrow(\InvalidArgumentException::class);
});

test('election cannot be started without approved candidates', function () {
    $this->actingAs($this->admin);

    $service = app(ElectionService::class);

    expect(fn () => $service->startElection($this->election))
        ->toThrow(\InvalidArgumentException::class);
});

test('election cannot be started in future', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->draft()->create([
        'created_by' => $this->admin->id,
        'starts_at' => now()->addDays(7),
    ]);
    Position::factory()->create(['election_id' => $election->id]);
    Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $election->positions()->first()->id,
    ]);

    $service = app(ElectionService::class);

    expect(fn () => $service->startElection($election))
        ->toThrow(\InvalidArgumentException::class);
});

test('active election can be ended', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $service = app(ElectionService::class);
    $election = $service->endElection($election);

    expect($election->status)->toBe('completed');
});

test('draft election cannot be ended', function () {
    $this->actingAs($this->admin);

    $service = app(ElectionService::class);

    expect(fn () => $service->endElection($this->election))
        ->toThrow(\InvalidArgumentException::class);
});

test('active election can be paused', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $service = app(ElectionService::class);
    $election = $service->pauseElection($election);

    expect($election->status)->toBe('paused');
});

test('paused election can be resumed', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $service = app(ElectionService::class);
    $service->pauseElection($election);
    $election = $service->resumeElection($election);

    expect($election->status)->toBe('active');
});

test('election can be cancelled', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $service = app(ElectionService::class);
    $election = $service->cancelElection($election);

    expect($election->status)->toBe('cancelled');
});

test('results can be published for completed election', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->completed()->create([
        'created_by' => $this->admin->id,
        'results_published_at' => null,
    ]);

    $service = app(ElectionService::class);
    $election = $service->publishResults($election);

    expect($election->results_published_at)->not->toBeNull();
});

test('results cannot be published twice', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->completed()->create([
        'created_by' => $this->admin->id,
        'results_published_at' => now(),
    ]);

    $service = app(ElectionService::class);

    expect(fn () => $service->publishResults($election))
        ->toThrow(\InvalidArgumentException::class);
});

test('results cannot be published for active election', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $service = app(ElectionService::class);

    expect(fn () => $service->publishResults($election))
        ->toThrow(\InvalidArgumentException::class);
});

test('draft election can be deleted', function () {
    $this->actingAs($this->admin);

    $service = app(ElectionService::class);
    $result = $service->deleteElection($this->election);

    expect($result)->toBeTrue();
    expect(Election::find($this->election->id))->toBeNull();
});

test('active election cannot be deleted', function () {
    $this->actingAs($this->admin);
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $service = app(ElectionService::class);

    expect(fn () => $service->deleteElection($election))
        ->toThrow(\InvalidArgumentException::class);
});

test('position can be created', function () {
    $this->actingAs($this->admin);

    $service = app(PositionService::class);
    $position = $service->createPosition($this->election, [
        'title' => 'Vice President',
        'description' => 'VP role',
        'max_votes' => 1,
    ]);

    expect($position->title)->toBe('Vice President');
    expect($position->election_id)->toBe($this->election->id);
});

test('position can be updated', function () {
    $this->actingAs($this->admin);

    $service = app(PositionService::class);
    $position = $service->updatePosition($this->position, [
        'title' => 'Updated Title',
        'description' => $this->position->description,
        'max_votes' => 1,
    ]);

    expect($position->title)->toBe('Updated Title');
});

test('position can be deleted', function () {
    $this->actingAs($this->admin);

    $service = app(PositionService::class);
    $result = $service->deletePosition($this->position);

    expect($result)->toBeTrue();
});

test('voters can be added to election', function () {
    $this->actingAs($this->admin);
    $voters = User::factory()->voter()->count(5)->create();

    $service = app(ElectionService::class);
    $count = $service->addVoters($this->election, ['user_ids' => $voters->pluck('id')->toArray()]);

    expect($count)->toBe(5);
    expect($this->election->voters)->toHaveCount(5);
});

test('voter can be removed from election', function () {
    $this->actingAs($this->admin);
    $voter = User::factory()->voter()->create();
    $this->election->voters()->attach($voter->id);

    $service = app(ElectionService::class);
    $result = $service->removeVoter($this->election, $voter);

    expect($result)->toBeTrue();
    expect($this->election->voters()->where('user_id', $voter->id)->exists())->toBeFalse();
});

test('election settings are created with election', function () {
    $this->actingAs($this->admin);

    $service = app(ElectionService::class);
    $election = $service->createElection([
        'title' => 'Settings Test',
        'description' => 'Test',
        'type' => 'general',
        'starts_at' => now()->addDays(1),
        'ends_at' => now()->addDays(7),
        'is_anonymous' => true,
        'require_2fa' => false,
    ]);

    expect($election->settings()->first())->not->toBeNull();
});

test('election lifecycle from draft to completed', function () {
    $this->actingAs($this->admin);
    Candidate::factory()->approved()->create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
    ]);

    $service = app(ElectionService::class);

    expect($this->election->status)->toBe('draft');

    $election = $service->startElection($this->election);
    expect($election->status)->toBe('active');

    $election = $service->endElection($election);
    expect($election->status)->toBe('completed');

    $election = $service->publishResults($election);
    expect($election->results_published_at)->not->toBeNull();
});
