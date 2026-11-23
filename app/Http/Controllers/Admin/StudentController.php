<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['batch', 'course'])->latest();

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('class_name')) {
            $query->where('class_name', $request->class_name);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('student_id', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $students = $query->paginate(12);
        $batches = Batch::all();
        $courses = Course::all();

        return view('admin.students', compact('students', 'batches', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'class_name' => 'required|string|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'batch_id' => 'nullable|exists:batches,id',
            'admission_date' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Completed',
        ]);

        if (empty($validated['student_id'])) {
            $last = Student::latest('id')->first();
            $nextNum = $last ? ($last->id + 2400 + 1) : 2401;
            $validated['student_id'] = 'STD-' . $nextNum;
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        } else {
            $validated['avatar'] = 'images/student_female_profile_1790497748277.jpg';
        }

        Student::create($validated);

        return redirect()->route('admin.students')->with('success', 'Student added successfully!');
    }

    public function show($id = null)
    {
        $student = $id ? Student::with(['batch', 'course', 'attendances', 'results.exam', 'fees'])->findOrFail($id)
                       : Student::with(['batch', 'course', 'attendances', 'results.exam', 'fees'])->first();

        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'No student found.');
        }

        $totalDays = $student->attendances->count();
        $presentDays = $student->attendances->where('status', 'present')->count();
        $attRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 94;

        $avgGpa = $student->results->avg('gpa') ?: 4.83;
        $totalPaid = $student->fees->sum('paid_amount');
        $totalDue = $student->fees->sum('due_amount');

        return view('admin.student-profile', compact('student', 'attRate', 'avgGpa', 'totalPaid', 'totalDue'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'class_name' => 'required|string|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'batch_id' => 'nullable|exists:batches,id',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Completed',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $student->update($validated);

        return redirect()->route('admin.students')->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Student deleted successfully!');
    }
}
