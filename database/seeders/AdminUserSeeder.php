<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Test Voter',
            'email' => 'voter@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'voter',
            'student_id' => 'TEST001-2024/2025',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => 2,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Test Candidate',
            'email' => 'candidate@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'candidate',
            'student_id' => 'TEST002-2024/2025',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Software Engineering',
            'course' => 'BSc Software Engineering',
            'year_of_study' => 3,
            'is_active' => true,
        ]);
    }
}
