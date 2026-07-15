<?php

namespace Database\Factories;

use App\Models\Election;
use App\Models\ElectionSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElectionSetting>
 */
class ElectionSettingFactory extends Factory
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
            'allow_abstain' => true,
            'show_results_live' => false,
            'show_vote_count' => true,
            'require_student_id_verification' => true,
            'max_votes_per_position' => 1,
            'voting_time_limit_minutes' => null,
        ];
    }
}
