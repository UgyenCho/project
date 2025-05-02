<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // ** Ensure this line is present **

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding roles...'); // Add output message

        // Use firstOrCreate to avoid duplicates if run again
        Role::firstOrCreate(['name' => 'User'], ['description' => 'Standard User/LRC']);
        Role::firstOrCreate(['name' => 'HOD'], ['description' => 'Head of Department']);
        Role::firstOrCreate(['name' => 'Finance'], ['description' => 'Finance Department User']);
        Role::firstOrCreate(['name' => 'President'], ['description' => 'President']);
        Role::firstOrCreate(['name' => 'Admin'], ['description' => 'System Administrator']);
        // Add any other roles you need, e.g.:
        // Role::firstOrCreate(['name' => 'Store Manager'], ['description' => 'Store Manager']);

        $this->command->info('Roles seeded successfully.'); // Add success message
    }
}