<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Add other seeders if needed, e.g., for initial departments if not done above
            DepartmentLrcUserSeeder::class,
            RoleSeeder::class,
            DepartmentUserSeeder::class, // Make sure this line exists
            // Add HOD seeder if you have one using the same pattern
        ]);
    }
}