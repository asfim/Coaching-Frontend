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

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subjects = $query->paginate(15)->withQueryString();
        $classes = AcademicClass::withCount('subjects')->orderBy('numeric_level')->get();
        $totalSubjectsCount = Subject::count();
        $academicSubjectsCount = Subject::where('program_type', 'Academic')->count();
        $targetSubjectsCount = Subject::where('program_type', 'Target')->count();
        $selectedClass = $request->filled('class_id') ? AcademicClass::find($request->class_id) : null;

        return view('admin.subjects', compact(
            'subjects', 'classes', 'totalSubjectsCount', 
            'academicSubjectsCount', 'targetSubjectsCount', 'selectedClass'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50',
            'class_id' => 'required|exists:classes,id',
            'program_type' => 'nullable|in:Academic,Target',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $cls = AcademicClass::find($validated['class_id']);
        if (empty($validated['program_type'])) {
            $validated['program_type'] = $cls?->class_type ?? 'Academic';
        }

        $exists = Subject::where('class_id', $validated['class_id'])
            ->where('code', $validated['code'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Subject with code "' . $validated['code'] . '" already exists in this class!');
        }

        Subject::create($validated);

        return redirect()->route('admin.subjects.index', ['class_id' => $validated['class_id']])
            ->with('success', 'Subject "' . $validated['name'] . '" added successfully for this class!');
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50',
            'class_id' => 'required|exists:classes,id',
            'program_type' => 'nullable|in:Academic,Target',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $cls = AcademicClass::find($validated['class_id']);
        if (empty($validated['program_type'])) {
            $validated['program_type'] = $cls?->class_type ?? ($subject->program_type ?? 'Academic');
        }

        $exists = Subject::where('class_id', $validated['class_id'])
            ->where('code', $validated['code'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Another subject with code "' . $validated['code'] . '" already exists in this class!');
        }

        $oldName = $subject->name;
        $subject->update($validated);

        if ($oldName !== $validated['name']) {
            Exam::where('subject', $oldName)->update(['subject' => $validated['name']]);
        }

        return redirect()->route('admin.subjects.index', ['class_id' => $validated['class_id']])
            ->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $classId = $subject->class_id;
        Exam::where('subject_id', $subject->id)->update(['subject_id' => null]);
        $subject->delete();

        return redirect()->route('admin.subjects.index', $classId ? ['class_id' => $classId] : [])
            ->with('success', 'Subject deleted successfully!');
    }
}
