<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'position_id' => Position::factory(),
            'candidate_id' => Candidate::factory(),
            'verification_code' => Vote::generateVerificationCode(),
            'receipt_hash' => Vote::generateReceiptHash(),
            'encrypted_choice' => null,
            'cast_at' => now(),
        ];
    }
}
