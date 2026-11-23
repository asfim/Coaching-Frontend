<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseCategory;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Science', 'description' => 'Physics, Chemistry, Biology and Mathematics courses'],
            ['name' => 'Commerce', 'description' => 'Accounting, Finance, Management and Economics'],
            ['name' => 'Arts', 'description' => 'Humanities, Social Sciences, History and Literature'],
            ['name' => 'SSC', 'description' => 'Secondary School Certificate candidate batches'],
            ['name' => 'HSC', 'description' => 'Higher Secondary Certificate preparation batches'],
            ['name' => 'University Admission', 'description' => 'Public & Private University admission test coaching'],
            ['name' => 'Medical Admission', 'description' => 'Medical college admission test preparation'],
            ['name' => 'Engineering Admission', 'description' => 'BUET and engineering university preparation'],
        ];

        foreach ($categories as $cat) {
            $catRecord = CourseCategory::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                    'status' => 'Active',
                ]
            );

            // Connect existing courses
            Course::where('category', $cat['name'])->whereNull('category_id')->update(['category_id' => $catRecord->id]);
        }
    }
}
