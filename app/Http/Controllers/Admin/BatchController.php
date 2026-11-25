<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\AcademicClass;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with(['course'])->withCount('students')->latest();

        if ($request->filled('class_name')) {
            $query->where('class_name', $request->class_name);
        }

        $batches = $query->paginate(12);
        $courses = Course::all();
        $classes = AcademicClass::where('status', 'Active')->orderBy('numeric_level')->get();

        return view('admin.batches', compact('batches', 'courses', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:50|unique:batches,batch_code',
            'class_name' => 'required|string|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'days' => 'required|string|max:100',
            'time_slot' => 'required|string|max:100',
            'room' => 'required|string|max:50',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:Active,Inactive',
        ]);

        $classObj = AcademicClass::where('name', $validated['class_name'])->first();
        if ($classObj) {
            $validated['class_id'] = $classObj->id;
        }

        Batch::create($validated);

        return redirect()->route('admin.batches')->with('success', 'Batch created successfully!');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()?->canEdit('batches')) {
            return redirect()->back()->with('error', 'You do not have permission to edit batches.');
        }

        $batch = Batch::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'batch_code' => 'required|string|max:50|unique:batches,batch_code,' . $id,
            'class_name' => 'required|string|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'days' => 'required|string|max:100',
            'time_slot' => 'required|string|max:100',
            'room' => 'required|string|max:50',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:Active,Inactive',
        ]);

        $classObj = AcademicClass::where('name', $validated['class_name'])->first();
        if ($classObj) {
            $validated['class_id'] = $classObj->id;
        }

        $batch->update($validated);

        return redirect()->route('admin.batches')->with('success', 'Batch updated successfully!');
    }

    public function destroy($id)
    {
        if (!auth()->user()?->canDelete('batches')) {
            return redirect()->back()->with('error', 'You do not have permission to delete batches.');
        }

        $batch = Batch::findOrFail($id);
        $batch->delete();

        return redirect()->route('admin.batches')->with('success', 'Batch deleted successfully!');
    }
}
