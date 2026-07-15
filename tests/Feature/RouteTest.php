<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->voter = User::factory()->voter()->create();
    $this->candidate = User::factory()->candidate()->create();
});

test('public page routes are accessible', function () {
    $this->get(route('home'))->assertOk();
    $this->get(route('about'))->assertOk();
    $this->get(route('features'))->assertOk();
    $this->get(route('contact'))->assertOk();
    $this->get(route('privacy'))->assertOk();
    $this->get(route('terms'))->assertOk();
});

test('auth routes are accessible to guests', function () {
    $this->get(route('login'))->assertOk();
    $this->get(route('register'))->assertOk();
    $this->get(route('register.candidate'))->assertOk();
    $this->get(route('password.request'))->assertOk();
});

test('guest routes redirect authenticated users', function () {
    $this->actingAs($this->voter)
        ->get(route('login'))
        ->assertRedirect();
});

test('protected dashboard routes redirect guests to login', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    $this->get(route('voter.dashboard'))->assertRedirect(route('login'));
    $this->get(route('candidate.dashboard'))->assertRedirect(route('login'));
});

test('admin routes require admin role', function () {
    $this->actingAs($this->voter)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('voter routes require voter role', function () {
    $this->actingAs($this->admin)
        ->get(route('voter.dashboard'))
        ->assertForbidden();
});

test('candidate routes require candidate role', function () {
    $this->actingAs($this->voter)
        ->get(route('candidate.dashboard'))
        ->assertForbidden();
});

test('admin can access admin dashboard', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('voter can access voter dashboard', function () {
    $this->actingAs($this->voter)
        ->get(route('voter.dashboard'))
        ->assertOk();
});

test('candidate can access candidate dashboard', function () {
    $election = Election::factory()->active()->create(['created_by' => $this->admin->id]);
    $position = Position::create([
        'election_id' => $election->id,
        'title' => 'President',
        'description' => 'President',
        'max_votes' => 1,
        'sort_order' => 1,
    ]);
    Candidate::create([
        'user_id' => $this->candidate->id,
        'election_id' => $election->id,
        'position_id' => $position->id,
        'manifesto_title' => 'My Vision',
        'manifesto' => str_repeat('I will serve. ', 10),
        'slogan' => 'Together',
        'status' => 'pending',
    ]);

    $this->actingAs($this->candidate)
        ->get(route('candidate.dashboard'))
        ->assertOk();
});

test('contact form submission redirects back with status', function () {
    $this->post(route('contact.post'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Inquiry',
        'message' => 'I have a question about your platform.',
    ])
        ->assertRedirect()
        ->assertSessionHas('status');
});

test('logout redirects to home', function () {
    $this->actingAs($this->voter)
        ->post(route('logout'))
        ->assertRedirect(route('home'));
});
