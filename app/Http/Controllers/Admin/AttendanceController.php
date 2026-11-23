<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $batchId = $request->input('batch_id');

        $batches = Batch::all();
        $selectedBatch = $batchId ? Batch::find($batchId) : $batches->first();

        $students = $selectedBatch ? Student::where('batch_id', $selectedBatch->id)->get() : Student::take(20)->get();

        $attendances = Attendance::where('date', $date)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $presentCount = Attendance::where('date', $date)->where('status', 'present')->count();
        $absentCount = Attendance::where('date', $date)->where('status', 'absent')->count();
        $lateCount = Attendance::where('date', $date)->where('status', 'late')->count();
        $leaveCount = Attendance::where('date', $date)->where('status', 'leave')->count();

        return view('admin.attendance', compact(
            'batches', 'selectedBatch', 'students', 'attendances', 'date',
            'presentCount', 'absentCount', 'lateCount', 'leaveCount'
        ));
    }

    public function mark(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $statuses = $request->input('status', []); // [student_id => status]
        $batchId = $request->input('batch_id');

        foreach ($statuses as $studentId => $status) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $date],
                ['batch_id' => $batchId, 'status' => $status]
            );
        }

        return redirect()->route('admin.attendance', ['date' => $date, 'batch_id' => $batchId])
            ->with('success', 'Attendance marked successfully!');
    }
}
