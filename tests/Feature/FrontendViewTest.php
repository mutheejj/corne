<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->voter = User::factory()->voter()->create();
    $this->candidateUser = User::factory()->candidate()->create();
});

test('renders admin dashboard view', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard.admin.dashboard');
});

test('renders voter dashboard view', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard.voter.dashboard');
});

test('renders candidate dashboard view', function () {
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);
    $position = Position::create([
        'election_id' => $election->id,
        'title' => 'President',
        'description' => 'President',
        'max_votes' => 1,
        'sort_order' => 1,
    ]);
    Candidate::create([
        'user_id' => $this->candidateUser->id,
        'election_id' => $election->id,
        'position_id' => $position->id,
        'manifesto_title' => 'My Vision',
        'manifesto' => str_repeat('I will serve. ', 10),
        'slogan' => 'Together',
        'status' => 'pending',
    ]);

    $this->actingAs($this->candidateUser)
        ->get(route('candidate.dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard.candidate.dashboard');
});

test('renders voter elections list view', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.elections.index'))
        ->assertOk()
        ->assertViewIs('dashboard.voter.elections');
});

test('renders voter profile view', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.profile'))
        ->assertOk()
        ->assertViewIs('dashboard.voter.profile');
});

test('renders admin elections list view', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.elections.index'))
        ->assertOk()
        ->assertViewIs('dashboard.admin.elections');
});

test('admin dashboard shows correct sidebar links', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

    $response->assertSee('Dashboard');
    $response->assertSee('Elections');
    $response->assertSee('Voters');
    $response->assertSee('Audit Logs');
});

test('voter dashboard shows correct sidebar links', function () {
    $response = $this->actingAs($this->voter)->get(route('voter.dashboard'));

    $response->assertSee('Dashboard');
    $response->assertSee('Elections');
    $response->assertSee('Vote History');
    $response->assertSee('Profile');
});
