<?php

use App\Models\Election;
use App\Models\User;

describe('ElectionActive Middleware', function () {
    test('active election allows access', function () {
        $election = Election::factory()->active()->create(['created_by' => User::factory()->admin()->create()->id]);

        $response = $this->actingAs(User::factory()->voter()->create())
            ->get("/test-election-active/{$election->id}");

        $response->assertOk();
    });

    test('draft election blocks access', function () {
        $election = Election::factory()->draft()->create(['created_by' => User::factory()->admin()->create()->id]);

        $response = $this->actingAs(User::factory()->voter()->create())
            ->get("/test-election-active/{$election->id}");

        $response->assertForbidden();
    });

    test('completed election blocks access', function () {
        $election = Election::factory()->completed()->create(['created_by' => User::factory()->admin()->create()->id]);

        $response = $this->actingAs(User::factory()->voter()->create())
            ->get("/test-election-active/{$election->id}");

        $response->assertForbidden();
    });
});
