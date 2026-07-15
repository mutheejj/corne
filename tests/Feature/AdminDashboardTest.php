<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('admin can view dashboard', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('admin can view elections list', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.elections.index'))
        ->assertOk();
});

test('admin can view create election form', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.elections.create'))
        ->assertOk();
});

test('admin can create election', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.elections.store'), [
            'title' => 'Student Council 2026',
            'description' => 'Annual student council election',
            'type' => 'general',
            'starts_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'ends_at' => now()->addDays(14)->format('Y-m-d\TH:i'),
            'is_anonymous' => true,
            'require_2fa' => false,
        ])
        ->assertRedirect();
});

test('admin can view election details', function () {
    $election = Election::factory()->draft()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.elections.show', $election))
        ->assertOk();
});

test('admin can view edit election form', function () {
    $election = Election::factory()->draft()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.elections.edit', $election))
        ->assertOk();
});

test('admin can update election', function () {
    $election = Election::factory()->draft()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->put(route('admin.elections.update', $election), [
            'title' => 'Updated Title',
            'description' => $election->description,
            'type' => $election->type,
            'starts_at' => $election->starts_at->format('Y-m-d\TH:i'),
            'ends_at' => $election->ends_at->format('Y-m-d\TH:i'),
            'is_anonymous' => true,
            'require_2fa' => false,
        ])
        ->assertRedirect();
});

test('admin can delete draft election', function () {
    $election = Election::factory()->draft()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.elections.destroy', $election))
        ->assertRedirect();
});

test('admin can start election', function () {
    $election = Election::factory()->draft()->create([
        'created_by' => $this->admin->id,
        'starts_at' => now()->subHour(),
    ]);
    $position = Position::factory()->create(['election_id' => $election->id]);
    Candidate::factory()->approved()->create([
        'election_id' => $election->id,
        'position_id' => $position->id,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.elections.start', $election))
        ->assertRedirect();
});

test('admin can end election', function () {
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->post(route('admin.elections.end', $election))
        ->assertRedirect();
});

test('admin can pause and resume election', function () {
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->post(route('admin.elections.pause', $election))
        ->assertRedirect();

    expect($election->fresh()->status)->toBe('paused');

    $this->actingAs($this->admin)
        ->post(route('admin.elections.resume', $election))
        ->assertRedirect();

    expect($election->fresh()->status)->toBe('active');
});

test('admin can cancel election', function () {
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->post(route('admin.elections.cancel', $election))
        ->assertRedirect();

    expect($election->fresh()->status)->toBe('cancelled');
});

test('admin can publish results', function () {
    $election = Election::factory()->completed()->create([
        'created_by' => $this->admin->id,
        'results_published_at' => null,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.elections.publish-results', $election))
        ->assertRedirect();

    expect($election->fresh()->results_published_at)->not->toBeNull();
});

test('admin can view results', function () {
    $election = Election::factory()->completed()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.elections.results', $election))
        ->assertOk();
});

test('admin can export CSV', function () {
    $election = Election::factory()->completed()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.elections.results.csv', $election))
        ->assertOk();
});

test('admin can export PDF', function () {
    $election = Election::factory()->completed()->create(['created_by' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.elections.results.pdf', $election))
        ->assertOk();
});

test('admin can view candidates list', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.candidates.index'))
        ->assertOk();
});

test('admin can approve candidate', function () {
    $candidate = Candidate::factory()->pending()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.candidates.approve', $candidate))
        ->assertRedirect();

    expect($candidate->fresh()->status)->toBe('approved');
});

test('admin can reject candidate', function () {
    $candidate = Candidate::factory()->pending()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.candidates.reject', $candidate), [
            'rejection_reason' => 'Does not meet requirements',
        ])
        ->assertRedirect();

    expect($candidate->fresh()->status)->toBe('rejected');
});

test('admin can view voters list', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.voters.index'))
        ->assertOk();
});

test('admin can view audit logs', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.audit-logs.index'))
        ->assertOk();
});

test('admin can view security report', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.security-report'))
        ->assertOk();
});
