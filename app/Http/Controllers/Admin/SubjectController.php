<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\AcademicClass;
use App\Models\Exam;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with('academicClass')->withCount('exams')->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subjects = $query->paginate(15);
        $classes = AcademicClass::orderBy('numeric_level')->get();

        return view('admin.subjects', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:subjects,code',
            'class_id' => 'nullable|exists:classes,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject "' . $validated['name'] . '" added successfully!');
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'class_id' => 'nullable|exists:classes,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $oldName = $subject->name;
        $subject->update($validated);

        if ($oldName !== $validated['name']) {
            Exam::where('subject', $oldName)->update(['subject' => $validated['name']]);
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        Exam::where('subject_id', $subject->id)->update(['subject_id' => null]);
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully!');
    }
}
