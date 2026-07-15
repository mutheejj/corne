<?php

use App\Models\Election;
use App\Models\Position;
use App\Models\User;

describe('Position Policy', function () {
    test('any user can view positions', function () {
        $user = User::factory()->voter()->create();

        expect($user->can('viewAny', Position::class))->toBeTrue();
    });

    test('only admin can create positions', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->active()->create(['created_by' => $admin->id]);

        expect($admin->can('create', [Position::class, $election]))->toBeTrue();
        expect($voter->can('create', [Position::class, $election]))->toBeFalse();
    });

    test('only admin can update positions', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->active()->create(['created_by' => $admin->id]);
        $position = Position::create([
            'election_id' => $election->id,
            'title' => 'President',
            'max_votes' => 1,
            'sort_order' => 1,
        ]);

        expect($admin->can('update', $position))->toBeTrue();
        expect($voter->can('update', $position))->toBeFalse();
    });

    test('only admin can delete positions', function () {
        $admin = User::factory()->admin()->create();
        $voter = User::factory()->voter()->create();
        $election = Election::factory()->active()->create(['created_by' => $admin->id]);
        $position = Position::create([
            'election_id' => $election->id,
            'title' => 'President',
            'max_votes' => 1,
            'sort_order' => 1,
        ]);

        expect($admin->can('delete', $position))->toBeTrue();
        expect($voter->can('delete', $position))->toBeFalse();
    });
});
