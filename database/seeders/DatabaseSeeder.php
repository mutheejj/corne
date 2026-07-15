<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FacultySeeder::class,
            DepartmentSeeder::class,
            AdminUserSeeder::class,
            VoterSeeder::class,
            ElectionSeeder::class,
            CandidateSeeder::class,
        ]);
    }
}
