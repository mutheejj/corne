<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

describe('Email Verification', function () {
    test('verification notice page is accessible when authenticated', function () {
        $user = User::factory()->voter()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertOk();
        $response->assertViewIs('auth.verify-email');
    });

    test('verification notice redirects verified user to dashboard', function () {
        $user = User::factory()->voter()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertRedirect(route('voter.dashboard'));
    });

    test('email can be verified', function () {
        Event::fake();
        $user = User::factory()->voter()->unverified()->create();

        $url = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $response = $this->actingAs($user)->get($url);

        Event::assertDispatched(Verified::class);
        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    });

    test('verification link with invalid hash fails', function () {
        $user = User::factory()->voter()->unverified()->create();

        $url = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => 'invalid-hash',
        ]);

        $response = $this->actingAs($user)->get($url);

        $response->assertForbidden();
    });

    test('resend verification link', function () {
        Notification::fake();
        $user = User::factory()->voter()->unverified()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertSessionHas('status');
    });

    test('resend verification redirects already verified user', function () {
        $user = User::factory()->voter()->create();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect(route('voter.dashboard'));
    });
});
