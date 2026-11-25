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
            'dashboard' => [
                'name' => 'Dashboard (ড্যাশবোর্ড)',
                'desc' => 'View administrative summary counters, charts and metrics.',
                'icon' => 'bi-speedometer2',
                'color' => '#4f46e5',
                'category' => 'General',
                'actions' => ['view'],
            ],
            'students' => [
                'name' => 'Students (শিক্ষার্থী ব্যবস্থাপনা)',
                'desc' => 'Manage student admissions, profiles, and registration.',
                'icon' => 'bi-person-vcard',
                'color' => '#0284c7',
                'category' => 'Academic',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'teachers' => [
                'name' => 'Teachers (শিক্ষক ব্যবস্থাপনা)',
                'desc' => 'Manage faculty members, salaries, and profiles.',
                'icon' => 'bi-person-badge',
                'color' => '#059669',
                'category' => 'Academic',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'courses' => [
                'name' => 'Courses (কোর্সসমূহ)',
                'desc' => 'Manage courses, syllabus, and course categories.',
                'icon' => 'bi-journal-bookmark',
                'color' => '#d97706',
                'category' => 'Academic',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'classes' => [
                'name' => 'Classes (শ্রেণী / ক্লাস)',
                'desc' => 'Manage academic classes and target batch classifications.',
                'icon' => 'bi-book',
                'color' => '#7c3aed',
                'category' => 'Academic',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'subjects' => [
                'name' => 'Subjects (বিষয়সমূহ)',
                'desc' => 'Manage subject list, codes, and exam paper associations.',
                'icon' => 'bi-journals',
                'color' => '#dc2626',
                'category' => 'Academic',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'batches' => [
                'name' => 'Batches (ব্যাচসমূহ)',
                'desc' => 'Manage batches, schedules, and capacity limits.',
                'icon' => 'bi-collection',
                'color' => '#2563eb',
                'category' => 'Academic',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'attendance' => [
                'name' => 'Take Attendance (উপস্থিতি গ্রহণ)',
                'desc' => 'Record and manage daily batch attendance.',
                'icon' => 'bi-calendar2-check',
                'color' => '#059669',
                'category' => 'Operations',
                'actions' => ['view', 'edit'],
            ],
            'exams' => [
                'name' => 'Exams (পরীক্ষা ব্যবস্থাপনা)',
                'desc' => 'Schedule exams, exam types, and test papers.',
                'icon' => 'bi-file-earmark-text',
                'color' => '#7c3aed',
                'category' => 'Examinations',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'results' => [
                'name' => 'Exam Results & Marks (ফলাফল ও নম্বর)',
                'desc' => 'Enter subject-wise scores and publish marksheets.',
                'icon' => 'bi-trophy',
                'color' => '#d97706',
                'category' => 'Examinations',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'routine' => [
                'name' => 'Class Routine (ক্লাস রুটিন)',
                'desc' => 'Manage weekly class timetable slots and rooms.',
                'icon' => 'bi-clock-history',
                'color' => '#2563eb',
                'category' => 'Operations',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'fees' => [
                'name' => 'Fees Collection (ফি আদায়)',
                'desc' => 'Collect fees, installments, and payment records.',
                'icon' => 'bi-cash-coin',
                'color' => '#059669',
                'category' => 'Accounts & Finance',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'invoices' => [
                'name' => 'Invoices & Receipts (রসিদ ও ইনভয়েস)',
                'desc' => 'Generate and print payment invoices.',
                'icon' => 'bi-receipt',
                'color' => '#0284c7',
                'category' => 'Accounts & Finance',
                'actions' => ['view'],
            ],
            'accounts' => [
                'name' => 'Accounts (আয়-ব্যয় ও হিসাব)',
                'desc' => 'Manage income, expense, and ledger accounts.',
                'icon' => 'bi-wallet2',
                'color' => '#16a34a',
                'category' => 'Accounts & Finance',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'notices' => [
                'name' => 'Notices (নোটিশ ও বিজ্ঞপ্তি)',
                'desc' => 'Publish notices and circulars to students.',
                'icon' => 'bi-megaphone',
                'color' => '#0284c7',
                'category' => 'Communication',
                'actions' => ['view', 'edit', 'delete'],
            ],
            'sms' => [
                'name' => 'SMS Notifications (এসএমএস নোটিফিকেশন)',
                'desc' => 'Send broadcast SMS to students and guardians.',
                'icon' => 'bi-chat-dots',
                'color' => '#ea580c',
                'category' => 'Communication',
                'actions' => ['view', 'delete'],
            ],
            'reports' => [
                'name' => 'Reports & Analytics (রিপোর্ট)',
                'desc' => 'Generate analytics and performance summaries.',
                'icon' => 'bi-bar-chart',
                'color' => '#4f46e5',
                'category' => 'Analytics',
                'actions' => ['view'],
            ],
            'roles' => [
                'name' => 'Role Permissions (রোল পারমিশন)',
                'desc' => 'Manage roles and module access permissions.',
                'icon' => 'bi-shield-lock',
                'color' => '#9333ea',
                'category' => 'Administration',
                'actions' => ['view', 'edit'],
            ],
            'settings' => [
                'name' => 'System Settings (সিস্টেম সেটিংস)',
                'desc' => 'Manage coaching logo, favicon, and system info.',
                'icon' => 'bi-gear',
                'color' => '#475569',
                'category' => 'Administration',
                'actions' => ['view', 'edit'],
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
