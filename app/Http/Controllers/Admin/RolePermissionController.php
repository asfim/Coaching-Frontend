<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $adminCount = User::where('role', 'admin')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $studentCount = User::where('role', 'student')->count();
        $parentCount = User::where('role', 'parent')->count();

        $teachers = Teacher::with(['user.permissions', 'user.roles'])->latest()->get();

        $availablePermissions = [
            'attendance' => [
                'name' => 'Take Attendance (উপস্থিতি গ্রহণ)',
                'desc' => 'Allows teacher to record daily student attendance.',
                'icon' => 'bi-check2-circle',
                'color' => '#059669',
            ],
            'routine' => [
                'name' => 'Class Routine (ক্লাস রুটিন)',
                'desc' => 'Allows viewing class schedule timetable and weekly slots.',
                'icon' => 'bi-clock',
                'color' => '#2563eb',
            ],
            'results' => [
                'name' => 'Exam Results & Marks (ফলাফল ও নম্বর)',
                'desc' => 'Allows entering subject-wise marks and accessing exam scores.',
                'icon' => 'bi-trophy',
                'color' => '#d97706',
            ],
            'exams' => [
                'name' => 'Exams Management (পরীক্ষা)',
                'desc' => 'Allows viewing exam schedules, syllabus, and test details.',
                'icon' => 'bi-file-earmark-text',
                'color' => '#7c3aed',
            ],
            'notices' => [
                'name' => 'Post Notices (নোটিশ পোস্ট)',
                'desc' => 'Allows publishing notices and circulars to students.',
                'icon' => 'bi-megaphone',
                'color' => '#0284c7',
            ],
            'students' => [
                'name' => 'View Students (শিক্ষার্থী তালিকা)',
                'desc' => 'Allows viewing student list and profile records.',
                'icon' => 'bi-people',
                'color' => '#475569',
            ],
        ];

        return view('admin.roles', compact(
            'adminCount', 'teacherCount', 'studentCount', 'parentCount',
            'teachers', 'availablePermissions'
        ));
    }

    public function updateUserPermissions(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $permissions = $request->input('permissions', []);
        if (!is_array($permissions)) {
            $permissions = [];
        }

        // Ensure permissions exist in Spatie
        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        $user = User::where('teacher_id', $teacher->id)->first();
        if ($user) {
            $user->assignRole('teacher');
            $user->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Permissions updated successfully for ' . $teacher->name . '!');
    }
}
