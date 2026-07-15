<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'voter',
            'student_id' => fake()->unique()->regexify('[A-Z]{3}\d{3}-\d{4}/\d{4}'),
            'phone' => fake()->phoneNumber(),
            'faculty' => fake()->randomElement(['Computing & Information Technology', 'Engineering & Technology', 'Science', 'Business', 'Education']),
            'department' => fake()->randomElement(['Computer Science', 'Software Engineering', 'Information Technology', 'Electrical Engineering', 'Mathematics']),
            'course' => fake()->randomElement(['BSc Computer Science', 'BSc Software Engineering', 'BSc Information Technology', 'BSc Electrical Engineering']),
            'year_of_study' => fake()->numberBetween(1, 4),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'student_id' => null,
            'faculty' => null,
            'department' => null,
            'course' => null,
            'year_of_study' => null,
        ]);
    }

    public function voter(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'voter',
        ]);
    }

    public function candidate(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'candidate',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
