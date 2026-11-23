<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicClass;
use App\Models\Student;
use App\Models\Batch;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['name' => 'Class IX', 'code' => 'C09', 'numeric_level' => 9, 'description' => 'SSC Foundation Batch & Core Subjects', 'status' => 'Active'],
            ['name' => 'Class X', 'code' => 'C10', 'numeric_level' => 10, 'description' => 'SSC Candidate Special Batch & Model Tests', 'status' => 'Active'],
            ['name' => 'Class XI', 'code' => 'C11', 'numeric_level' => 11, 'description' => 'HSC 1st Year Science, Commerce & Arts', 'status' => 'Active'],
            ['name' => 'Class XII', 'code' => 'C12', 'numeric_level' => 12, 'description' => 'HSC 2nd Year & Pre-Board Preparation', 'status' => 'Active'],
            ['name' => 'University Admission', 'code' => 'ADM', 'numeric_level' => 13, 'description' => 'Varsity, Medical & Engineering Admission Test Preparation', 'status' => 'Active'],
        ];

        foreach ($classes as $c) {
            $class = AcademicClass::firstOrCreate(['name' => $c['name']], $c);

            // Connect existing students and batches with matching class_name
            Student::where('class_name', $c['name'])->whereNull('class_id')->update(['class_id' => $class->id]);
            Batch::where('class_name', $c['name'])->whereNull('class_id')->update(['class_id' => $class->id]);
        }
    }
}
