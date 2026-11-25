<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicClass::withCount(['students', 'batches'])->latest();

        if ($request->filled('class_type')) {
            $query->where('class_type', $request->class_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $classes = $query->paginate(12)->withQueryString();

        $totalClasses = AcademicClass::count();
        $academicCount = AcademicClass::where('class_type', 'Academic')->count();
        $targetCount = AcademicClass::where('class_type', 'Target')->count();
        $activeClasses = AcademicClass::where('status', 'Active')->count();
        $totalStudents = Student::count();

        return view('admin.classes.index', compact('classes', 'totalClasses', 'academicCount', 'targetCount', 'activeClasses', 'totalStudents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'code' => 'nullable|string|max:50',
            'numeric_level' => 'nullable|integer',
            'class_type' => 'nullable|in:Academic,Target',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $validated['class_type'] = $request->input('class_type', 'Academic');

        AcademicClass::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class added successfully!');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()?->canEdit('classes')) {
            return redirect()->back()->with('error', 'You do not have permission to edit classes.');
        }

        $class = AcademicClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
            'code' => 'nullable|string|max:50',
            'numeric_level' => 'nullable|integer',
            'class_type' => 'nullable|in:Academic,Target',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $validated['class_type'] = $request->input('class_type', $class->class_type ?? 'Academic');

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy($id)
    {
        if (!auth()->user()?->canDelete('classes')) {
            return redirect()->back()->with('error', 'You do not have permission to delete classes.');
        }

        $class = AcademicClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully!');
    }
}
