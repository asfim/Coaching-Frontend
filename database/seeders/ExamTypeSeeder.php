<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamType;
use App\Models\Exam;
use Illuminate\Support\Str;

class ExamTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Model Test', 'description' => 'Comprehensive mock exam following board/admission pattern'],
            ['name' => 'Monthly Test', 'description' => 'Monthly evaluation exam on syllabus covered'],
            ['name' => 'Term Final', 'description' => 'Semester or term end examination'],
            ['name' => 'Class Test', 'description' => 'Regular weekly or bi-weekly chapter test'],
            ['name' => 'Weekly Quiz', 'description' => 'Short 20-30 minute quick assessment'],
            ['name' => 'Admission Test', 'description' => 'Competitive college/university admission mock test'],
            ['name' => 'Scholarship Exam', 'description' => 'Talent search and fee waiver eligibility exam'],
        ];

        foreach ($types as $t) {
            $record = ExamType::firstOrCreate(
                ['name' => $t['name']],
                [
                    'slug' => Str::slug($t['name']),
                    'description' => $t['description'],
                    'status' => 'Active',
                ]
            );

            // Connect existing exams with this exam_type string
            Exam::where('exam_type', $t['name'])->whereNull('exam_type_id')->update(['exam_type_id' => $record->id]);
        }
    }
}
