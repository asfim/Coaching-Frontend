<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Attendance;
use App\Models\Fee;
use App\Models\Account;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalCourses = Course::count();
        $totalBatches = Batch::count();

        // Attendance today
        $today = now()->toDateString();
        $todayPresent = Attendance::where('date', $today)->where('status', 'present')->count();
        $todayAbsent = Attendance::where('date', $today)->where('status', 'absent')->count();
        $todayLate = Attendance::where('date', $today)->where('status', 'late')->count();
        $todayLeave = Attendance::where('date', $today)->where('status', 'leave')->count();
        $todayTotal = $todayPresent + $todayAbsent + $todayLate + $todayLeave;
        $attendanceRate = $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100) : 92;

        // Monthly collection
        $monthlyCollection = Fee::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('paid_amount');

        // Recent students
        $recentStudents = Student::with(['batch', 'course'])->latest()->take(6)->get();

        // Net account balance
        $totalIncome = Account::where('type', 'income')->sum('amount');
        $totalExpense = Account::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('admin.dashboard', compact(
            'totalStudents', 'totalTeachers', 'totalCourses', 'totalBatches',
            'attendanceRate', 'todayPresent', 'todayAbsent', 'todayLate', 'todayLeave',
            'monthlyCollection', 'recentStudents', 'netBalance'
        ));
    }
}
