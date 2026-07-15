<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class VoterSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(50)->voter()->create();
    }
}
