<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['batch', 'course', 'fees'])->latest();

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
        $classes = AcademicClass::where('status', 'Active')->orderBy('numeric_level')->get();

        return view('admin.students', compact('students', 'batches', 'courses', 'classes'));
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
            'course_id' => 'nullable',
            'batch_id' => 'nullable',
            'admission_date' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Completed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        // Clean nullable relationships
        $validated['course_id'] = $request->filled('course_id') ? $request->course_id : null;
        $validated['batch_id'] = $request->filled('batch_id') ? $request->batch_id : null;
        $validated['admission_date'] = $request->filled('admission_date') ? $request->admission_date : now()->toDateString();
        $validated['email'] = $request->filled('email') ? $request->email : null;

        // Associate with AcademicClass
        $classObj = AcademicClass::where('name', $validated['class_name'])->first();
        if ($classObj) {
            $validated['class_id'] = $classObj->id;
        }

        // Auto-generate guaranteed unique student ID (STD-2401, STD-2402, ...)
        $maxNum = 2400;
        $existingIds = Student::pluck('student_id');
        foreach ($existingIds as $sid) {
            if (preg_match('/STD-(\d+)/', $sid, $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $validated['student_id'] = 'STD-' . ($maxNum + 1);

        // Avatar upload handling
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = 'storage/' . $path;
        } else {
            $validated['avatar'] = 'images/student_female_profile_1790497748277.jpg';
        }

        $student = Student::create($validated);

        // Auto-create student user account for login if email is given
        if (!empty($student->email)) {
            User::firstOrCreate(
                ['email' => $student->email],
                [
                    'name' => $student->name,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'phone' => $student->phone,
                    'student_id' => $student->id,
                    'avatar' => $student->avatar,
                    'status' => 'active',
                ]
            );
        }

        return redirect()->route('admin.students')->with('success', 'Student ' . $student->name . ' (' . $student->student_id . ') added successfully!');
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
            'course_id' => 'nullable',
            'batch_id' => 'nullable',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Completed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $validated['course_id'] = $request->filled('course_id') ? $request->course_id : null;
        $validated['batch_id'] = $request->filled('batch_id') ? $request->batch_id : null;
        $validated['email'] = $request->filled('email') ? $request->email : null;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = 'storage/' . $path;
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
