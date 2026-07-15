<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['election', 'vote', 'candidate', 'system']),
            'title' => fake()->sentence(4),
            'message' => fake()->paragraph(),
            'data' => null,
            'read_at' => null,
        ];
    }
}
