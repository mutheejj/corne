<?php

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;

beforeEach(function () {
    $admin = User::factory()->admin()->create();
    $election = Election::factory()->active()->create(['created_by' => $admin->id]);
    Position::create([
        'election_id' => $election->id,
        'title' => 'President',
        'description' => 'President of Student Union',
        'max_votes' => 1,
        'sort_order' => 1,
    ]);
});

test('candidate registration page is accessible', function () {
    $response = $this->get(route('register.candidate'));

    $response->assertOk();
    $response->assertViewIs('auth.register-candidate');
});

test('candidate can register with valid data', function () {
    $position = Position::first();

    $response = $this->post(route('register.candidate.post'), [
        'name' => 'Jane Doe',
        'student_id' => 'JDO456-0002/2024',
        'email' => 'jane@university.ac.ke',
        'phone' => '+254798765432',
        'faculty' => 'Computing & Information Technology',
        'department' => 'Computer Science',
        'course' => 'BSc Computer Science',
        'year_of_study' => 3,
        'position_id' => $position->id,
        'manifesto_title' => 'My Vision for Change',
        'manifesto' => str_repeat('I will serve the student body with dedication and integrity. ', 3),
        'slogan' => 'Together We Can',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => true,
    ]);

    $response->assertRedirect(route('candidate.dashboard'));
    $this->assertAuthenticated();

    $user = User::where('email', 'jane@university.ac.ke')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('candidate');

    $candidate = Candidate::where('user_id', $user->id)->first();
    expect($candidate)->not->toBeNull();
    expect($candidate->status)->toBe('pending');
    expect($candidate->position_id)->toBe($position->id);
});

test('candidate registration requires position_id', function () {
    $response = $this->post(route('register.candidate.post'), [
        'name' => 'Jane Doe',
        'student_id' => 'JDO456-0002/2024',
        'email' => 'jane@university.ac.ke',
        'phone' => '+254798765432',
        'faculty' => 'Computing & Information Technology',
        'department' => 'Computer Science',
        'course' => 'BSc Computer Science',
        'year_of_study' => 3,
        'manifesto_title' => 'My Vision for Change',
        'manifesto' => str_repeat('I will serve the student body with dedication and integrity. ', 3),
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => true,
    ]);

    $response->assertSessionHasErrors('position_id');
    $this->assertGuest();
});

test('candidate registration requires manifesto minimum 100 characters', function () {
    $position = Position::first();

    $response = $this->post(route('register.candidate.post'), [
        'name' => 'Jane Doe',
        'student_id' => 'JDO456-0002/2024',
        'email' => 'jane@university.ac.ke',
        'phone' => '+254798765432',
        'faculty' => 'Computing & Information Technology',
        'department' => 'Computer Science',
        'course' => 'BSc Computer Science',
        'year_of_study' => 3,
        'position_id' => $position->id,
        'manifesto_title' => 'My Vision for Change',
        'manifesto' => 'short',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => true,
    ]);

    $response->assertSessionHasErrors('manifesto');
});

test('candidate registration requires valid position_id', function () {
    $response = $this->post(route('register.candidate.post'), [
        'name' => 'Jane Doe',
        'student_id' => 'JDO456-0002/2024',
        'email' => 'jane@university.ac.ke',
        'phone' => '+254798765432',
        'faculty' => 'Computing & Information Technology',
        'department' => 'Computer Science',
        'course' => 'BSc Computer Science',
        'year_of_study' => 3,
        'position_id' => 99999,
        'manifesto_title' => 'My Vision for Change',
        'manifesto' => str_repeat('I will serve the student body with dedication and integrity. ', 3),
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'terms' => true,
    ]);

    $response->assertSessionHasErrors('position_id');
});
