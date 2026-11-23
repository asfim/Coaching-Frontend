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

        $classes = $query->paginate(12);

        $totalClasses = AcademicClass::count();
        $activeClasses = AcademicClass::where('status', 'Active')->count();
        $totalStudents = Student::count();

        return view('admin.classes.index', compact('classes', 'totalClasses', 'activeClasses', 'totalStudents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'code' => 'nullable|string|max:50',
            'numeric_level' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        AcademicClass::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class added successfully!');
    }

    public function update(Request $request, $id)
    {
        $class = AcademicClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
            'code' => 'nullable|string|max:50',
            'numeric_level' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy($id)
    {
        $class = AcademicClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully!');
    }
}
