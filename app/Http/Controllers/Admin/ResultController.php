<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $query = Result::with(['exam.batch', 'student'])->latest();

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $results = $query->paginate(15);
        $exams = Exam::all();
        $students = Student::all();

        return view('admin.results', compact('results', 'exams', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'obtained_marks' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'remarks' => 'nullable|string',
        ]);

        $percentage = ($validated['obtained_marks'] / $validated['total_marks']) * 100;
        if ($percentage >= 80) {
            $gpa = 5.00; $grade = 'A+';
        } elseif ($percentage >= 70) {
            $gpa = 4.00; $grade = 'A';
        } elseif ($percentage >= 60) {
            $gpa = 3.50; $grade = 'A-';
        } elseif ($percentage >= 50) {
            $gpa = 3.00; $grade = 'B';
        } elseif ($percentage >= 40) {
            $gpa = 2.00; $grade = 'C';
        } elseif ($percentage >= 33) {
            $gpa = 1.00; $grade = 'D';
        } else {
            $gpa = 0.00; $grade = 'F';
        }

        $validated['gpa'] = $gpa;
        $validated['grade'] = $grade;

        Result::updateOrCreate(
            ['exam_id' => $validated['exam_id'], 'student_id' => $validated['student_id']],
            $validated
        );

        return redirect()->route('admin.results')->with('success', 'Exam result recorded successfully!');
    }

    public function destroy($id)
    {
        $result = Result::findOrFail($id);
        $result->delete();

        return redirect()->route('admin.results')->with('success', 'Result entry deleted successfully!');
    }
}
