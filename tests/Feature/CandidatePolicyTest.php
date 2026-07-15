<?php

use App\Models\Candidate;
use App\Models\User;

describe('Candidate Policy', function () {
    test('any user can view candidates', function () {
        $user = User::factory()->voter()->create();

        expect($user->can('viewAny', Candidate::class))->toBeTrue();
    });

    test('candidate owner can update their profile', function () {
        $owner = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $owner->id]);

        expect($owner->can('update', $candidate))->toBeTrue();
    });

    test('non-owner cannot update candidate profile', function () {
        $other = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create();

        expect($other->can('update', $candidate))->toBeFalse();
    });

    test('admin can update any candidate', function () {
        $admin = User::factory()->admin()->create();
        $candidate = Candidate::factory()->create();

        expect($admin->can('update', $candidate))->toBeTrue();
    });

    test('only admin can approve candidates', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $candidate = Candidate::factory()->create();

        expect($admin->can('approve', $candidate))->toBeTrue();
        expect($voter->can('approve', $candidate))->toBeFalse();
    });

    test('only admin can reject candidates', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $candidate = Candidate::factory()->create();

        expect($admin->can('reject', $candidate))->toBeTrue();
        expect($voter->can('reject', $candidate))->toBeFalse();
    });

    test('only admin can disqualify candidates', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $candidate = Candidate::factory()->create();

        expect($admin->can('disqualify', $candidate))->toBeTrue();
        expect($voter->can('disqualify', $candidate))->toBeFalse();
    });

    test('only admin can delete candidates', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $candidate = Candidate::factory()->create();

        expect($admin->can('delete', $candidate))->toBeTrue();
        expect($voter->can('delete', $candidate))->toBeFalse();
    });
});
