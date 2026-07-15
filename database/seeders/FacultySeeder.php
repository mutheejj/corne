<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            ['name' => 'Computing & Information Technology', 'code' => 'CIT', 'description' => 'Faculty of Computing & Information Technology'],
            ['name' => 'Engineering & Technology', 'code' => 'ENG', 'description' => 'Faculty of Engineering & Technology'],
            ['name' => 'Science', 'code' => 'SCI', 'description' => 'Faculty of Science'],
            ['name' => 'Business', 'code' => 'BUS', 'description' => 'Faculty of Business'],
            ['name' => 'Education', 'code' => 'EDU', 'description' => 'Faculty of Education'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
