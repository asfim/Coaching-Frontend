<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ResultItem;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $query = Result::with(['exam.batch', 'student', 'items'])->latest();

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%");
            });
        }

        $results = $query->paginate(15)->withQueryString();
        $exams = Exam::orderBy('date', 'desc')->get();
        $students = Student::orderBy('name')->get();
        $subjects = Subject::where('status', 'Active')->orderBy('name')->get();

        $totalResultsCount = Result::count();
        $academicResultsCount = Result::where('program_type', 'Academic')->count();
        $targetResultsCount = Result::where('program_type', 'Target')->count();

        return view('admin.results', compact(
            'results', 'exams', 'students', 'subjects',
            'totalResultsCount', 'academicResultsCount', 'targetResultsCount'
        ));
    }

    public function searchStudents(Request $request)
    {
        $q = $request->get('q', '');
        $students = Student::query()
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('student_id', 'like', "%{$q}%")
                        ->orWhere('class_name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->limit(25)
            ->get(['id', 'student_id', 'name', 'class_name', 'program_type', 'phone', 'avatar']);

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'program_type' => 'nullable|in:Academic,Target',
            'merit_position' => 'nullable|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        $calculated = $this->calculateResultData($request);

        $exam = Exam::find($request->exam_id);
        $student = Student::find($request->student_id);
        $programType = $request->input('program_type') 
            ?: ($exam?->program_type ?? ($student?->program_type ?? 'Academic'));
        $meritPos = $request->filled('merit_position') ? (int)$request->merit_position : null;

        $result = Result::updateOrCreate(
            ['exam_id' => $request->exam_id, 'student_id' => $request->student_id],
            [
                'exam_id' => $request->exam_id,
                'student_id' => $request->student_id,
                'program_type' => $programType,
                'merit_position' => $meritPos,
                'obtained_marks' => $calculated['obtained_marks'],
                'total_marks' => $calculated['total_marks'],
                'gpa' => $calculated['gpa'],
                'grade' => $calculated['grade'],
                'remarks' => $request->remarks,
            ]
        );

        // Sync Result Items
        $this->syncResultItems($result, $calculated['items']);

        return redirect()->route('admin.results')->with('success', 'Exam result recorded successfully with subject-wise evaluation and ranking!');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()?->canEdit('results')) {
            return redirect()->back()->with('error', 'You do not have permission to edit results.');
        }

        $result = Result::findOrFail($id);

        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'program_type' => 'nullable|in:Academic,Target',
            'merit_position' => 'nullable|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        $calculated = $this->calculateResultData($request);

        $exam = Exam::find($request->exam_id);
        $student = Student::find($request->student_id);
        $programType = $request->input('program_type') 
            ?: ($exam?->program_type ?? ($student?->program_type ?? ($result->program_type ?? 'Academic')));
        $meritPos = $request->filled('merit_position') ? (int)$request->merit_position : $result->merit_position;

        $result->update([
            'exam_id' => $request->exam_id,
            'student_id' => $request->student_id,
            'program_type' => $programType,
            'merit_position' => $meritPos,
            'obtained_marks' => $calculated['obtained_marks'],
            'total_marks' => $calculated['total_marks'],
            'gpa' => $calculated['gpa'],
            'grade' => $calculated['grade'],
            'remarks' => $request->remarks,
        ]);

        // Sync Result Items
        $this->syncResultItems($result, $calculated['items']);

        return redirect()->route('admin.results')->with('success', 'Exam result updated successfully!');
    }

    public function marksheet($id)
    {
        $result = Result::with(['exam.batch', 'student.batch', 'student.academicClass', 'items.subject'])->findOrFail($id);
        return view('admin.marksheet', compact('result'));
    }

    public function destroy($id)
    {
        if (!auth()->user()?->canDelete('results')) {
            return redirect()->back()->with('error', 'You do not have permission to delete results.');
        }

        $result = Result::findOrFail($id);
        $result->items()->delete();
        $result->delete();

        return redirect()->route('admin.results')->with('success', 'Result entry deleted successfully!');
    }

    private function calculateResultData(Request $request): array
    {
        $items = [];
        $totalObtained = 0;
        $totalFull = 0;
        $gpaList = [];
        $hasFailedSubject = false;

        if ($request->has('subjects') && is_array($request->subjects) && count($request->subjects) > 0) {
            foreach ($request->subjects as $row) {
                if (empty($row['subject_name'])) {
                    continue;
                }

                $subName = trim($row['subject_name']);
                $obtained = (float)($row['obtained_marks'] ?? 0);
                $full = (float)($row['total_marks'] ?? 100);
                if ($full <= 0) $full = 100;

                $pct = ($obtained / $full) * 100;
                $subGpa = $this->getGpaFromPct($pct);
                $subGrade = $this->getGradeFromPct($pct);

                if ($subGpa == 0.00) {
                    $hasFailedSubject = true;
                }

                $gpaList[] = $subGpa;
                $totalObtained += $obtained;
                $totalFull += $full;

                $subObj = Subject::where('name', $subName)->first();

                $items[] = [
                    'subject_id' => $subObj?->id,
                    'subject_name' => $subName,
                    'obtained_marks' => $obtained,
                    'total_marks' => $full,
                    'gpa' => $subGpa,
                    'grade' => $subGrade,
                ];
            }
        }

        // Fallback for single mark submission
        if (empty($items)) {
            $exam = Exam::find($request->exam_id);
            $subName = $exam?->subject ?: 'General Subject';
            $obtained = (float)($request->obtained_marks ?? 0);
            $full = (float)($request->total_marks ?? 100);
            if ($full <= 0) $full = 100;

            $pct = ($obtained / $full) * 100;
            $subGpa = $this->getGpaFromPct($pct);
            $subGrade = $this->getGradeFromPct($pct);

            $subObj = Subject::where('name', $subName)->first();

            $totalObtained = $obtained;
            $totalFull = $full;
            $gpaList[] = $subGpa;
            if ($subGpa == 0.00) {
                $hasFailedSubject = true;
            }

            $items[] = [
                'subject_id' => $subObj?->id,
                'subject_name' => $subName,
                'obtained_marks' => $obtained,
                'total_marks' => $full,
                'gpa' => $subGpa,
                'grade' => $subGrade,
            ];
        }

        if ($hasFailedSubject) {
            $overallGpa = 0.00;
            $overallGrade = 'F';
        } else {
            $count = count($gpaList);
            $overallGpa = $count > 0 ? round(array_sum($gpaList) / $count, 2) : 0.00;
            $overallGrade = $this->getGradeFromGpa($overallGpa);
        }

        return [
            'obtained_marks' => $totalObtained,
            'total_marks' => $totalFull,
            'gpa' => $overallGpa,
            'grade' => $overallGrade,
            'items' => $items,
        ];
    }

    private function syncResultItems(Result $result, array $items): void
    {
        $result->items()->delete();
        foreach ($items as $item) {
            $result->items()->create($item);
        }
    }

    private function getGpaFromPct(float $pct): float
    {
        if ($pct >= 80) return 5.00;
        if ($pct >= 70) return 4.00;
        if ($pct >= 60) return 3.50;
        if ($pct >= 50) return 3.00;
        if ($pct >= 40) return 2.00;
        if ($pct >= 33) return 1.00;
        return 0.00;
    }

    private function getGradeFromPct(float $pct): string
    {
        if ($pct >= 80) return 'A+';
        if ($pct >= 70) return 'A';
        if ($pct >= 60) return 'A-';
        if ($pct >= 50) return 'B';
        if ($pct >= 40) return 'C';
        if ($pct >= 33) return 'D';
        return 'F';
    }

    private function getGradeFromGpa(float $gpa): string
    {
        if ($gpa >= 5.00) return 'A+';
        if ($gpa >= 4.00) return 'A';
        if ($gpa >= 3.50) return 'A-';
        if ($gpa >= 3.00) return 'B';
        if ($gpa >= 2.00) return 'C';
        if ($gpa >= 1.00) return 'D';
        return 'F';
    }
}
