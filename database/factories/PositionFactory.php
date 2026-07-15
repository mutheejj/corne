<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
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
            'title' => fake()->jobTitle(),
            'description' => fake()->sentence(),
            'max_votes' => 1,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
