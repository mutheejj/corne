<?php

use App\Models\User;

describe('EnsureActiveUser Middleware', function () {
    test('active user can access protected route', function () {
        $user = User::factory()->voter()->create(['is_active' => true]);

        $response = $this->actingAs($user)->get('/test-active-user');

        $response->assertOk();
    });

    test('inactive user is logged out and redirected', function () {
        $user = User::factory()->voter()->create(['is_active' => false]);

        $response = $this->actingAs($user)->get('/test-active-user');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertGuest();
    });
});
