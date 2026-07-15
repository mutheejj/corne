<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        $elections = Election::all();

        foreach ($elections as $election) {
            $positions = $election->positions;

            foreach ($positions as $position) {
                $voters = User::voters()->inRandomOrder()->limit(3)->get();

                foreach ($voters as $voter) {
                    $voter->update(['role' => 'candidate']);

                    Candidate::create([
                        'user_id' => $voter->id,
                        'election_id' => $election->id,
                        'position_id' => $position->id,
                        'manifesto_title' => 'My Vision for '.$position->title,
                        'manifesto' => 'I am committed to serving the student body with integrity, transparency, and dedication. My priorities include better student welfare, improved academic resources, and stronger representation.',
                        'slogan' => 'Together We Can',
                        'status' => $election->status === 'completed' ? 'approved' : fake()->randomElement(['pending', 'approved']),
                        'approved_at' => $election->status === 'completed' ? now()->subDays(7) : null,
                        'approved_by' => $election->status === 'completed' ? User::admins()->first()->id : null,
                    ]);
                }
            }
        }
    }
}
