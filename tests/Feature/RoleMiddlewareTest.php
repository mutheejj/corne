<?php

use App\Models\User;

describe('Role Middleware', function () {
    test('admin can access admin-only route', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->get('/test-role-admin');

        $response->assertOk();
    });

    test('voter cannot access admin-only route', function () {
        $voter = User::factory()->voter()->create();

        $response = $this->actingAs($voter)
            ->get('/test-role-admin');

        $response->assertForbidden();
    });

    test('candidate cannot access admin-only route', function () {
        $candidate = User::factory()->candidate()->create();

        $response = $this->actingAs($candidate)
            ->get('/test-role-admin');

        $response->assertForbidden();
    });

    test('voter can access voter-only route', function () {
        $voter = User::factory()->voter()->create();

        $response = $this->actingAs($voter)
            ->get('/test-role-voter');

        $response->assertOk();
    });

    test('admin can access voter route when allowed multiple roles', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->get('/test-role-admin-voter');

        $response->assertOk();
    });
});
