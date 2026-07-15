<?php

use App\Models\User;

describe('Login', function () {
    test('admin can login with email', function () {
        $admin = User::factory()->admin()->create(['password' => 'password']);

        $response = $this->post(route('login.post'), [
            'identifier' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    });

    test('voter can login with email', function () {
        $voter = User::factory()->voter()->create(['password' => 'password']);

        $response = $this->post(route('login.post'), [
            'identifier' => $voter->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('voter.dashboard'));
        $this->assertAuthenticatedAs($voter);
    });

    test('voter can login with student id', function () {
        $voter = User::factory()->voter()->create([
            'password' => 'password',
            'student_id' => 'ABC123-1234/2023',
        ]);

        $response = $this->post(route('login.post'), [
            'identifier' => 'ABC123-1234/2023',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('voter.dashboard'));
        $this->assertAuthenticatedAs($voter);
    });

    test('candidate can login with email', function () {
        $candidate = User::factory()->candidate()->create(['password' => 'password']);

        $response = $this->post(route('login.post'), [
            'identifier' => $candidate->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('candidate.dashboard'));
        $this->assertAuthenticatedAs($candidate);
    });

    test('cannot login with invalid password', function () {
        $user = User::factory()->voter()->create();

        $response = $this->post(route('login.post'), [
            'identifier' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    });

    test('cannot login with non-existent email', function () {
        $response = $this->post(route('login.post'), [
            'identifier' => 'nobody@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    });

    test('login page is accessible', function () {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertViewIs('auth.login');
    });
});

describe('Logout', function () {
    test('authenticated user can logout', function () {
        $user = User::factory()->voter()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    });

    test('guest cannot logout', function () {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
    });
});
