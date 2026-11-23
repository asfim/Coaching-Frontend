<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicClass;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Result;
use App\Models\ResultItem;

class AcademicAndTargetProgramSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update existing classes to 'Academic'
        AcademicClass::whereIn('code', ['C09', 'C10', 'C11', 'C12'])->update(['class_type' => 'Academic']);

        // 2. Create Target Batch Classes
        $engTargetClass = AcademicClass::firstOrCreate(
            ['code' => 'TGT-ENG'],
            [
                'name' => 'Engineering Target Batch (BUET / RUET / KUET)',
                'class_type' => 'Target',
                'numeric_level' => 13,
                'description' => 'Rigorous engineering admission training, advanced problem solving and mock tests',
                'status' => 'Active',
            ]
        );

        $medTargetClass = AcademicClass::firstOrCreate(
            ['code' => 'TGT-MED'],
            [
                'name' => 'Medical Target Batch (MBBS / BDS)',
                'class_type' => 'Target',
                'numeric_level' => 14,
                'description' => 'Medical college admission preparation with intensive biology, chemistry and negative marking tests',
                'status' => 'Active',
            ]
        );

        $varTargetClass = AcademicClass::firstOrCreate(
            ['code' => 'TGT-VAR'],
            [
                'name' => 'Varsity Admission Target Batch (Dhaka Univ A/B/C)',
                'class_type' => 'Target',
                'numeric_level' => 15,
                'description' => 'Public and private university admission test preparation with MCQ and written tactics',
                'status' => 'Active',
            ]
        );

        // 3. Batches: Set existing to Academic, and create Target Batches
        Batch::whereNull('program_type')->orWhere('program_type', '')->update(['program_type' => 'Academic']);

        $engBatch = Batch::firstOrCreate(
            ['batch_code' => 'ENG-T26'],
            [
                'name' => 'Engineering Titans 2026',
                'program_type' => 'Target',
                'class_id' => $engTargetClass->id,
                'class_name' => $engTargetClass->name,
                'days' => 'Friday, Saturday',
                'time_slot' => '08:00 AM - 12:00 PM',
                'room' => 'Hall 1',
                'max_students' => 80,
                'status' => 'Active',
            ]
        );

        $medBatch = Batch::firstOrCreate(
            ['batch_code' => 'MED-T26'],
            [
                'name' => 'Medical Dreamers 2026',
                'program_type' => 'Target',
                'class_id' => $medTargetClass->id,
                'class_name' => $medTargetClass->name,
                'days' => 'Sunday, Tuesday, Thursday',
                'time_slot' => '02:00 PM - 05:00 PM',
                'room' => 'Hall 2',
                'max_students' => 75,
                'status' => 'Active',
            ]
        );

        // 4. Subjects: Set existing to Academic, and create Target Batch Subjects
        Subject::whereNull('program_type')->orWhere('program_type', '')->update(['program_type' => 'Academic']);

        $targetSubjects = [
            ['name' => 'Engineering Physics & Mechanics', 'code' => 'TGT-PHY', 'class_id' => $engTargetClass->id, 'description' => 'Advanced mechanics, thermodynamics & electrical circuit shortcuts'],
            ['name' => 'Engineering Mathematics (Calculus & Vectors)', 'code' => 'TGT-MATH', 'class_id' => $engTargetClass->id, 'description' => 'BUET standard calculus, integration and coordinate geometry'],
            ['name' => 'Advanced Organic & Physical Chemistry', 'code' => 'TGT-CHEM', 'class_id' => $engTargetClass->id, 'description' => 'Engineering chemical reactions and stoichiometry'],
            ['name' => 'Medical Biology (Zoology & Botany)', 'code' => 'TGT-BIO', 'class_id' => $medTargetClass->id, 'description' => 'Intensive medical MCQ biology, human physiology and genetics'],
            ['name' => 'Medical Chemistry & Equations', 'code' => 'TGT-MCHEM', 'class_id' => $medTargetClass->id, 'description' => 'Medical pattern inorganic and organic chemical analysis'],
            ['name' => 'General Knowledge & English Aptitude', 'code' => 'TGT-GK', 'class_id' => $medTargetClass->id, 'description' => 'Bangladesh & international affairs, medical English vocabulary'],
        ];

        foreach ($targetSubjects as $ts) {
            Subject::firstOrCreate(
                ['code' => $ts['code']],
                [
                    'name' => $ts['name'],
                    'program_type' => 'Target',
                    'class_id' => $ts['class_id'],
                    'description' => $ts['description'],
                    'status' => 'Active',
                ]
            );
        }

        // 5. Students: Assign half existing students to Target Batch for demonstration
        $students = Student::all();
        if ($students->count() >= 2) {
            $students[1]->update([
                'program_type' => 'Target',
                'class_id' => $engTargetClass->id,
                'class_name' => $engTargetClass->name,
                'batch_id' => $engBatch->id,
            ]);
        }
        if ($students->count() >= 4) {
            $students[3]->update([
                'program_type' => 'Target',
                'class_id' => $medTargetClass->id,
                'class_name' => $medTargetClass->name,
                'batch_id' => $medBatch->id,
            ]);
        }

        // 6. Exams: Set existing to Academic and create a Target Batch Exam
        Exam::whereNull('program_type')->orWhere('program_type', '')->update(['program_type' => 'Academic']);

        $targetExam = Exam::firstOrCreate(
            ['title' => 'BUET Central Model Test #1'],
            [
                'program_type' => 'Target',
                'exam_type' => 'Model Test',
                'class_id' => $engTargetClass->id,
                'batch_id' => $engBatch->id,
                'subject' => 'Engineering Mathematics (Calculus & Vectors)',
                'date' => now()->addDays(10)->toDateString(),
                'time' => '09:00 AM - 12:00 PM',
                'total_marks' => 200,
                'pass_marks' => 80,
                'room' => 'Hall 1',
                'status' => 'Upcoming',
            ]
        );

        // 7. Results: Set existing to Academic
        Result::whereNull('program_type')->orWhere('program_type', '')->update(['program_type' => 'Academic']);
    }
}
