<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\Batch;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\AcademicClass;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function index(Request $request)
    {
        $batchId = $request->input('batch_id');
        $batches = Batch::with('academicClass')->get();
        $selectedBatch = $batchId ? Batch::find($batchId) : $batches->first();

        $routines = $selectedBatch ? Routine::with(['teacher'])
            ->where('batch_id', $selectedBatch->id)
            ->get()
            ->groupBy('day') : collect();

        $teachers = Teacher::where('status', 'Active')->get();
        if ($teachers->isEmpty()) {
            $teachers = Teacher::all();
        }

        $classes = AcademicClass::where('status', 'Active')->orderBy('numeric_level')->get();
        $subjects = Subject::where('status', 'Active')->get();

        return view('admin.routine', compact('batches', 'selectedBatch', 'routines', 'teachers', 'classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'day' => 'required|string|max:20',
            'subject' => 'required|string|max:100',
            'teacher_id' => 'nullable|exists:teachers,id',
            'start_time' => 'required|string|max:20',
            'end_time' => 'required|string|max:20',
            'room' => 'nullable|string|max:50',
        ]);

        Routine::create($validated);

        return redirect()->route('admin.routine', ['batch_id' => $validated['batch_id']])
            ->with('success', 'Routine slot added successfully!');
    }

    public function update(Request $request, $id)
    {
        $routine = Routine::findOrFail($id);

        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'day' => 'required|string|max:20',
            'subject' => 'required|string|max:100',
            'teacher_id' => 'nullable|exists:teachers,id',
            'start_time' => 'required|string|max:20',
            'end_time' => 'required|string|max:20',
            'room' => 'nullable|string|max:50',
        ]);

        $routine->update($validated);

        return redirect()->route('admin.routine', ['batch_id' => $validated['batch_id']])
            ->with('success', 'Routine slot updated successfully!');
    }

    public function destroy($id)
    {
        $routine = Routine::findOrFail($id);
        $batchId = $routine->batch_id;
        $routine->delete();

        return redirect()->route('admin.routine', ['batch_id' => $batchId])
            ->with('success', 'Routine slot deleted successfully!');
    }
}
