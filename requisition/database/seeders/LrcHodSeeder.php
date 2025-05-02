<?php

namespace Database\Seeders;

// Import necessary classes
use App\Models\Department;
use App\Models\Lrc;
use App\Models\Hod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Optional, but good practice
use Illuminate\Database\Seeder; // Import the base Seeder class
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Import Str facade for potentially cleaner email generation

class DepartmentLrcHodSeeder extends Seeder // Define the class extending Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void // Add void return type hint (good practice)
    {
        $departments = [
            'DIT', 'DEE', 'DCES', 'DME', 'DHM',
            'Exam cell', 'Library', 'Admin', 'Estate', 'Service', 'IT', 'Management','Student Affairs'
        ];

        foreach ($departments as $deptName) {
            // Create the department
            $department = Department::create(['name' => $deptName]);

            // Generate a slightly safer email prefix (handles spaces, etc.)
            $emailPrefix = Str::slug($deptName, '.'); // e.g., 'exam.cell'

            // Create the LRC user for this department
            Lrc::create([
                'name' => 'LRC ' . $deptName,
                'email' => "lrc.$emailPrefix@jnec.edu.bt", // Use the generated prefix
                'password' => Hash::make('Passw0rd'), // Consider using a more secure default or config value
                'department_id' => $department->id
            ]);

            // Create the HOD user for this department
            Hod::create([
                'name' => 'HOD ' . $deptName,
                'email' => "hod.$emailPrefix@jnec.edu.bt", // Use the generated prefix
                'password' => Hash::make('Passw0rd'), // Consider using a more secure default or config value
                'department_id' => $department->id
            ]);
        }
    }
}