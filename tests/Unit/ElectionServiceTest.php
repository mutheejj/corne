<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Services\ElectionService;

it('can create election', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);

    $service = app(ElectionService::class);
    $election = $service->createElection([
        'title' => 'Student Council 2026',
        'description' => 'Annual election',
        'status' => 'draft',
        'type' => 'general',
        'starts_at' => now()->addDays(7),
        'ends_at' => now()->addDays(14),
        'is_anonymous' => true,
        'require_2fa' => false,
    ]);

    expect($election->title)->toBe('Student Council 2026');
    expect($election->slug)->toBe('student-council-2026');
    expect($election->settings()->first())->not->toBeNull();
});

it('can start election', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $election = Election::factory()->draft()->create([
        'created_by' => $admin->id,
        'starts_at' => now()->subHour(),
    ]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    $service = app(ElectionService::class);
    $election = $service->startElection($election);

    expect($election->status)->toBe('active');
});

it('prevents starting without positions', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $election = Election::factory()->draft()->create(['created_by' => $admin->id]);

    $service = app(ElectionService::class);

    expect(fn () => $service->startElection($election))
        ->toThrow(InvalidArgumentException::class);
});

it('can end election', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);

    $service = app(ElectionService::class);
    $election = $service->endElection($election);

    expect($election->status)->toBe('completed');
});

it('can publish results', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $election = Election::factory()->completed()->create([
        'created_by' => $admin->id,
        'results_published_at' => null,
    ]);

    $service = app(ElectionService::class);
    $election = $service->publishResults($election);

    expect($election->results_published_at)->not->toBeNull();
});

it('can add voters', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    $voters = User::factory()->voter()->count(3)->create();

    $service = app(ElectionService::class);
    $service->addVoters($election, ['user_ids' => $voters->pluck('id')->toArray()]);

    expect($election->voters)->toHaveCount(3);
});
