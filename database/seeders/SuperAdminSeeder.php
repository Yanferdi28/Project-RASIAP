<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the superadmin role exists
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        
        // Create a superadmin user with verification status
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('admin123'),
            'unit_pengolah_id' => null, // Super admin might not have a specific unit
            'verification_status' => 'verified', // User is already verified
            'verified_at' => now(),
            'email_verified_at' => now(), // Email is also verified
        ]);

        // Assign the superadmin role to the user
        $superAdmin->assignRole('admin');
        
        $this->command->info('Admin user created successfully!');
    }
}