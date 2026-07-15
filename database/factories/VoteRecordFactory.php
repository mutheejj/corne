<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VoteRecord>
 */
class VoteRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->voter(),
            'election_id' => Election::factory(),
            'position_id' => Position::factory(),
            'verification_code' => Vote::generateVerificationCode(),
            'receipt_hash' => Vote::generateReceiptHash(),
            'voted_at' => now(),
        ];
    }
}
