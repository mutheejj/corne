<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;

describe('Password Reset', function () {
    test('forgot password page is accessible', function () {
        $response = $this->get(route('password.request'));

        $response->assertOk();
        $response->assertViewIs('auth.forgot-password');
    });

    test('forgot password sends reset link', function () {
        Notification::fake();
        $user = User::factory()->voter()->create();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
    });

    test('forgot password does not reveal non-existent email', function () {
        $response = $this->post(route('password.email'), [
            'email' => 'nobody@example.com',
        ]);

        $response->assertSessionHas('status');
    });

    test('forgot password requires email', function () {
        $response = $this->post(route('password.email'), []);

        $response->assertSessionHasErrors('email');
    });

    test('reset password page is accessible with token', function () {
        $token = 'test-token';

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => 'test@example.com']));

        $response->assertOk();
        $response->assertViewIs('auth.reset-password');
    });

    test('reset password with valid token', function () {
        $user = User::factory()->voter()->create(['password' => 'old-password']);
        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status');
    });

    test('reset password with invalid token fails', function () {
        $user = User::factory()->voter()->create();

        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertSessionHasErrors('email');
    });

    test('reset password requires strong password', function () {
        $user = User::factory()->voter()->create();
        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('password');
    });
});
