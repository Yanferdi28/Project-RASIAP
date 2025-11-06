<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat permission untuk ArsipAktif jika belum ada
        $permissions = [
            'arsipaktif.view-any',
            'arsipaktif.create',
            'arsipaktif.update',
            'arsipaktif.delete',
            'arsipaktif.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Membuat role jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);

        // Memberikan permission ke role admin (semua akses)
        $adminRole->givePermissionTo($permissions);

        // Memberikan permission ke role user
        $userRole->givePermissionTo([
            'arsipaktif.view-any',
            'arsipaktif.view',
            'arsipaktif.create',
            'arsipaktif.update',
        ]);

        // Memberikan permission ke role operator
        $operatorRole->givePermissionTo([
            'arsipaktif.view-any',
            'arsipaktif.view',
            'arsipaktif.update',
        ]);
    }
}