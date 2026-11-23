<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Batch;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with(['batch'])->latest('date');

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $exams = $query->paginate(12);
        $batches = Batch::all();

        return view('admin.exams', compact('exams', 'batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|string|max:100',
            'batch_id' => 'required|exists:batches,id',
            'subject' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required|string|max:50',
            'total_marks' => 'required|integer|min:10',
            'pass_marks' => 'required|integer|min:1',
            'room' => 'nullable|string|max:50',
            'status' => 'required|in:Upcoming,Completed,Ongoing',
        ]);

        Exam::create($validated);

        return redirect()->route('admin.exams')->with('success', 'Exam scheduled successfully!');
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|string|max:100',
            'batch_id' => 'required|exists:batches,id',
            'subject' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'required|string|max:50',
            'total_marks' => 'required|integer|min:10',
            'pass_marks' => 'required|integer|min:1',
            'room' => 'nullable|string|max:50',
            'status' => 'required|in:Upcoming,Completed,Ongoing',
        ]);

        $exam->update($validated);

        return redirect()->route('admin.exams')->with('success', 'Exam updated successfully!');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('admin.exams')->with('success', 'Exam deleted successfully!');
    }
}
