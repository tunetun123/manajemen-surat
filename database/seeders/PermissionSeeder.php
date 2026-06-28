<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Surat Masuk' => ['view', 'create', 'edit', 'delete'],
            'Surat Keluar' => ['view', 'create', 'edit', 'delete'],
            'Disposisi' => ['view', 'create', 'edit', 'delete'],
            'Persetujuan' => ['view', 'create', 'edit', 'delete'],
            'Unit Kerja' => ['view', 'create', 'edit', 'delete'],
            'Kategori Surat' => ['view', 'create', 'edit', 'delete'],
            'Pengguna' => ['view', 'create', 'edit', 'delete'],
            'Hak Akses' => ['view', 'create', 'edit', 'delete'],
            'Pengaturan' => ['manage'],
            'Dashboard' => ['view'],
        ];

        // Ensure super_admin role exists
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                // e.g. "view Dokumen", "create Dokumen", etc.
                $permissionName = $action . ' ' . $module;

                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);

                // Assign all permissions to super_admin by default
                if (!$superAdmin->hasPermissionTo($permission)) {
                    $superAdmin->givePermissionTo($permission);
                }
            }
        }
    }
}
