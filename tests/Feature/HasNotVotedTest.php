<?php

use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\VoteRecord;

describe('HasNotVoted Middleware', function () {
    beforeEach(function () {
        $admin = User::factory()->admin()->create();
        $this->election = Election::factory()->active()->create(['created_by' => $admin->id]);
        $this->position = Position::create([
            'election_id' => $this->election->id,
            'title' => 'President',
            'description' => 'President',
            'max_votes' => 1,
            'sort_order' => 1,
        ]);
        $this->voter = User::factory()->voter()->create();
    });

    test('user who has not voted can access', function () {
        $response = $this->actingAs($this->voter)
            ->get("/test-has-not-voted/{$this->position->id}");

        $response->assertOk();
    });

    test('user who has voted is blocked', function () {
        VoteRecord::create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'position_id' => $this->position->id,
            'verification_code' => 'TESTCODE123',
            'receipt_hash' => 'testhash',
            'voted_at' => now(),
        ]);

        $response = $this->actingAs($this->voter)
            ->get("/test-has-not-voted/{$this->position->id}");

        $response->assertForbidden();
    });
});
