<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Notice;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function studentDashboard()
    {
        $user = Auth::user();
        $student = ($user && $user->student_id)
            ? Student::with(['batch.academicClass', 'course', 'attendances', 'results.exam.subjectModel', 'results.items', 'fees'])->find($user->student_id)
            : Student::with(['batch.academicClass', 'course', 'attendances', 'results.exam.subjectModel', 'results.items', 'fees'])->first();

        $totalAtt = $student?->attendances->count() ?: 0;
        $presentCount = $student?->attendances->where('status', 'present')->count() ?: 0;
        $absentCount = $student?->attendances->where('status', 'absent')->count() ?: 0;
        $lateCount = $student?->attendances->where('status', 'late')->count() ?: 0;
        $attRate = $totalAtt > 0 ? round(($presentCount / $totalAtt) * 100) : 100;

        $gpa = $student && $student->results->count() > 0 ? round($student->results->avg('gpa'), 2) : 0;
        $nextExam = Exam::where('status', 'Upcoming')->orderBy('date')->first();
        $recentNotices = Notice::where('status', 'published')->latest()->take(3)->get();
        $routines = Routine::with('teacher')
            ->when($student?->batch_id, fn($q) => $q->where('batch_id', $student->batch_id))
            ->get();
        $recentResult = $student?->results()->with(['exam.subjectModel', 'items'])->latest()->first();

        return view('student.dashboard', compact(
            'student', 'attRate', 'presentCount', 'absentCount', 'lateCount',
            'gpa', 'nextExam', 'recentNotices', 'routines', 'recentResult'
        ));
    }

    public function parentDashboard()
    {
        $user = Auth::user();
        $child = ($user && $user->student_id)
            ? Student::with(['batch', 'course', 'attendances', 'results.exam', 'fees'])->find($user->student_id)
            : Student::with(['batch', 'course', 'attendances', 'results.exam', 'fees'])->first();

        $totalAtt = $child?->attendances->count() ?: 0;
        $presentCount = $child?->attendances->where('status', 'present')->count() ?: 0;
        $absentCount = $child?->attendances->where('status', 'absent')->count() ?: 0;
        $lateCount = $child?->attendances->where('status', 'late')->count() ?: 0;
        $attRate = $totalAtt > 0 ? round(($presentCount / $totalAtt) * 100) : 94;

        $gpa = $child && $child->results->count() > 0 ? round($child->results->avg('gpa'), 2) : 4.83;
        $feeDue = $child?->fees->sum('due_amount') ?: 0;
        $nextExam = Exam::where('status', 'Upcoming')->orderBy('date')->first();
        $upcomingExams = Exam::where('status', 'Upcoming')->orderBy('date')->take(3)->get();
        $recentNotices = Notice::where('status', 'published')->latest()->take(3)->get();

        return view('parent.dashboard', compact(
            'child', 'attRate', 'presentCount', 'absentCount', 'lateCount',
            'gpa', 'feeDue', 'nextExam', 'upcomingExams', 'recentNotices'
        ));
    }
}
