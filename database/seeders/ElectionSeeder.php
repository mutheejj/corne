<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ElectionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::admins()->first();

        // Active election
        $activeElection = Election::create([
            'title' => 'Student Union General Election 2026',
            'slug' => Str::slug('Student Union General Election 2026'),
            'description' => 'The annual general election for student union representatives. All registered students are eligible to vote.',
            'status' => 'active',
            'type' => 'general',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(7),
            'is_anonymous' => true,
            'require_2fa' => false,
            'created_by' => $admin->id,
        ]);

        $this->createPositions($activeElection, [
            ['title' => 'President', 'sort_order' => 1],
            ['title' => 'Vice President', 'sort_order' => 2],
            ['title' => 'Secretary General', 'sort_order' => 3],
        ]);

        ElectionSetting::create([
            'election_id' => $activeElection->id,
        ]);

        // Completed election
        $completedElection = Election::create([
            'title' => 'Faculty Representative Election 2025',
            'slug' => Str::slug('Faculty Representative Election 2025'),
            'description' => 'Election for faculty representatives for the academic year 2025/2026.',
            'status' => 'completed',
            'type' => 'faculty',
            'starts_at' => now()->subDays(30),
            'ends_at' => now()->subDays(23),
            'results_published_at' => now()->subDays(22),
            'is_anonymous' => true,
            'require_2fa' => false,
            'created_by' => $admin->id,
        ]);

        $this->createPositions($completedElection, [
            ['title' => 'Faculty Representative', 'sort_order' => 1],
            ['title' => 'Assistant Faculty Representative', 'sort_order' => 2],
        ]);

        ElectionSetting::create([
            'election_id' => $completedElection->id,
        ]);
    }

    private function createPositions(Election $election, array $positions): void
    {
        foreach ($positions as $pos) {
            Position::create([
                'election_id' => $election->id,
                'title' => $pos['title'],
                'description' => 'Position for '.$pos['title'],
                'max_votes' => 1,
                'sort_order' => $pos['sort_order'],
            ]);
        }
    }
}
