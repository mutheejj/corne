<?php

use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Policies\VotePolicy;

describe('Vote Policy', function () {
    beforeEach(function () {
        $admin = User::factory()->admin()->create();
        $this->election = Election::factory()->active()->create(['created_by' => $admin->id]);
        $this->position = Position::create([
            'election_id' => $this->election->id,
            'title' => 'President',
            'max_votes' => 1,
            'sort_order' => 1,
        ]);
    });

    test('registered voter in election can cast vote', function () {
        $voter = User::factory()->voter()->create();
        $this->election->voters()->attach($voter);

        $policy = new VotePolicy;

        expect($policy->cast($voter, $this->position))->toBeTrue();
    });

    test('voter not in election cannot cast vote', function () {
        $voter = User::factory()->voter()->create();

        $policy = new VotePolicy;

        expect($policy->cast($voter, $this->position))->toBeFalse();
    });

    test('admin cannot cast vote', function () {
        $admin = User::factory()->admin()->create();

        $policy = new VotePolicy;

        expect($policy->cast($admin, $this->position))->toBeFalse();
    });

    test('candidate cannot cast vote', function () {
        $candidate = User::factory()->candidate()->create();

        $policy = new VotePolicy;

        expect($policy->cast($candidate, $this->position))->toBeFalse();
    });
});
