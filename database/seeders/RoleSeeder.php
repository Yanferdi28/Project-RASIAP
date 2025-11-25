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
        $arsipaktifPermissions = [
            'arsipaktif.view-any',
            'arsipaktif.create',
            'arsipaktif.update',
            'arsipaktif.delete',
            'arsipaktif.view',
        ];

        foreach ($arsipaktifPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Membuat permission untuk ArsipUnit jika belum ada
        $arsipunitPermissions = [
            'arsipunit.view-any',
            'arsipunit.create',
            'arsipunit.update',
            'arsipunit.delete',
            'arsipunit.view',
            'arsipunit.submit',
            'arsipunit.verify',
        ];

        foreach ($arsipunitPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Membuat permission untuk BerkasArsip jika belum ada
        $berkasarsipPermissions = [
            'berkasarsip.view-any',
            'berkasarsip.create',
            'berkasarsip.update',
            'berkasarsip.delete',
            'berkasarsip.view',
        ];

        foreach ($berkasarsipPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Membuat role jika belum ada
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $manajemenRole = Role::firstOrCreate(['name' => 'manajemen']);

        // Combine all permissions for super admin
        $allPermissions = array_merge($arsipaktifPermissions, $arsipunitPermissions, $berkasarsipPermissions);

        // Super admin gets all permissions
        $superAdminRole->givePermissionTo($allPermissions);

        // Memberikan permission ke role admin (semua akses)
        $adminRole->givePermissionTo($allPermissions);

        // Memberikan permission ke role user
        $userRole->givePermissionTo([
            'arsipaktif.view-any',
            'arsipaktif.view',
            'arsipaktif.create',
            'arsipaktif.update',
            'arsipunit.view-any',
            'arsipunit.view',
            'arsipunit.create',
            'arsipunit.update',
            'berkasarsip.view-any',
            'berkasarsip.view',
            'berkasarsip.create',
            'berkasarsip.update',
        ]);

        // Memberikan permission ke role operator
        $operatorRole->givePermissionTo([
            'arsipaktif.view-any',
            'arsipaktif.view',
            'arsipaktif.update',
            'arsipunit.view-any',
            'arsipunit.view',
            'arsipunit.update',
            'arsipunit.submit',
            'arsipunit.verify',
            'berkasarsip.view-any',
            'berkasarsip.view',
            'berkasarsip.update',
        ]);

        // Memberikan permission ke role manajemen (hanya bisa melihat arsip unit dan berkas arsip, tanpa bisa mengedit/menambah/menghapus)
        // Tetapi bisa melihat semua arsip unit pengolah
        $manajemenRole->givePermissionTo([
            'arsipunit.view-any',  // Bisa melihat semua arsip unit (tidak terbatas pada unit pengolahnya sendiri)
            'arsipunit.view',      // Bisa melihat detail arsip unit
            'berkasarsip.view-any', // Bisa melihat semua berkas arsip
            'berkasarsip.view',     // Bisa melihat detail berkas arsip
        ]);
    }
}