<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Available Permissions
        $permissions = [
            'attendance',
            'routine',
            'results',
            'exams',
            'notices',
            'students',
            'fees',
            'accounts',
            'reports',
            'settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 2. Define Roles and Assign Default Permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $teacherRole->syncPermissions([]); // Granular permissions are assigned per teacher

        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $parentRole = Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);

        // 3. Sync Roles & Permissions with Existing Users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                $user->assignRole($user->role);
            }
            if ($user->role === 'admin') {
                $user->syncPermissions(Permission::all());
            } elseif ($user->role === 'teacher') {
                // If the user has no direct permissions yet, give default teacher permissions
                if ($user->permissions()->count() === 0) {
                    $user->syncPermissions(['attendance', 'routine']);
                }
            }
        }
    }
}
