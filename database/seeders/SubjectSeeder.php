<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\Result;
use App\Models\ResultItem;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Physics', 'code' => 'PHY-101', 'description' => 'Mechanics, Waves, Thermodynamics, Electricity & Modern Physics'],
            ['name' => 'Chemistry', 'code' => 'CHEM-101', 'description' => 'Organic, Inorganic & Physical Chemistry'],
            ['name' => 'Higher Mathematics', 'code' => 'HMATH-101', 'description' => 'Calculus, Trigonometry, Vectors & Coordinate Geometry'],
            ['name' => 'General Mathematics', 'code' => 'GMATH-101', 'description' => 'Algebra, Geometry, Arithmetic & Statistics'],
            ['name' => 'Biology', 'code' => 'BIO-101', 'description' => 'Botany, Zoology, Genetics & Cell Biology'],
            ['name' => 'Bangla 1st Paper', 'code' => 'BAN-101', 'description' => 'Literature, Poetry, Prose and Drama'],
            ['name' => 'Bangla 2nd Paper', 'code' => 'BAN-102', 'description' => 'Grammar, Essay, Letter and Comprehension'],
            ['name' => 'English 1st Paper', 'code' => 'ENG-101', 'description' => 'Reading, Vocabulary, Theme Writing and Summary'],
            ['name' => 'English 2nd Paper', 'code' => 'ENG-102', 'description' => 'Grammar, Formal Letters, Report and Composition'],
            ['name' => 'ICT', 'code' => 'ICT-101', 'description' => 'Information & Communication Technology, Programming, Web Design'],
            ['name' => 'Accounting', 'code' => 'ACC-101', 'description' => 'Financial Accounting, Principles & Ledgers'],
            ['name' => 'Finance & Banking', 'code' => 'FIN-101', 'description' => 'Business Finance, Banking and Insurance'],
            ['name' => 'General Science', 'code' => 'SCI-101', 'description' => 'Integrated science curriculum for junior classes'],
        ];

        foreach ($subjects as $s) {
            $record = Subject::firstOrCreate(
                ['code' => $s['code']],
                [
                    'name' => $s['name'],
                    'description' => $s['description'],
                    'status' => 'Active',
                ]
            );

            // Link existing exams with this subject name
            Exam::where('subject', $s['name'])->whereNull('subject_id')->update(['subject_id' => $record->id]);
        }

        // Backfill result_items for existing seeded results so marksheets display properly
        $existingResults = Result::with('items', 'exam')->get();
        foreach ($existingResults as $res) {
            if ($res->items()->count() === 0) {
                $subName = $res->exam?->subject ?: 'General Subject';
                $subObj = Subject::where('name', $subName)->first();

                ResultItem::create([
                    'result_id' => $res->id,
                    'subject_id' => $subObj?->id,
                    'subject_name' => $subName,
                    'obtained_marks' => $res->obtained_marks,
                    'total_marks' => $res->total_marks,
                    'gpa' => $res->gpa,
                    'grade' => $res->grade,
                ]);
            }
        }
    }
}
