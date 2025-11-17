<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create operator user
        $operator = User::create([
            'name' => 'operator',
            'email' => 'operator@gmail.com',
            'password' => Hash::make('operator'),
            'unit_pengolah_id' => null, // Adjust as needed
            'verification_status' => 'verified', // User is already verified
            'verified_at' => now(),
            'email_verified_at' => now(), // Email is also verified
        ]);

        // Assign the operator role to the user
        $operator->assignRole('operator');

        // Create user
        $user = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user1234'),
            'unit_pengolah_id' => null, // Adjust as needed
            'verification_status' => 'verified', // User is already verified
            'verified_at' => now(),
            'email_verified_at' => now(), // Email is also verified
        ]);

        // Assign the user role to the user
        $user->assignRole('user');

        $this->command->info('Operator and User created successfully!');
    }
}