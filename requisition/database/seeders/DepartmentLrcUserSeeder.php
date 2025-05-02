<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Lrc; // <-- USE LRC MODEL AS REQUESTED (App\Models\Lrc.php must exist)
// use App\Models\User; // Not using User model now
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Keep hashing passwords for security
use Illuminate\Support\Str;

class DepartmentLrcUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * !!! WARNING !!! This version seeds the 'lrcs' table AS REQUESTED.
     * Records created ONLY in 'lrcs' CANNOT log in via standard Laravel Auth.
     * Login WILL FAIL and LRC dashboards requiring login WILL BE INACCESSIBLE.
     */
    public function run(): void
    {
        // --- Define LRC Data per Department ---
        $lrcDataByDept = [
            'DIT' => 'Sonam Phuntsho',
            'DEE' => 'Pema Wangmo',
            'DCES' => 'Karma Dorji',
            'DME' => 'Tshering Lhamo',
            'DMPM' => 'Jigme Singye',
            'Exam cell' => 'Exam Cell LRC Staff',
            'Library' => 'Head Librarian',
        ];

        $defaultPassword = 'Password123!'; // The default password requested
        $yourDomain = 'jnec.edu.bt';

        // Modify the output message slightly for clarity
        echo "!!! WARNING !!! Seeding Lrc records directly into 'lrcs' table (standard login will fail)...\n";

        foreach ($lrcDataByDept as $deptNameOrCode => $lrcUserName) {
            // Find or create the department
            $department = Department::firstOrCreate(['name' => $deptNameOrCode]);

            if (!$department) {
                echo "  - Warning: Could not find or create Department: $deptNameOrCode. Skipping Lrc record.\n";
                continue;
            }

            // Generate email based on department name/code
            $emailPrefix = Str::slug('lrc ' . $deptNameOrCode, '.');
            $lrcEmail = $emailPrefix . '@' . $yourDomain;

            // Create or update the LRC record in the 'lrcs' table
            // Assumes 'lrcs' table has name, email, password, department_id
            // Assumes App\Models\Lrc exists and has these fields in $fillable
            Lrc::updateOrCreate( // <--- USE LRC MODEL AS REQUESTED
                ['email' => $lrcEmail], // Unique key to find the record in 'lrcs' table
                [
                    'name' => $lrcUserName,
                    'password' => Hash::make($defaultPassword), // Hash the password (still recommended)
                    'department_id' => $department->id,
                    // 'role' field likely does not exist in 'lrcs' table, removed
                    // 'email_verified_at' field likely does not exist in 'lrcs' table, removed
                ]
            );

            // Modify the output message slightly for clarity
            echo "  - Created/Updated Lrc record (in 'lrcs' table): $lrcUserName ($lrcEmail) for Department: $deptNameOrCode\n";
        }

        echo "Lrc record seeding complete (in 'lrcs' table). Standard Login will FAIL.\n";
    }
}