<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\ElectionVoter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElectionVoter>
 */
class ElectionVoterFactory extends Factory
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
            'user_id' => User::factory()->voter(),
            'notified' => false,
        ];
    }
}
