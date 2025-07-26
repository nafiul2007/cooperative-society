<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles if not exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $memberRole = Role::firstOrCreate(['name' => 'member']);

        // Create admin user manually
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'password' => bcrypt('admin123'),  // change password later!
                'is_active' => true,  // Set admin user as active
            ]
        );

        // Assign admin role if not assigned yet
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }
    }
}
