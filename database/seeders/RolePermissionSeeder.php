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

        // 1. Base menu permissions
        $menuPermissions = [
            'dashboard',
            'students',
            'teachers',
            'courses',
            'classes',
            'subjects',
            'batches',
            'attendance',
            'exams',
            'results',
            'routine',
            'fees',
            'invoices',
            'accounts',
            'notices',
            'sms',
            'reports',
            'roles',
            'settings',
        ];

        // 2. Granular Edit & Delete permissions
        $actionPermissions = [
            'can_edit',
            'can_delete',

            // Edit permissions per module
            'students.edit',
            'teachers.edit',
            'courses.edit',
            'classes.edit',
            'subjects.edit',
            'batches.edit',
            'attendance.edit',
            'exams.edit',
            'results.edit',
            'routine.edit',
            'fees.edit',
            'accounts.edit',
            'notices.edit',
            'settings.edit',
            'roles.edit',

            // Delete permissions per module
            'students.delete',
            'teachers.delete',
            'courses.delete',
            'classes.delete',
            'subjects.delete',
            'batches.delete',
            'exams.delete',
            'results.delete',
            'routine.delete',
            'fees.delete',
            'accounts.delete',
            'notices.delete',
            'sms.delete',
        ];

        $allPermissions = array_merge($menuPermissions, $actionPermissions);

        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 3. Define Roles and Assign Default Permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $teacherRole->syncPermissions([]); // Granular permissions are assigned per teacher

        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $parentRole = Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);

        // 4. Sync Roles & Permissions with Existing Users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                $user->assignRole($user->role);
            }
            if ($user->role === 'admin') {
                $user->syncPermissions(Permission::all());
            }
        }
    }
}
