<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with(['batch.academicClass', 'academicClass', 'typeModel', 'subjectModel'])->latest('date');

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('class_id')) {
            $classId = $request->class_id;
            $query->where(function ($q) use ($classId) {
                $q->where('class_id', $classId)
                  ->orWhereHas('batch', fn($b) => $b->where('class_id', $classId));
            });
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        $exams = $query->paginate(12)->withQueryString();
        $batches = Batch::with('academicClass')->orderBy('name')->get();
        $classes = AcademicClass::with(['subjects' => fn($q) => $q->where('status', 'Active')])->orderBy('numeric_level')->get();
        $examTypes = ExamType::withCount('exams')->orderBy('name')->get();
        $subjects = Subject::where('status', 'Active')->with('academicClass')->orderBy('name')->get();

        $totalExamsCount = Exam::count();
        $academicExamsCount = Exam::where('program_type', 'Academic')->count();
        $targetExamsCount = Exam::where('program_type', 'Target')->count();

        return view('admin.exams', compact(
            'exams', 'batches', 'classes', 'examTypes', 'subjects',
            'totalExamsCount', 'academicExamsCount', 'targetExamsCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|string|max:100',
            'program_type' => 'nullable|in:Academic,Target',
            'class_id' => 'nullable|exists:classes,id',
            'batch_id' => 'required|exists:batches,id',
            'subject' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required|string|max:50',
            'total_marks' => 'required|integer|min:10',
            'pass_marks' => 'required|integer|min:1',
            'room' => 'nullable|string|max:50',
            'status' => 'required|in:Upcoming,Completed,Ongoing',
        ]);

        $batch = Batch::find($validated['batch_id']);
        $validated['class_id'] = $validated['class_id'] ?? $batch?->class_id;

        if (empty($validated['program_type'])) {
            $validated['program_type'] = $batch?->program_type ?? 'Academic';
        }

        $typeObj = ExamType::firstOrCreate(
            ['name' => $validated['exam_type']],
            ['slug' => Str::slug($validated['exam_type']), 'status' => 'Active']
        );
        $validated['exam_type_id'] = $typeObj->id;
        $validated['exam_type'] = $typeObj->name;

        $subObj = Subject::where('name', $validated['subject'])
            ->when($validated['class_id'], fn($q, $cid) => $q->where('class_id', $cid))
            ->first();

        if (!$subObj) {
            $subObj = Subject::firstOrCreate(
                ['name' => $validated['subject'], 'class_id' => $validated['class_id']],
                [
                    'code' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $validated['subject']), 0, 4)) . '-' . ($validated['class_id'] ?? '101'),
                    'status' => 'Active'
                ]
            );
        }
        $validated['subject_id'] = $subObj->id;
        $validated['subject'] = $subObj->name;

        Exam::create($validated);

        return redirect()->route('admin.exams')->with('success', 'Exam scheduled successfully!');
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|string|max:100',
            'program_type' => 'nullable|in:Academic,Target',
            'class_id' => 'nullable|exists:classes,id',
            'batch_id' => 'required|exists:batches,id',
            'subject' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required|string|max:50',
            'total_marks' => 'required|integer|min:10',
            'pass_marks' => 'required|integer|min:1',
            'room' => 'nullable|string|max:50',
            'status' => 'required|in:Upcoming,Completed,Ongoing',
        ]);

        $batch = Batch::find($validated['batch_id']);
        $validated['class_id'] = $validated['class_id'] ?? $batch?->class_id;

        if (empty($validated['program_type'])) {
            $validated['program_type'] = $batch?->program_type ?? ($exam->program_type ?? 'Academic');
        }

        $typeObj = ExamType::firstOrCreate(
            ['name' => $validated['exam_type']],
            ['slug' => Str::slug($validated['exam_type']), 'status' => 'Active']
        );
        $validated['exam_type_id'] = $typeObj->id;
        $validated['exam_type'] = $typeObj->name;

        $subObj = Subject::where('name', $validated['subject'])
            ->when($validated['class_id'], fn($q, $cid) => $q->where('class_id', $cid))
            ->first();

        if (!$subObj) {
            $subObj = Subject::firstOrCreate(
                ['name' => $validated['subject'], 'class_id' => $validated['class_id']],
                [
                    'code' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $validated['subject']), 0, 4)) . '-' . ($validated['class_id'] ?? '101'),
                    'status' => 'Active'
                ]
            );
        }
        $validated['subject_id'] = $subObj->id;
        $validated['subject'] = $subObj->name;

        $exam->update($validated);

        return redirect()->route('admin.exams')->with('success', 'Exam updated successfully!');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('admin.exams')->with('success', 'Exam deleted successfully!');
    }

    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:exam_types,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'Active';

        ExamType::create($validated);

        return redirect()->route('admin.exams')->with('success', 'Exam type "' . $validated['name'] . '" added successfully!');
    }

    public function updateType(Request $request, $id)
    {
        $type = ExamType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:exam_types,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $oldName = $type->name;
        $validated['slug'] = Str::slug($validated['name']);

        $type->update($validated);

        Exam::where('exam_type', $oldName)->update(['exam_type' => $validated['name']]);

        return redirect()->route('admin.exams')->with('success', 'Exam type updated successfully!');
    }

    public function destroyType($id)
    {
        $type = ExamType::findOrFail($id);
        Exam::where('exam_type_id', $type->id)->update(['exam_type_id' => null]);
        $type->delete();

        return redirect()->route('admin.exams')->with('success', 'Exam type deleted successfully!');
    }
}
