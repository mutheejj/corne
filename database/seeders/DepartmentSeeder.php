<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['faculty_code' => 'CIT', 'name' => 'Computer Science', 'code' => 'CS'],
            ['faculty_code' => 'CIT', 'name' => 'Information Technology', 'code' => 'IT'],
            ['faculty_code' => 'CIT', 'name' => 'Software Engineering', 'code' => 'SE'],
            ['faculty_code' => 'ENG', 'name' => 'Electrical Engineering', 'code' => 'EE'],
            ['faculty_code' => 'ENG', 'name' => 'Mechanical Engineering', 'code' => 'ME'],
            ['faculty_code' => 'ENG', 'name' => 'Civil Engineering', 'code' => 'CE'],
            ['faculty_code' => 'SCI', 'name' => 'Mathematics', 'code' => 'MTH'],
            ['faculty_code' => 'SCI', 'name' => 'Biology', 'code' => 'BIO'],
            ['faculty_code' => 'SCI', 'name' => 'Chemistry', 'code' => 'CHM'],
            ['faculty_code' => 'BUS', 'name' => 'Finance', 'code' => 'FIN'],
            ['faculty_code' => 'BUS', 'name' => 'Marketing', 'code' => 'MKT'],
            ['faculty_code' => 'BUS', 'name' => 'Accounting', 'code' => 'ACC'],
            ['faculty_code' => 'EDU', 'name' => 'Primary Education', 'code' => 'PED'],
            ['faculty_code' => 'EDU', 'name' => 'Secondary Education', 'code' => 'SED'],
            ['faculty_code' => 'EDU', 'name' => 'Educational Psychology', 'code' => 'EPY'],
        ];

        foreach ($departments as $dept) {
            $faculty = Faculty::where('code', $dept['faculty_code'])->first();

            Department::create([
                'faculty_id' => $faculty->id,
                'name' => $dept['name'],
                'code' => $dept['code'],
                'is_active' => true,
            ]);
        }
    }
}
