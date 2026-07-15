<?php

use App\Models\Election;
use App\Models\User;

describe('Election Policy', function () {
    test('any user can view any election', function () {
        $user = User::factory()->voter()->create();
        $admin = User::factory()->admin()->create();
        $election = Election::factory()->active()->create(['created_by' => $admin->id]);

        expect($user->can('viewAny', Election::class))->toBeTrue();
        expect($user->can('view', $election))->toBeTrue();
    });

    test('only admin can create elections', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();

        expect($admin->can('create', Election::class))->toBeTrue();
        expect($voter->can('create', Election::class))->toBeFalse();
    });

    test('only admin can update elections', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->active()->create(['created_by' => $admin->id]);

        expect($admin->can('update', $election))->toBeTrue();
        expect($voter->can('update', $election))->toBeFalse();
    });

    test('only admin can delete elections', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->active()->create(['created_by' => $admin->id]);

        expect($admin->can('delete', $election))->toBeTrue();
        expect($voter->can('delete', $election))->toBeFalse();
    });

    test('only admin can start elections', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->draft()->create(['created_by' => $admin->id]);

        expect($admin->can('start', $election))->toBeTrue();
        expect($voter->can('start', $election))->toBeFalse();
    });

    test('only admin can end elections', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->active()->create(['created_by' => $admin->id]);

        expect($admin->can('end', $election))->toBeTrue();
        expect($voter->can('end', $election))->toBeFalse();
    });

    test('only admin can publish results', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->completed()->create(['created_by' => $admin->id]);

        expect($admin->can('publishResults', $election))->toBeTrue();
        expect($voter->can('publishResults', $election))->toBeFalse();
    });
});
