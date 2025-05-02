<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User; // Use the User model
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DepartmentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departmentsData = [
            'DIT',
            'DEE',
            'DCES',
            'DME',
            'DMPM', 
            'Exam cell',
            'Library',
        ];
        // --- Configuration ---
        $defaultPassword = env('DEFAULT_SEED_PASSWORD', 'Password123!');
        $domain = env('SEED_USER_EMAIL_DOMAIN', 'jnec.edu.bt');

        // --- Seed Departments and Departmental Users (HOD, LRC) ---
        foreach ($departmentsData as $deptFullName => $deptCode) {

            // --- Create or find the department using the Full Name ---
            $department = Department::firstOrCreate(
                ['name' => $deptFullName]
            );

            if (!$department) {
                $this->command->error("Failed to create or find department: '$deptFullName'");
                continue;
            }

            // --- Create or UPDATE HOD User ---
            // The special 'Finance' logic won't run as 'FIN' is no longer in the array
            $hodRole = 'HOD'; // Now always HOD
            $hodName = 'HOD ' . $deptCode;
            $hodEmailPrefix = Str::slug($hodName, '.');
            $hodEmail = $hodEmailPrefix . '@' . $domain;

            User::updateOrCreate(
                ['email' => $hodEmail], // Attributes to find the user by
                [                      // Attributes to update or create with
                    'name' => $hodName,
                    'password' => Hash::make($defaultPassword),
                    'department_id' => $department->id,
                    'role' => $hodRole,
                    'user_type' => 1, // HOD = 1
                    'email_verified_at' => now(),
                ]
            );

            // --- Create or UPDATE LRC User ---
            // The condition to skip 'FIN' is no longer needed, but leaving it doesn't hurt
            // if ($deptCode !== 'FIN') { // This condition will always be true now
                $lrcRole = 'LRC';
                $lrcName = 'LRC ' . $deptCode;
                $lrcEmailPrefix = Str::slug($lrcName, '.');
                $lrcEmail = $lrcEmailPrefix . '@' . $domain;

                User::updateOrCreate(
                    ['email' => $lrcEmail],  // Attributes to find the user by
                    [                       // Attributes to update or create with
                        'name' => $lrcName,
                        'password' => Hash::make($defaultPassword),
                        'department_id' => $department->id,
                        'role' => $lrcRole,
                        'user_type' => 0, // LRC = 0
                        'email_verified_at' => now(),
                    ]
                );
            // } // End IF condition (optional now)
        }

        // --- Seed Non-Departmental / Top-Level Users ---
        // (President and Admin creation remains the same)

        // Create President User
        User::firstOrCreate(
            ['email' => 'president@' . $domain],
            [
                'name' => 'President',
                'password' => Hash::make($defaultPassword),
                'department_id' => null,
                'role' => 'President',
                'user_type' => 3,
                'email_verified_at' => now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'finance@' . $domain],
            [
                'name' => 'Finance',
                'password' => Hash::make($defaultPassword),
                'department_id' => null,
                'role' => 'Finanace',
                'user_type' => 2,
                'email_verified_at' => now(),
            ]
        );

        // Create Admin User (Optional)
        User::firstOrCreate(
            ['email' => 'admin@' . $domain],
            [
                'name' => 'Admin User',
                'password' => Hash::make($defaultPassword),
                'department_id' => null,
                'role' => 'Admin',
                'user_type' => 4,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Departments (excluding Finance) and Users seeded successfully!');
    }
}
