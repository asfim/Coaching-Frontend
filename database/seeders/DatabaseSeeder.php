<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Routine;
use App\Models\Fee;
use App\Models\Account;
use App\Models\Notice;
use App\Models\SmsLog;
use App\Models\Setting;
use App\Models\User;
use App\Models\AcademicClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Academic Classes
        $class9 = AcademicClass::firstOrCreate(
            ['name' => 'Class IX'],
            ['code' => 'C09', 'numeric_level' => 9, 'description' => 'SSC Foundation Batch & Core Subjects', 'status' => 'Active']
        );
        $class10 = AcademicClass::firstOrCreate(
            ['name' => 'Class X'],
            ['code' => 'C10', 'numeric_level' => 10, 'description' => 'SSC Candidate Special Batch & Model Tests', 'status' => 'Active']
        );
        $class11 = AcademicClass::firstOrCreate(
            ['name' => 'Class XI'],
            ['code' => 'C11', 'numeric_level' => 11, 'description' => 'HSC 1st Year Science, Commerce & Arts', 'status' => 'Active']
        );
        $class12 = AcademicClass::firstOrCreate(
            ['name' => 'Class XII'],
            ['code' => 'C12', 'numeric_level' => 12, 'description' => 'HSC 2nd Year & Pre-Board Preparation', 'status' => 'Active']
        );
        $classAdm = AcademicClass::firstOrCreate(
            ['name' => 'University Admission'],
            ['code' => 'ADM', 'numeric_level' => 13, 'description' => 'Varsity, Medical & Engineering Admission Test Preparation', 'status' => 'Active']
        );

        // 1. Courses
        $course1 = Course::create([
            'title' => 'SSC Science Complete Batch 2026',
            'slug' => 'ssc-science-complete-batch-2026',
            'category' => 'Science',
            'duration' => '12 Months',
            'seats' => 85,
            'fee' => 2500.00,
            'description' => 'Comprehensive preparation for Physics, Chemistry, Biology, and Higher Mathematics for SSC 2026 examinees.',
            'image' => 'images/hero_classroom_1790497065510.jpg',
            'status' => 'Active',
        ]);

        $course2 = Course::create([
            'title' => 'HSC Higher Math & Physics Special',
            'slug' => 'hsc-higher-math-physics-special',
            'category' => 'HSC',
            'duration' => '18 Months',
            'seats' => 120,
            'fee' => 3500.00,
            'description' => 'Advanced theoretical concept clearing, board question analysis, and problem-solving for A+ aspirants.',
            'image' => 'images/students_group_study_1790497179383.jpg',
            'status' => 'Active',
        ]);

        $course3 = Course::create([
            'title' => 'University Admission Crash Course',
            'slug' => 'university-admission-crash-course',
            'category' => 'Admission',
            'duration' => '6 Months',
            'seats' => 200,
            'fee' => 5000.00,
            'description' => 'Intensive model tests, shortcut techniques, and guidance for DU ' . "A" . ' Unit and BUET admission test.',
            'image' => 'images/exam_hall_1790497202709.jpg',
            'status' => 'Active',
        ]);

        $course4 = Course::create([
            'title' => 'SSC English & ICT Foundation',
            'slug' => 'ssc-english-ict-foundation',
            'category' => 'Commerce',
            'duration' => '6 Months',
            'seats' => 60,
            'fee' => 1800.00,
            'description' => 'Grammar mastery, creative writing, and practical ICT problem-solving batch.',
            'image' => 'images/about_building_1790497156877.jpg',
            'status' => 'Active',
        ]);

        // 2. Batches
        $batch1 = Batch::create([
            'course_id' => $course1->id,
            'name' => 'SSC Batch A',
            'batch_code' => 'SSC-A',
            'class_name' => 'Class X',
            'days' => 'Sun, Tue, Thu',
            'time_slot' => '09:00 AM - 11:00 AM',
            'room' => 'Room 201',
            'max_students' => 45,
            'status' => 'Active',
        ]);

        $batch2 = Batch::create([
            'course_id' => $course1->id,
            'name' => 'SSC Batch B',
            'batch_code' => 'SSC-B',
            'class_name' => 'Class X',
            'days' => 'Sat, Mon, Wed',
            'time_slot' => '11:30 AM - 01:30 PM',
            'room' => 'Room 202',
            'max_students' => 40,
            'status' => 'Active',
        ]);

        $batch3 = Batch::create([
            'course_id' => $course2->id,
            'name' => 'HSC Batch A',
            'batch_code' => 'HSC-A',
            'class_name' => 'Class XI',
            'days' => 'Sun, Tue, Thu',
            'time_slot' => '02:00 PM - 04:00 PM',
            'room' => 'Room 301',
            'max_students' => 50,
            'status' => 'Active',
        ]);

        $batch4 = Batch::create([
            'course_id' => $course2->id,
            'name' => 'HSC Batch B',
            'batch_code' => 'HSC-B',
            'class_name' => 'Class XII',
            'days' => 'Sat, Mon, Wed',
            'time_slot' => '04:00 PM - 06:00 PM',
            'room' => 'Room 302',
            'max_students' => 50,
            'status' => 'Active',
        ]);

        // 3. Teachers
        $teacher1 = Teacher::create([
            'name' => 'Prof. Abdul Karim',
            'email' => 'karim@edumanagepro.com.bd',
            'phone' => '01711-223344',
            'designation' => 'Head of Mathematics',
            'subject' => 'Higher Math',
            'qualification' => 'Ph.D., DU',
            'salary' => 45000.00,
            'join_date' => '2016-01-10',
            'image' => 'images/teacher_male_2_1790497121228.jpg',
            'bio' => 'Over 15 years experience mentoring top board rankers in SSC and HSC Mathematics.',
            'status' => 'Active',
        ]);

        $teacher2 = Teacher::create([
            'name' => 'Md. Rafiqul Islam',
            'email' => 'rafiq@edumanagepro.com.bd',
            'phone' => '01812-334455',
            'designation' => 'Senior Physics Instructor',
            'subject' => 'Physics & Math',
            'qualification' => 'M.Sc., BUET',
            'salary' => 40000.00,
            'join_date' => '2018-03-15',
            'image' => 'images/teacher_male_1_1790497096029.jpg',
            'bio' => 'Known for intuitive visual demonstrations and problem-solving speed techniques.',
            'status' => 'Active',
        ]);

        $teacher3 = Teacher::create([
            'name' => 'Nasrin Sultana',
            'email' => 'nasrin@edumanagepro.com.bd',
            'phone' => '01913-445566',
            'designation' => 'Faculty, General Studies',
            'subject' => 'Bangladesh Studies',
            'qualification' => 'M.A., DU',
            'salary' => 35000.00,
            'join_date' => '2019-07-01',
            'image' => 'images/teacher_female_1_1790497081063.jpg',
            'bio' => 'Specialized in creative question answering and critical thinking training.',
            'status' => 'Active',
        ]);

        $teacher4 = Teacher::create([
            'name' => 'Dr. Sadia Sharmin',
            'email' => 'sadia@edumanagepro.com.bd',
            'phone' => '01614-556677',
            'designation' => 'Senior Lecturer, Biology',
            'subject' => 'Biology & Chemistry',
            'qualification' => 'M.B.B.S, DMC',
            'salary' => 42000.00,
            'join_date' => '2020-02-01',
            'image' => 'images/teacher_female_2_1790497140028.jpg',
            'bio' => 'Experienced in pre-medical admission guidance and secondary science coaching.',
            'status' => 'Active',
        ]);

        // 4. Students
        $studentsData = [
            ['student_id' => 'STD-2401', 'name' => 'Fatima Begum', 'phone' => '01712-345678', 'guardian_name' => 'Karim Hossain', 'guardian_phone' => '01712-000001', 'class_name' => 'Class X', 'course_id' => $course1->id, 'batch_id' => $batch1->id, 'admission_date' => '2026-01-15', 'blood_group' => 'A+', 'avatar' => 'images/student_female_profile_1790497748277.jpg', 'status' => 'Active'],
            ['student_id' => 'STD-2402', 'name' => 'Rakibul Hasan', 'phone' => '01813-456789', 'guardian_name' => 'Jamal Hasan', 'guardian_phone' => '01813-000002', 'class_name' => 'Class XI', 'course_id' => $course2->id, 'batch_id' => $batch3->id, 'admission_date' => '2026-02-10', 'blood_group' => 'B+', 'avatar' => 'images/student_male_profile_1790497728356.jpg', 'status' => 'Active'],
            ['student_id' => 'STD-2403', 'name' => 'Sumaiya Islam', 'phone' => '01914-567890', 'guardian_name' => 'Rahim Islam', 'guardian_phone' => '01914-000003', 'class_name' => 'Class X', 'course_id' => $course1->id, 'batch_id' => $batch1->id, 'admission_date' => '2026-03-05', 'blood_group' => 'O+', 'avatar' => 'images/student_female_profile_1790497748277.jpg', 'status' => 'Active'],
            ['student_id' => 'STD-2404', 'name' => 'Mahbub Rahman', 'phone' => '01615-678901', 'guardian_name' => 'Anwar Rahman', 'guardian_phone' => '01615-000004', 'class_name' => 'Class XII', 'course_id' => $course2->id, 'batch_id' => $batch4->id, 'admission_date' => '2026-01-20', 'blood_group' => 'AB+', 'avatar' => 'images/student_male_profile_1790497728356.jpg', 'status' => 'Active'],
            ['student_id' => 'STD-2405', 'name' => 'Nusrat Jahan', 'phone' => '01716-789012', 'guardian_name' => 'Kabir Jahan', 'guardian_phone' => '01716-000005', 'class_name' => 'Class X', 'course_id' => $course1->id, 'batch_id' => $batch2->id, 'admission_date' => '2026-04-12', 'blood_group' => 'A-', 'avatar' => 'images/student_female_profile_1790497748277.jpg', 'status' => 'Active'],
            ['student_id' => 'STD-2406', 'name' => 'Tanvir Ahmed', 'phone' => '01817-890123', 'guardian_name' => 'Mizanur Rahman', 'guardian_phone' => '01817-000006', 'class_name' => 'Class XI', 'course_id' => $course2->id, 'batch_id' => $batch3->id, 'admission_date' => '2026-02-18', 'blood_group' => 'B-', 'avatar' => 'images/student_male_profile_1790497728356.jpg', 'status' => 'Active'],
            ['student_id' => 'STD-2407', 'name' => 'Sadia Akter', 'phone' => '01918-901234', 'guardian_name' => 'Faruk Akter', 'guardian_phone' => '01918-000007', 'class_name' => 'Class X', 'course_id' => $course1->id, 'batch_id' => $batch1->id, 'admission_date' => '2026-05-01', 'blood_group' => 'O-', 'avatar' => 'images/student_female_profile_1790497748277.jpg', 'status' => 'Active'],
            ['student_id' => 'STD-2408', 'name' => 'Ariful Haque', 'phone' => '01619-012345', 'guardian_name' => 'Enamul Haque', 'guardian_phone' => '01619-000008', 'class_name' => 'Class XII', 'course_id' => $course2->id, 'batch_id' => $batch4->id, 'admission_date' => '2026-03-25', 'blood_group' => 'A+', 'avatar' => 'images/student_male_profile_1790497728356.jpg', 'status' => 'Active'],
        ];

        $students = [];
        foreach ($studentsData as $st) {
            $students[] = Student::create($st);
        }

        // 5. Attendance (Today & past dates)
        $today = now()->toDateString();
        $statuses = ['present', 'present', 'present', 'present', 'late', 'absent', 'present', 'leave'];
        foreach ($students as $index => $st) {
            Attendance::create([
                'student_id' => $st->id,
                'batch_id' => $st->batch_id,
                'date' => $today,
                'status' => $statuses[$index] ?? 'present',
                'remarks' => $statuses[$index] == 'late' ? '15 mins late' : null,
            ]);
        }

        // 6. Exams
        $exam1 = Exam::create([
            'title' => 'SSC Model Test 1 - Physics',
            'exam_type' => 'Model Test',
            'batch_id' => $batch1->id,
            'subject' => 'Physics',
            'date' => now()->addDays(8)->toDateString(),
            'time' => '09:00 AM - 12:00 PM',
            'total_marks' => 100,
            'pass_marks' => 40,
            'room' => 'Hall A',
            'status' => 'Upcoming',
        ]);

        $exam2 = Exam::create([
            'title' => 'HSC Monthly Evaluation - Higher Math',
            'exam_type' => 'Monthly Test',
            'batch_id' => $batch3->id,
            'subject' => 'Higher Math',
            'date' => now()->addDays(15)->toDateString(),
            'time' => '02:00 PM - 04:00 PM',
            'total_marks' => 100,
            'pass_marks' => 40,
            'room' => 'Hall B',
            'status' => 'Upcoming',
        ]);

        $exam3 = Exam::create([
            'title' => 'SSC September Monthly Exam',
            'exam_type' => 'Monthly Test',
            'batch_id' => $batch1->id,
            'subject' => 'General Science',
            'date' => now()->subDays(10)->toDateString(),
            'time' => '10:00 AM - 12:00 PM',
            'total_marks' => 100,
            'pass_marks' => 40,
            'room' => 'Hall A',
            'status' => 'Completed',
        ]);

        // 7. Results
        Result::create([
            'exam_id' => $exam3->id,
            'student_id' => $students[0]->id,
            'obtained_marks' => 94.00,
            'total_marks' => 100.00,
            'gpa' => 5.00,
            'grade' => 'A+',
            'remarks' => 'Outstanding performance',
        ]);

        Result::create([
            'exam_id' => $exam3->id,
            'student_id' => $students[1]->id,
            'obtained_marks' => 88.00,
            'total_marks' => 100.00,
            'gpa' => 4.83,
            'grade' => 'A+',
            'remarks' => 'Excellent problem solving',
        ]);

        Result::create([
            'exam_id' => $exam3->id,
            'student_id' => $students[2]->id,
            'obtained_marks' => 76.00,
            'total_marks' => 100.00,
            'gpa' => 4.00,
            'grade' => 'A',
            'remarks' => 'Good, needs work in MCQ',
        ]);

        Result::create([
            'exam_id' => $exam3->id,
            'student_id' => $students[3]->id,
            'obtained_marks' => 91.00,
            'total_marks' => 100.00,
            'gpa' => 5.00,
            'grade' => 'A+',
            'remarks' => 'Very thorough answers',
        ]);

        // 8. Routines
        $days = ['Sunday', 'Tuesday', 'Thursday'];
        foreach ($days as $day) {
            Routine::create([
                'batch_id' => $batch1->id,
                'day' => $day,
                'subject' => 'Physics',
                'teacher_id' => $teacher2->id,
                'start_time' => '09:00 AM',
                'end_time' => '10:15 AM',
                'room' => 'Room 201',
            ]);
            Routine::create([
                'batch_id' => $batch1->id,
                'day' => $day,
                'subject' => 'Higher Math',
                'teacher_id' => $teacher1->id,
                'start_time' => '10:30 AM',
                'end_time' => '11:45 AM',
                'room' => 'Room 201',
            ]);
        }

        // 9. Fees
        Fee::create([
            'invoice_no' => 'INV-2026-0001',
            'student_id' => $students[0]->id,
            'batch_id' => $batch1->id,
            'month' => 'October',
            'year' => 2026,
            'amount' => 2500.00,
            'discount' => 0.00,
            'paid_amount' => 2500.00,
            'due_amount' => 0.00,
            'payment_method' => 'bKash',
            'payment_date' => now()->toDateString(),
            'status' => 'Paid',
            'notes' => 'Received via bKash TrxID: 9X82KD9A',
        ]);

        Fee::create([
            'invoice_no' => 'INV-2026-0002',
            'student_id' => $students[1]->id,
            'batch_id' => $batch3->id,
            'month' => 'October',
            'year' => 2026,
            'amount' => 3500.00,
            'discount' => 0.00,
            'paid_amount' => 2000.00,
            'due_amount' => 1500.00,
            'payment_method' => 'Cash',
            'payment_date' => now()->toDateString(),
            'status' => 'Partial',
            'notes' => 'Remaining 1500 will pay by Oct 15',
        ]);

        Fee::create([
            'invoice_no' => 'INV-2026-0003',
            'student_id' => $students[2]->id,
            'batch_id' => $batch1->id,
            'month' => 'October',
            'year' => 2026,
            'amount' => 2500.00,
            'discount' => 0.00,
            'paid_amount' => 0.00,
            'due_amount' => 2500.00,
            'payment_method' => 'Cash',
            'payment_date' => null,
            'status' => 'Due',
            'notes' => 'Pending guardian confirmation',
        ]);

        Fee::create([
            'invoice_no' => 'INV-2026-0004',
            'student_id' => $students[3]->id,
            'batch_id' => $batch4->id,
            'month' => 'October',
            'year' => 2026,
            'amount' => 3500.00,
            'discount' => 0.00,
            'paid_amount' => 3500.00,
            'due_amount' => 0.00,
            'payment_method' => 'Bank',
            'payment_date' => now()->subDays(2)->toDateString(),
            'status' => 'Paid',
            'notes' => 'City Bank Online Deposit',
        ]);

        // 10. Accounts Transactions
        Account::create([
            'title' => 'Batch A October Fee Collection',
            'type' => 'income',
            'category' => 'Student Fee',
            'amount' => 35000.00,
            'payment_method' => 'Cash',
            'date' => now()->toDateString(),
            'reference' => 'VCH-INC-001',
            'description' => 'Collection from 14 students of SSC Batch A',
        ]);

        Account::create([
            'title' => 'HSC Special Online Admissions',
            'type' => 'income',
            'category' => 'Admission Fee',
            'amount' => 24500.00,
            'payment_method' => 'bKash',
            'date' => now()->subDay()->toDateString(),
            'reference' => 'VCH-INC-002',
            'description' => '7 new student admissions via bKash merchant',
        ]);

        Account::create([
            'title' => 'Faculty Salary - Prof. Abdul Karim',
            'type' => 'expense',
            'category' => 'Teacher Salary',
            'amount' => 45000.00,
            'payment_method' => 'Bank',
            'date' => now()->subDays(3)->toDateString(),
            'reference' => 'SAL-2026-09',
            'description' => 'September monthly remuneration disbursement',
        ]);

        Account::create([
            'title' => 'Coaching Campus Rent - Dhanmondi Branch',
            'type' => 'expense',
            'category' => 'Rent',
            'amount' => 30000.00,
            'payment_method' => 'Bank',
            'date' => now()->subDays(5)->toDateString(),
            'reference' => 'RNT-2026-09',
            'description' => 'Campus building floor rent for September',
        ]);

        Account::create([
            'title' => 'High-Speed Fiber Internet & Electricity',
            'type' => 'expense',
            'category' => 'Utility',
            'amount' => 8400.00,
            'payment_method' => 'bKash',
            'date' => now()->subDays(6)->toDateString(),
            'reference' => 'UTL-2026-09',
            'description' => 'DPDC electricity bill and ISP optical connection',
        ]);

        Account::create([
            'title' => 'Model Test Sheet Printing & Stationery',
            'type' => 'expense',
            'category' => 'Supplies',
            'amount' => 12500.00,
            'payment_method' => 'Cash',
            'date' => now()->subDays(8)->toDateString(),
            'reference' => 'SUP-2026-09',
            'description' => 'Printed 500 sets of SSC Model Test question papers',
        ]);

        // 11. Notices
        Notice::create([
            'title' => 'SSC Model Test Registration Deadline: Oct 5',
            'category' => 'urgent',
            'content' => 'All SSC Batch examinees must collect their admit cards from the admin office before October 3, 2026. Exam Hall opens at 08:30 AM.',
            'published_at' => now()->toDateString(),
            'target_audience' => 'students',
            'status' => 'published',
        ]);

        Notice::create([
            'title' => 'Monthly Fee Collection Reminder for October',
            'category' => 'fee',
            'content' => 'Respected guardians and students, please clear October monthly tuition fees by October 10 to avoid late fine.',
            'published_at' => now()->subDays(2)->toDateString(),
            'target_audience' => 'all',
            'status' => 'published',
        ]);

        Notice::create([
            'title' => 'Admissions Open for HSC 2027 Foundation Batch',
            'category' => 'general',
            'content' => 'New batches for Science and Commerce starting next week. Limited 40 seats per batch. Early bird 10% discount on first month.',
            'published_at' => now()->subDays(4)->toDateString(),
            'target_audience' => 'all',
            'status' => 'published',
        ]);

        Notice::create([
            'title' => 'Upcoming Holiday Notice: Durga Puja',
            'category' => 'holiday',
            'content' => 'The coaching center will remain closed from October 18 to October 22. Regular class schedule resumes on October 23.',
            'published_at' => now()->subDays(7)->toDateString(),
            'target_audience' => 'all',
            'status' => 'published',
        ]);

        // 12. SMS Logs
        SmsLog::create([
            'recipient_type' => 'All Students',
            'recipient' => 'Fatima Begum',
            'phone' => '01712-345678',
            'message' => 'Dear Student, your SSC Model Test starts on Oct 5. Please collect admit card.',
            'status' => 'Delivered',
        ]);

        SmsLog::create([
            'recipient_type' => 'SSC Batch A',
            'recipient' => 'Karim Hossain',
            'phone' => '01712-000001',
            'message' => 'Respected Guardian, Fatima attended class today on time. EduManage Pro.',
            'status' => 'Delivered',
        ]);

        // 13. Settings
        $settings = [
            'coaching_name' => 'EduManage Pro Coaching Center',
            'institute_tagline' => 'Smart Coaching Management for Modern Education',
            'email' => 'info@edumanagepro.com.bd',
            'phone' => '+880 1700-123456',
            'address' => 'House 12, Road 5, Dhanmondi, Dhaka-1205',
            'currency_symbol' => '৳',
            'currency_code' => 'BDT',
            'admission_status' => 'Open',
            'facebook_url' => 'https://facebook.com',
            'youtube_url' => 'https://youtube.com',
        ];

        foreach ($settings as $k => $v) {
            Setting::create(['key' => $k, 'value' => $v]);
        }

        // 14. Authentication Users (Admin, Teacher, Student, Parent)
        User::firstOrCreate(
            ['email' => 'admin@edumanage.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '01700112233',
                'avatar' => 'images/teacher_male_2_1790497121228.jpg',
                'status' => 'active',
            ]
        );

        $firstTeacher = Teacher::first();
        User::firstOrCreate(
            ['email' => 'teacher@edumanage.com'],
            [
                'name' => $firstTeacher?->name ?? 'Md. Rafiqul Islam',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone' => $firstTeacher?->phone ?? '01711223344',
                'teacher_id' => $firstTeacher?->id,
                'avatar' => $firstTeacher?->image ?? 'images/teacher_male_1_1790497096029.jpg',
                'status' => 'active',
            ]
        );

        $firstStudent = Student::first();
        User::firstOrCreate(
            ['email' => 'student@edumanage.com'],
            [
                'name' => $firstStudent?->name ?? 'Fatima Begum',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => $firstStudent?->phone ?? '01712345678',
                'student_id' => $firstStudent?->id,
                'avatar' => $firstStudent?->avatar ?? 'images/student_female_profile_1790497748277.jpg',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'parent@edumanage.com'],
            [
                'name' => 'Shahida Begum (Parent)',
                'password' => Hash::make('password'),
                'role' => 'parent',
                'phone' => '01811223355',
                'student_id' => $firstStudent?->id,
                'avatar' => 'images/teacher_female_1_1790497081063.jpg',
                'status' => 'active',
            ]
        );
    }
}
