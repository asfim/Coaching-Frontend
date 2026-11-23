<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Routine;
use App\Models\Batch;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $teacher = $user?->teacher ?? Teacher::first();

        $routines = Routine::with('batch')
            ->when($teacher, fn($q) => $q->where('teacher_id', $teacher->id))
            ->get();

        $batches = Batch::withCount('students')->take(5)->get();
        $upcomingExams = Exam::where('status', 'Upcoming')->take(4)->get();
        $totalStudents = Student::count();

        return view('teacher.dashboard', compact('teacher', 'routines', 'batches', 'upcomingExams', 'totalStudents'));
    }

    public function routine()
    {
        $user = Auth::user();
        $teacher = $user?->teacher ?? Teacher::first();

        $routines = Routine::with('batch')
            ->when($teacher, fn($q) => $q->where('teacher_id', $teacher->id))
            ->orderBy('day')
            ->get();

        return view('teacher.routine', compact('teacher', 'routines'));
    }

    public function attendance(Request $request)
    {
        $batches = Batch::all();
        $selectedBatch = $request->batch_id ?? $batches->first()?->id;
        $date = $request->date ?? now()->toDateString();

        $students = Student::where('batch_id', $selectedBatch)->get();
        $attendances = Attendance::where('batch_id', $selectedBatch)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance', compact('batches', 'selectedBatch', 'date', 'students', 'attendances'));
    }
}
