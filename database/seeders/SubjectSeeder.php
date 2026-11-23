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
        $class9 = AcademicClass::where('code', 'C09')->orWhere('name', 'like', '%Class IX%')->first();
        $class10 = AcademicClass::where('code', 'C10')->orWhere('name', 'like', '%Class X%')->first();
        $class11 = AcademicClass::where('code', 'C11')->orWhere('name', 'like', '%Class XI%')->first();
        $class12 = AcademicClass::where('code', 'C12')->orWhere('name', 'like', '%Class XII%')->first();

        // 1. Class IX Subjects
        if ($class9) {
            $class9Subjects = [
                ['name' => 'Bangla 1st Paper', 'code' => 'C09-BAN1', 'description' => 'Secondary Bengali Literature and Prose'],
                ['name' => 'Bangla 2nd Paper', 'code' => 'C09-BAN2', 'description' => 'Secondary Bengali Grammar and Writing'],
                ['name' => 'English 1st Paper', 'code' => 'C09-ENG1', 'description' => 'Secondary English Reading and Comprehension'],
                ['name' => 'English 2nd Paper', 'code' => 'C09-ENG2', 'description' => 'Secondary English Grammar and Composition'],
                ['name' => 'General Mathematics', 'code' => 'C09-GMATH', 'description' => 'Algebra, Geometry, and Basic Trigonometry'],
                ['name' => 'Higher Mathematics', 'code' => 'C09-HMATH', 'description' => 'Advanced Geometry, Vectors, and Coordinate Geometry'],
                ['name' => 'Physics', 'code' => 'C09-PHY', 'description' => 'Physical Quantities, Motion, Force, and Work Energy'],
                ['name' => 'Chemistry', 'code' => 'C09-CHEM', 'description' => 'Atomic Structure, Chemical Bonding, and Periodic Table'],
                ['name' => 'Biology', 'code' => 'C09-BIO', 'description' => 'Cell Structure, Tissues, Cell Division, and Genetics'],
                ['name' => 'ICT', 'code' => 'C09-ICT', 'description' => 'Information Communication Technology, Computer Ethics and Internet'],
            ];

            foreach ($class9Subjects as $s) {
                Subject::updateOrCreate(
                    ['code' => $s['code']],
                    [
                        'name' => $s['name'],
                        'class_id' => $class9->id,
                        'description' => $s['description'],
                        'status' => 'Active',
                    ]
                );
            }
        }

        // 2. Class X (SSC) Subjects
        if ($class10) {
            $class10Subjects = [
                ['name' => 'Bangla 1st Paper', 'code' => 'C10-BAN1', 'description' => 'SSC Bengali Literature, Poems, and Novel'],
                ['name' => 'Bangla 2nd Paper', 'code' => 'C10-BAN2', 'description' => 'SSC Bengali Grammar, Essays, and Applications'],
                ['name' => 'English 1st Paper', 'code' => 'C10-ENG1', 'description' => 'SSC English Passage, Theme Writing, and Dialogues'],
                ['name' => 'English 2nd Paper', 'code' => 'C10-ENG2', 'description' => 'SSC Applied Grammar and Composition'],
                ['name' => 'General Mathematics', 'code' => 'C10-GMATH', 'description' => 'SSC Board Mathematics with Trigonometry & Statistics'],
                ['name' => 'Higher Mathematics', 'code' => 'C10-HMATH', 'description' => 'Calculus foundation, Solid Geometry & Probability'],
                ['name' => 'Physics', 'code' => 'C10-PHY', 'description' => 'Light, Sound, Current Electricity, and Modern Physics'],
                ['name' => 'Chemistry', 'code' => 'C10-CHEM', 'description' => 'Chemical Reactions, Acids-Bases, and Hydrocarbons'],
                ['name' => 'Biology', 'code' => 'C10-BIO', 'description' => 'Life Processes, Ecosystems, and Biotechnology'],
                ['name' => 'ICT', 'code' => 'C10-ICT', 'description' => 'SSC Board ICT, Database, and Web Technologies'],
            ];

            foreach ($class10Subjects as $s) {
                Subject::updateOrCreate(
                    ['code' => $s['code']],
                    [
                        'name' => $s['name'],
                        'class_id' => $class10->id,
                        'description' => $s['description'],
                        'status' => 'Active',
                    ]
                );
            }
        }

        // 3. Class XI (HSC 1st Year) Subjects
        if ($class11) {
            $class11Subjects = [
                ['name' => 'Physics 1st Paper', 'code' => 'C11-PHY1', 'description' => 'Vectors, Newtonian Mechanics, Work Energy, Waves, and Thermal Physics'],
                ['name' => 'Chemistry 1st Paper', 'code' => 'C11-CHEM1', 'description' => 'Qualitative Chemistry, Periodic Trends, Chemical Changes, and Laboratory'],
                ['name' => 'Higher Mathematics 1st Paper', 'code' => 'C11-HMATH1', 'description' => 'Matrices, Straight Lines, Circles, Trigonometry, and Calculus (Differentiation)'],
                ['name' => 'Biology 1st Paper (Botany)', 'code' => 'C11-BIO1', 'description' => 'Cell Biology, Microorganisms, Taxonomy, Plant Physiology, and Biotechnology'],
                ['name' => 'Bangla 1st Paper', 'code' => 'C11-BAN1', 'description' => 'HSC Bengali Prose, Poetry, and Red Blue Canvas'],
                ['name' => 'English 1st Paper', 'code' => 'C11-ENG1', 'description' => 'HSC English Reading, Summarizing, and Creative Expression'],
                ['name' => 'ICT', 'code' => 'C11-ICT', 'description' => 'HSC ICT: Web Design (HTML), Number Systems, and Networking'],
                ['name' => 'Accounting 1st Paper', 'code' => 'C11-ACC1', 'description' => 'Principles of Accounting, Journal, Ledger, and Trail Balance'],
                ['name' => 'Finance & Banking 1st Paper', 'code' => 'C11-FIN1', 'description' => 'Principles of Finance, Time Value of Money, and Capital Budgeting'],
            ];

            foreach ($class11Subjects as $s) {
                Subject::updateOrCreate(
                    ['code' => $s['code']],
                    [
                        'name' => $s['name'],
                        'class_id' => $class11->id,
                        'description' => $s['description'],
                        'status' => 'Active',
                    ]
                );
            }
        }

        // 4. Class XII (HSC 2nd Year) Subjects
        if ($class12) {
            $class12Subjects = [
                ['name' => 'Physics 2nd Paper', 'code' => 'C12-PHY2', 'description' => 'Thermodynamics, Electrostatics, Magnetism, Optics, and Modern Physics'],
                ['name' => 'Chemistry 2nd Paper', 'code' => 'C12-CHEM2', 'description' => 'Environmental Chemistry, Organic Chemistry, Quantitative Chemistry, and Electrochemistry'],
                ['name' => 'Higher Mathematics 2nd Paper', 'code' => 'C12-HMATH2', 'description' => 'Complex Numbers, Polynomials, Conics, Inverse Trigonometry, and Integration'],
                ['name' => 'Biology 2nd Paper (Zoology)', 'code' => 'C12-BIO2', 'description' => 'Animal Diversity, Human Physiology, Genetics, Evolution, and Behavior'],
                ['name' => 'Bangla 2nd Paper', 'code' => 'C12-BAN2', 'description' => 'HSC Grammar Rules, Pronunciation, Reports, and Composition'],
                ['name' => 'English 2nd Paper', 'code' => 'C12-ENG2', 'description' => 'HSC Advanced Grammar, Formal Reports, and Essays'],
                ['name' => 'ICT', 'code' => 'C12-ICT', 'description' => 'HSC ICT: C Programming Language and Database Management Systems'],
                ['name' => 'Accounting 2nd Paper', 'code' => 'C12-ACC2', 'description' => 'Partnership, Joint Stock Companies, and Cost Accounting'],
                ['name' => 'Finance & Banking 2nd Paper', 'code' => 'C12-FIN2', 'description' => 'Commercial Banking, Central Banking, and Insurance Principles'],
            ];

            foreach ($class12Subjects as $s) {
                Subject::updateOrCreate(
                    ['code' => $s['code']],
                    [
                        'name' => $s['name'],
                        'class_id' => $class12->id,
                        'description' => $s['description'],
                        'status' => 'Active',
                    ]
                );
            }
        }

        // Connect existing exams with appropriate subjects
        $exams = Exam::whereNull('subject_id')->get();
        foreach ($exams as $exam) {
            $match = Subject::where('name', $exam->subject)->first();
            if ($match) {
                $exam->update(['subject_id' => $match->id]);
            }
        }

        // Ensure result_items backfilled
        $results = Result::with('items', 'exam')->get();
        foreach ($results as $res) {
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
