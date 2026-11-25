<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Notice;
use App\Models\AcademicClass;
use App\Models\CourseCategory;
use App\Models\Exam;
use App\Models\Batch;
use App\Models\ExamType;
use App\Models\Result;
use App\Models\Subject;
use App\Models\ResultItem;
use App\Models\Fee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RoutesTest extends TestCase
{
    protected User $adminUser;
    protected User $teacherUser;
    protected User $studentUser;
    protected User $parentUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
        $this->teacherUser = User::where('role', 'teacher')->first() ?? User::factory()->create(['role' => 'teacher']);
        $this->studentUser = User::where('role', 'student')->first() ?? User::factory()->create(['role' => 'student']);
        $this->parentUser = User::where('role', 'parent')->first() ?? User::factory()->create(['role' => 'parent']);
    }

    public function test_public_routes_return_ok(): void
    {
        $publicRoutes = [
            '/',
            '/about',
            '/courses',
            '/course-detail',
            '/teachers',
            '/results',
            '/notice',
            '/blog',
            '/contact',
            '/login',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_guest_is_redirected_to_login_from_protected_routes(): void
    {
        $protectedRoutes = [
            '/admin',
            '/admin/students',
            '/admin/accounts',
            '/teacher/dashboard',
            '/student/dashboard',
            '/parent/dashboard',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('login'));
        }
    }

    public function test_student_cannot_access_admin_panel(): void
    {
        $response = $this->actingAs($this->studentUser)->get('/admin');
        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_teacher_cannot_access_admin_panel(): void
    {
        $response = $this->actingAs($this->teacherUser)->get('/admin');
        $response->assertRedirect(route('teacher.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_authenticated_admin_can_access_all_admin_routes(): void
    {
        $adminRoutes = [
            '/admin',
            '/admin/students',
            '/admin/student-profile',
            '/admin/teachers',
            '/admin/courses',
            '/admin/batches',
            '/admin/attendance',
            '/admin/exams',
            '/admin/results',
            '/admin/routine',
            '/admin/fees',
            '/admin/invoice',
            '/admin/classes',
            '/admin/subjects',
            '/admin/accounts',
            '/admin/notices',
            '/admin/sms',
            '/admin/reports',
            '/admin/settings',
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($this->adminUser)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_teacher_can_access_teacher_portal(): void
    {
        $response = $this->actingAs($this->teacherUser)->get(route('teacher.dashboard'));
        $response->assertStatus(200);

        $routineResponse = $this->actingAs($this->teacherUser)->get(route('teacher.routine'));
        $routineResponse->assertStatus(200);

        $attendanceResponse = $this->actingAs($this->teacherUser)->get(route('teacher.attendance'));
        $attendanceResponse->assertStatus(200);
    }

    public function test_student_can_access_student_dashboard(): void
    {
        $response = $this->actingAs($this->studentUser)->get(route('student.dashboard'));
        $response->assertStatus(200);
    }

    public function test_parent_can_access_parent_dashboard(): void
    {
        $response = $this->actingAs($this->parentUser)->get(route('parent.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_login_success(): void
    {
        $response = $this->post(route('login.post'), [
            'login' => 'admin@edumanage.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->adminUser);
    }

    public function test_teacher_login_success(): void
    {
        $response = $this->post(route('login.post'), [
            'login' => 'teacher@edumanage.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertAuthenticatedAs($this->teacherUser);
    }

    public function test_student_login_with_student_id(): void
    {
        $student = Student::first();
        if ($student) {
            $response = $this->post(route('login.post'), [
                'login' => $student->student_id,
                'password' => 'password',
            ]);

            $response->assertRedirect(route('student.dashboard'));
            $this->assertAuthenticated();
        }
    }

    public function test_logout_functionality(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_accounts_crud_operations(): void
    {
        // 1. Create a transaction
        $storeResponse = $this->actingAs($this->adminUser)->post(route('admin.accounts.store'), [
            'type' => 'income',
            'title' => 'Test Admission Fee Collection',
            'category' => 'Admission',
            'amount' => 5000,
            'date' => '2026-11-23',
            'payment_method' => 'Bkash',
            'reference' => 'BK-TEST-12345',
            'description' => 'Test income transaction',
        ]);
        $storeResponse->assertRedirect(route('admin.accounts.index'));
        $storeResponse->assertSessionHas('success');

        $account = Account::where('reference', 'BK-TEST-12345')->first();
        $this->assertNotNull($account);
        $this->assertEquals(5000, $account->amount);

        // 2. Update the transaction
        $updateResponse = $this->actingAs($this->adminUser)->put(route('admin.accounts.update', $account->id), [
            'type' => 'income',
            'title' => 'Updated Admission Fee',
            'category' => 'Admission',
            'amount' => 5500,
            'date' => '2026-11-23',
            'payment_method' => 'Nagad',
            'reference' => 'BK-TEST-12345',
            'description' => 'Updated test description',
        ]);
        $updateResponse->assertRedirect(route('admin.accounts.index'));
        $updateResponse->assertSessionHas('success');

        $account->refresh();
        $this->assertEquals(5500, $account->amount);
        $this->assertEquals('Updated Admission Fee', $account->title);

        // 3. Delete the transaction
        $deleteResponse = $this->actingAs($this->adminUser)->delete(route('admin.accounts.destroy', $account->id));
        $deleteResponse->assertRedirect(route('admin.accounts.index'));
        $deleteResponse->assertSessionHas('success');

        $this->assertNull(Account::find($account->id));
    }

    public function test_courses_crud(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.courses.store'), [
            'title' => 'Test Engineering Math',
            'category' => 'Admission',
            'fee' => 4500,
            'duration' => '4 Months',
            'seats' => 50,
            'description' => 'Test course description',
            'status' => 'Active',
        ]);
        $response->assertRedirect(route('admin.courses'));

        $course = Course::where('title', 'Test Engineering Math')->first();
        $this->assertNotNull($course);

        // Update
        $update = $this->actingAs($this->adminUser)->put(route('admin.courses.update', $course->id), [
            'title' => 'Test Engineering Math Updated',
            'category' => 'Admission',
            'fee' => 4800,
            'duration' => '5 Months',
            'seats' => 60,
            'description' => 'Updated description',
            'status' => 'Active',
        ]);
        $update->assertRedirect(route('admin.courses'));

        $course->refresh();
        $this->assertEquals('Test Engineering Math Updated', $course->title);

        // Delete
        $delete = $this->actingAs($this->adminUser)->delete(route('admin.courses.destroy', $course->id));
        $delete->assertRedirect(route('admin.courses'));
        $this->assertNull(Course::find($course->id));
    }

    public function test_teachers_crud(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.teachers.store'), [
            'name' => 'Prof. Test Teacher',
            'email' => 'testteacher@edumanage.com',
            'phone' => '01700998877',
            'designation' => 'Senior Lecturer',
            'subject' => 'Higher Math',
            'qualification' => 'M.Sc. DU',
            'salary' => 35000,
            'join_date' => '2025-01-01',
            'status' => 'Active',
        ]);
        $response->assertRedirect(route('admin.teachers'));

        $teacher = Teacher::where('email', 'testteacher@edumanage.com')->first();
        $this->assertNotNull($teacher);

        // Update
        $up = $this->actingAs($this->adminUser)->put(route('admin.teachers.update', $teacher->id), [
            'name' => 'Prof. Test Teacher Updated',
            'email' => 'testteacher@edumanage.com',
            'phone' => '01700998877',
            'designation' => 'Lead Lecturer',
            'subject' => 'Physics',
            'qualification' => 'Ph.D. BUET',
            'salary' => 40000,
            'status' => 'Active',
        ]);
        $up->assertRedirect(route('admin.teachers'));

        $teacher->refresh();
        $this->assertEquals('Prof. Test Teacher Updated', $teacher->name);

        // Delete
        $del = $this->actingAs($this->adminUser)->delete(route('admin.teachers.destroy', $teacher->id));
        $del->assertRedirect(route('admin.teachers'));
        $this->assertNull(Teacher::find($teacher->id));
    }

    public function test_students_crud(): void
    {
        Storage::fake('public');
        $avatarFile = UploadedFile::fake()->image('student_photo.jpg', 200, 200);

        // 1. Create with avatar and optional empty fields
        $response = $this->actingAs($this->adminUser)->post(route('admin.students.store'), [
            'name' => 'Test Student Rahman',
            'email' => 'rahman_test@gmail.com',
            'phone' => '01811223344',
            'guardian_name' => 'Abdur Rahman',
            'guardian_phone' => '01811223355',
            'class_name' => 'Class X',
            'course_id' => '',
            'batch_id' => '',
            'admission_date' => '2026-01-10',
            'blood_group' => 'A+',
            'address' => 'Mirpur, Dhaka',
            'status' => 'Active',
            'avatar' => $avatarFile,
        ]);
        $response->assertRedirect(route('admin.students'));
        $response->assertSessionHas('success');

        $student = Student::where('email', 'rahman_test@gmail.com')->first();
        $this->assertNotNull($student);
        $this->assertStringStartsWith('STD-', $student->student_id);
        $this->assertStringStartsWith('storage/avatars/', $student->avatar);

        // 2. Update with new avatar
        $newAvatar = UploadedFile::fake()->image('new_student_photo.jpg', 200, 200);
        $up = $this->actingAs($this->adminUser)->put(route('admin.students.update', $student->id), [
            'name' => 'Test Student Rahman Updated',
            'email' => 'rahman_test@gmail.com',
            'phone' => '01811223344',
            'guardian_name' => 'Abdur Rahman Updated',
            'guardian_phone' => '01811223355',
            'class_name' => 'Class X',
            'course_id' => '',
            'batch_id' => '',
            'blood_group' => 'A+',
            'address' => 'Dhanmondi, Dhaka',
            'status' => 'Active',
            'avatar' => $newAvatar,
        ]);
        $up->assertRedirect(route('admin.students'));
        $up->assertSessionHas('success');

        $student->refresh();
        $this->assertEquals('Test Student Rahman Updated', $student->name);
        $this->assertStringStartsWith('storage/avatars/', $student->avatar);

        // 3. Delete
        $del = $this->actingAs($this->adminUser)->delete(route('admin.students.destroy', $student->id));
        $del->assertRedirect(route('admin.students'));
        $this->assertNull(Student::find($student->id));
    }

    public function test_notices_crud(): void
    {
        $res = $this->actingAs($this->adminUser)->post(route('admin.notices.store'), [
            'title' => 'Test Notice Title',
            'category' => 'Exam',
            'content' => 'This is a test notice description.',
            'target_audience' => 'All',
            'status' => 'published',
            'published_at' => now()->toDateString(),
        ]);
        $res->assertRedirect(route('admin.notices'));

        $notice = Notice::where('title', 'Test Notice Title')->first();
        $this->assertNotNull($notice);

        // Update
        $up = $this->actingAs($this->adminUser)->put(route('admin.notices.update', $notice->id), [
            'title' => 'Updated Test Notice Title',
            'category' => 'General',
            'content' => 'Updated description.',
            'target_audience' => 'Students',
            'status' => 'draft',
            'published_at' => now()->toDateString(),
        ]);
        $up->assertRedirect(route('admin.notices'));

        $notice->refresh();
        $this->assertEquals('Updated Test Notice Title', $notice->title);

        // Delete
        $del = $this->actingAs($this->adminUser)->delete(route('admin.notices.destroy', $notice->id));
        $del->assertRedirect(route('admin.notices'));
        $this->assertNull(Notice::find($notice->id));
    }

    public function test_classes_crud(): void
    {
        // 1. Create class
        $response = $this->actingAs($this->adminUser)->post(route('admin.classes.store'), [
            'name' => 'Class VIII (Junior)',
            'code' => 'C08',
            'numeric_level' => 8,
            'description' => 'Junior school scholarship batch',
            'status' => 'Active',
        ]);
        $response->assertRedirect(route('admin.classes.index'));
        $response->assertSessionHas('success');

        $class = AcademicClass::where('name', 'Class VIII (Junior)')->first();
        $this->assertNotNull($class);
        $this->assertEquals('C08', $class->code);

        // 2. Update class
        $up = $this->actingAs($this->adminUser)->put(route('admin.classes.update', $class->id), [
            'name' => 'Class VIII (Junior Updated)',
            'code' => 'C08-UPD',
            'numeric_level' => 8,
            'description' => 'Updated description',
            'status' => 'Active',
        ]);
        $up->assertRedirect(route('admin.classes.index'));
        $up->assertSessionHas('success');

        $class->refresh();
        $this->assertEquals('Class VIII (Junior Updated)', $class->name);
        $this->assertEquals('C08-UPD', $class->code);

        // 3. Delete class
        $del = $this->actingAs($this->adminUser)->delete(route('admin.classes.destroy', $class->id));
        $del->assertRedirect(route('admin.classes.index'));
        $del->assertSessionHas('success');

        $this->assertNull(AcademicClass::find($class->id));
    }

    public function test_course_categories_crud(): void
    {
        // 1. Create Course Category
        $response = $this->actingAs($this->adminUser)->post(route('admin.course.categories.store'), [
            'name' => 'Olympiad Preparation',
            'description' => 'Math and Science Olympiad training track',
        ]);
        $response->assertRedirect(route('admin.courses'));
        $response->assertSessionHas('success');

        $cat = CourseCategory::where('name', 'Olympiad Preparation')->first();
        $this->assertNotNull($cat);
        $this->assertEquals('olympiad-preparation', $cat->slug);

        // 2. Update Course Category
        $up = $this->actingAs($this->adminUser)->put(route('admin.course.categories.update', $cat->id), [
            'name' => 'National Olympiad Preparation',
            'status' => 'Active',
            'description' => 'Updated Olympiad coaching details',
        ]);
        $up->assertRedirect(route('admin.courses'));
        $up->assertSessionHas('success');

        $cat->refresh();
        $this->assertEquals('National Olympiad Preparation', $cat->name);

        // 3. Create a course with this dynamic category
        $courseRes = $this->actingAs($this->adminUser)->post(route('admin.courses.store'), [
            'title' => 'Math Olympiad Special 2026',
            'category' => 'National Olympiad Preparation',
            'duration' => '6 Months',
            'fee' => 3000,
            'seats' => 40,
            'status' => 'Active',
            'description' => 'Specialized math problem solving',
        ]);
        $courseRes->assertRedirect(route('admin.courses'));
        $course = Course::where('title', 'Math Olympiad Special 2026')->first();
        $this->assertNotNull($course);
        $this->assertEquals($cat->id, $course->category_id);
        $this->assertEquals('National Olympiad Preparation', $course->category);

        // 4. Delete Course Category (should unassign category_id on course safely)
        $del = $this->actingAs($this->adminUser)->delete(route('admin.course.categories.destroy', $cat->id));
        $del->assertRedirect(route('admin.courses'));
        $del->assertSessionHas('success');

        $this->assertNull(CourseCategory::find($cat->id));
        $course->refresh();
        $this->assertNull($course->category_id);

        // Clean up course
        $course->delete();
    }

    public function test_exam_types_crud(): void
    {
        $batch = Batch::first() ?? Batch::factory()->create();

        // 1. Create Exam Type
        $response = $this->actingAs($this->adminUser)->post(route('admin.exam.types.store'), [
            'name' => 'Speed Math Quiz',
            'description' => 'Fast arithmetic and logic test',
        ]);
        $response->assertRedirect(route('admin.exams'));
        $response->assertSessionHas('success');

        $type = ExamType::where('name', 'Speed Math Quiz')->first();
        $this->assertNotNull($type);
        $this->assertEquals('speed-math-quiz', $type->slug);

        // 2. Update Exam Type
        $up = $this->actingAs($this->adminUser)->put(route('admin.exam.types.update', $type->id), [
            'name' => 'Speed & Mental Math Quiz',
            'status' => 'Active',
            'description' => 'Updated mental calculation test',
        ]);
        $up->assertRedirect(route('admin.exams'));
        $up->assertSessionHas('success');

        $type->refresh();
        $this->assertEquals('Speed & Mental Math Quiz', $type->name);

        // 3. Schedule an Exam using this dynamic Exam Type
        $examRes = $this->actingAs($this->adminUser)->post(route('admin.exams.store'), [
            'title' => 'Weekly Mental Math Challenge #1',
            'exam_type' => 'Speed & Mental Math Quiz',
            'batch_id' => $batch->id,
            'subject' => 'Mathematics',
            'date' => now()->addDays(5)->toDateString(),
            'time' => '10:00 AM - 11:00 AM',
            'total_marks' => 50,
            'pass_marks' => 20,
            'room' => 'Hall C',
            'status' => 'Upcoming',
        ]);
        $examRes->assertRedirect(route('admin.exams'));
        $exam = Exam::where('title', 'Weekly Mental Math Challenge #1')->first();
        $this->assertNotNull($exam);
        $this->assertEquals($type->id, $exam->exam_type_id);
        $this->assertEquals('Speed & Mental Math Quiz', $exam->exam_type);

        // 4. Delete Exam Type (should unassign exam_type_id on exam safely)
        $del = $this->actingAs($this->adminUser)->delete(route('admin.exam.types.destroy', $type->id));
        $del->assertRedirect(route('admin.exams'));
        $del->assertSessionHas('success');

        $this->assertNull(ExamType::find($type->id));
        $exam->refresh();
        $this->assertNull($exam->exam_type_id);

        // Clean up exam
        $exam->delete();
    }

    public function test_results_crud_and_student_live_search(): void
    {
        $student = Student::first() ?? Student::factory()->create();
        $exam = Exam::first() ?? Exam::factory()->create();

        // 1. Test live search endpoint
        $searchRes = $this->actingAs($this->adminUser)->getJson(route('admin.results.search-students', [
            'q' => substr($student->name, 0, 3)
        ]));
        $searchRes->assertStatus(200);
        $searchRes->assertJsonFragment(['id' => $student->id, 'name' => $student->name]);

        // 2. Store Result
        $storeRes = $this->actingAs($this->adminUser)->post(route('admin.results.store'), [
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'obtained_marks' => 88,
            'total_marks' => 100,
            'remarks' => 'Outstanding analytical skills',
        ]);
        $storeRes->assertRedirect(route('admin.results'));
        $storeRes->assertSessionHas('success');

        $result = Result::where('exam_id', $exam->id)->where('student_id', $student->id)->first();
        $this->assertNotNull($result);
        $this->assertEquals(88, $result->obtained_marks);
        $this->assertEquals(5.00, $result->gpa);
        $this->assertEquals('A+', $result->grade);

        // 3. Update Result
        $updateRes = $this->actingAs($this->adminUser)->put(route('admin.results.update', $result->id), [
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'obtained_marks' => 75,
            'total_marks' => 100,
            'remarks' => 'Good performance',
        ]);
        $updateRes->assertRedirect(route('admin.results'));
        $updateRes->assertSessionHas('success');

        $result->refresh();
        $this->assertEquals(75, $result->obtained_marks);
        $this->assertEquals(4.00, $result->gpa);
        $this->assertEquals('A', $result->grade);

        // 4. Destroy Result
        $deleteRes = $this->actingAs($this->adminUser)->delete(route('admin.results.destroy', $result->id));
        $deleteRes->assertRedirect(route('admin.results'));
        $deleteRes->assertSessionHas('success');

        $this->assertNull(Result::find($result->id));
    }

    public function test_subjects_crud_and_exam_binding(): void
    {
        $class = AcademicClass::first() ?? AcademicClass::factory()->create();

        // 1. Create Subject for specific class
        $response = $this->actingAs($this->adminUser)->post(route('admin.subjects.store'), [
            'name' => 'Advanced Robotics & AI',
            'code' => 'ROBO-301',
            'class_id' => $class->id,
            'description' => 'Applied robotics and coding curriculum',
            'status' => 'Active',
        ]);
        $response->assertRedirect(route('admin.subjects.index', ['class_id' => $class->id]));
        $response->assertSessionHas('success');

        $subject = Subject::where('code', 'ROBO-301')->first();
        $this->assertNotNull($subject);
        $this->assertEquals('Advanced Robotics & AI', $subject->name);
        $this->assertEquals($class->id, $subject->class_id);

        // Filter by class_id
        $classFilterRes = $this->actingAs($this->adminUser)->get(route('admin.subjects.index', ['class_id' => $class->id]));
        $classFilterRes->assertStatus(200);
        $classFilterRes->assertSee('Advanced Robotics & AI');

        // 2. Update Subject
        $up = $this->actingAs($this->adminUser)->put(route('admin.subjects.update', $subject->id), [
            'name' => 'Advanced Robotics & Automation',
            'code' => 'ROBO-301',
            'class_id' => $class->id,
            'description' => 'Updated curriculum notes',
            'status' => 'Active',
        ]);
        $up->assertRedirect(route('admin.subjects.index', ['class_id' => $class->id]));
        $up->assertSessionHas('success');

        $subject->refresh();
        $this->assertEquals('Advanced Robotics & Automation', $subject->name);

        // 3. Bind with Exam
        $batch = Batch::first() ?? Batch::factory()->create();
        $examRes = $this->actingAs($this->adminUser)->post(route('admin.exams.store'), [
            'title' => 'Robotics Midterm Lab Exam',
            'exam_type' => 'Class Test',
            'class_id' => $class->id,
            'batch_id' => $batch->id,
            'subject' => 'Advanced Robotics & Automation',
            'date' => now()->addDays(7)->toDateString(),
            'time' => '10:00 AM - 12:00 PM',
            'total_marks' => 50,
            'pass_marks' => 20,
            'room' => 'Lab 1',
            'status' => 'Upcoming',
        ]);
        $examRes->assertRedirect(route('admin.exams'));

        $exam = Exam::where('title', 'Robotics Midterm Lab Exam')->first();
        $this->assertNotNull($exam);
        $this->assertEquals($subject->id, $exam->subject_id);
        $this->assertEquals('Advanced Robotics & Automation', $exam->subject);
        $this->assertEquals($class->id, $exam->class_id);

        // Filter exams by class_id
        $classFilterExam = $this->actingAs($this->adminUser)->get(route('admin.exams', ['class_id' => $class->id]));
        $classFilterExam->assertStatus(200);
        $classFilterExam->assertSee('Robotics Midterm Lab Exam');

        // 4. Delete Subject (exam's subject_id becomes null safely)
        $del = $this->actingAs($this->adminUser)->delete(route('admin.subjects.destroy', $subject->id));
        $del->assertRedirect(route('admin.subjects.index', ['class_id' => $class->id]));
        $del->assertSessionHas('success');

        $this->assertNull(Subject::find($subject->id));
        $exam->refresh();
        $this->assertNull($exam->subject_id);

        $exam->delete();
    }

    public function test_subject_wise_marks_and_marksheet_download(): void
    {
        $student = Student::first() ?? Student::factory()->create();
        $exam = Exam::first() ?? Exam::factory()->create();

        // 1. Store result with multiple subjects
        $response = $this->actingAs($this->adminUser)->post(route('admin.results.store'), [
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'remarks' => 'Good performance in Science subjects',
            'subjects' => [
                ['subject_name' => 'Physics', 'obtained_marks' => 85, 'total_marks' => 100],
                ['subject_name' => 'Chemistry', 'obtained_marks' => 75, 'total_marks' => 100],
                ['subject_name' => 'Higher Mathematics', 'obtained_marks' => 90, 'total_marks' => 100],
            ],
        ]);
        $response->assertRedirect(route('admin.results'));
        $response->assertSessionHas('success');

        $result = Result::where('exam_id', $exam->id)->where('student_id', $student->id)->first();
        $this->assertNotNull($result);
        // Total obtained: 85 + 75 + 90 = 250
        $this->assertEquals(250, $result->obtained_marks);
        // Total marks: 100 + 100 + 100 = 300
        $this->assertEquals(300, $result->total_marks);
        // Subject GPAs: Physics (5.0), Chemistry (4.0), Math (5.0) -> Average = (5+4+5)/3 = 4.67
        $this->assertEquals(4.67, $result->gpa);
        $this->assertEquals('A', $result->grade);

        // Check Result Items
        $this->assertCount(3, $result->items);

        // 2. Access Marksheet view for download/print
        $marksheetRes = $this->actingAs($this->adminUser)->get(route('admin.results.marksheet', $result->id));
        $marksheetRes->assertStatus(200);
        $marksheetRes->assertSee('Physics');
        $marksheetRes->assertSee('Chemistry');
        $marksheetRes->assertSee('Higher Mathematics');
        $marksheetRes->assertSee('250.0');
        $marksheetRes->assertSee('4.67');

        // Clean up
        $result->items()->delete();
        $result->delete();
    }

    public function test_academic_and_target_batch_dual_program_structure(): void
    {
        // Clean up any leftovers
        Student::whereIn('name', ['Tanzim Academic Student', 'Fahim Target Student'])->delete();
        Subject::whereIn('code', ['PHY-B9', 'ENG-MATH-T'])->delete();
        Batch::whereIn('batch_code', ['ACH-T9', 'BUET-T26'])->delete();
        AcademicClass::whereIn('name', ['Class IX Test Board', 'Engineering Target Test Batch'])->delete();

        // 1. Classes: Academic Class & Target Batch Class
        $academicClass = AcademicClass::create([
            'name' => 'Class IX Test Board',
            'code' => 'T-C09',
            'class_type' => 'Academic',
            'numeric_level' => 9,
            'status' => 'Active',
        ]);
        $targetClass = AcademicClass::create([
            'name' => 'Engineering Target Test Batch',
            'code' => 'T-ENG',
            'class_type' => 'Target',
            'status' => 'Active',
        ]);

        $this->assertEquals('Academic', $academicClass->class_type);
        $this->assertEquals('Target', $targetClass->class_type);

        // Filter classes by type
        $academicClassRes = $this->actingAs($this->adminUser)->get(route('admin.classes.index', ['class_type' => 'Academic']));
        $academicClassRes->assertStatus(200);
        $academicClassRes->assertSee('Class IX Test Board');

        $targetClassRes = $this->actingAs($this->adminUser)->get(route('admin.classes.index', ['class_type' => 'Target']));
        $targetClassRes->assertStatus(200);
        $targetClassRes->assertSee('Engineering Target Test Batch');

        // 2. Batches
        $academicBatch = Batch::create([
            'name' => 'Morning Achievers Test',
            'batch_code' => 'ACH-T9',
            'class_id' => $academicClass->id,
            'class_name' => $academicClass->name,
            'program_type' => 'Academic',
            'days' => 'Sat, Mon, Wed',
            'time_slot' => '08:00 AM - 10:00 AM',
            'room' => 'Room 101',
            'max_students' => 30,
            'status' => 'Active',
        ]);
        $targetBatch = Batch::create([
            'name' => 'BUET Titans Test 2026',
            'batch_code' => 'BUET-T26',
            'class_id' => $targetClass->id,
            'class_name' => $targetClass->name,
            'program_type' => 'Target',
            'days' => 'Sun, Tue, Thu',
            'time_slot' => '03:00 PM - 06:00 PM',
            'room' => 'Hall Target',
            'max_students' => 50,
            'status' => 'Active',
        ]);

        // 3. Students: Academic Student vs Target Batch Student
        $admitAcademic = $this->actingAs($this->adminUser)->post(route('admin.students.store'), [
            'name' => 'Tanzim Academic Student',
            'phone' => '01711-100201',
            'class_name' => $academicClass->name,
            'batch_id' => $academicBatch->id,
            'program_type' => 'Academic',
            'status' => 'Active',
        ]);
        $admitAcademic->assertRedirect(route('admin.students'));

        $admitTarget = $this->actingAs($this->adminUser)->post(route('admin.students.store'), [
            'name' => 'Fahim Target Student',
            'phone' => '01811-100202',
            'class_name' => $targetClass->name,
            'batch_id' => $targetBatch->id,
            'program_type' => 'Target',
            'status' => 'Active',
        ]);
        $admitTarget->assertRedirect(route('admin.students'));

        $academicStudent = Student::where('name', 'Tanzim Academic Student')->first();
        $targetStudent = Student::where('name', 'Fahim Target Student')->first();
        $this->assertNotNull($academicStudent);
        $this->assertNotNull($targetStudent);
        $this->assertEquals('Academic', $academicStudent->program_type);
        $this->assertEquals('Target', $targetStudent->program_type);

        // Verify Student filter tabs
        $academicFilter = $this->actingAs($this->adminUser)->get(route('admin.students', ['program_type' => 'Academic']));
        $academicFilter->assertStatus(200);
        $academicFilter->assertSee('Tanzim Academic Student');

        $targetFilter = $this->actingAs($this->adminUser)->get(route('admin.students', ['program_type' => 'Target']));
        $targetFilter->assertStatus(200);
        $targetFilter->assertSee('Fahim Target Student');

        // 4. Subjects: Academic Subject vs Target Subject
        $academicSubject = Subject::create([
            'name' => 'Board Physics Test',
            'code' => 'PHY-B9',
            'class_id' => $academicClass->id,
            'program_type' => 'Academic',
            'status' => 'Active',
        ]);
        $targetSubject = Subject::create([
            'name' => 'Advanced Mechanics & Calculus',
            'code' => 'ENG-MATH-T',
            'class_id' => $targetClass->id,
            'program_type' => 'Target',
            'status' => 'Active',
        ]);

        $subFilterAcademic = $this->actingAs($this->adminUser)->get(route('admin.subjects.index', ['program_type' => 'Academic', 'search' => 'Board Physics Test']));
        $subFilterAcademic->assertStatus(200);
        $subFilterAcademic->assertSee('Board Physics Test');

        $subFilterTarget = $this->actingAs($this->adminUser)->get(route('admin.subjects.index', ['program_type' => 'Target', 'search' => 'Advanced Mechanics & Calculus']));
        $subFilterTarget->assertStatus(200);
        $subFilterTarget->assertSee('Advanced Mechanics & Calculus');

        // 5. Exams: Academic Exam vs Target Exam
        $academicExam = Exam::create([
            'title' => 'SSC Board Physics Midterm Test',
            'exam_type' => 'Monthly Exam',
            'program_type' => 'Academic',
            'class_id' => $academicClass->id,
            'batch_id' => $academicBatch->id,
            'subject' => $academicSubject->name,
            'subject_id' => $academicSubject->id,
            'date' => now()->addDays(3)->toDateString(),
            'time' => '10:00 AM - 01:00 PM',
            'total_marks' => 100,
            'pass_marks' => 40,
            'status' => 'Upcoming',
        ]);

        $targetExam = Exam::create([
            'title' => 'BUET Admission Advance Model Test 1',
            'exam_type' => 'Admission Model Test',
            'program_type' => 'Target',
            'class_id' => $targetClass->id,
            'batch_id' => $targetBatch->id,
            'subject' => $targetSubject->name,
            'subject_id' => $targetSubject->id,
            'date' => now()->addDays(5)->toDateString(),
            'time' => '02:00 PM - 05:00 PM',
            'total_marks' => 200,
            'pass_marks' => 80,
            'status' => 'Upcoming',
        ]);

        $this->assertEquals('Academic', $academicExam->program_type);
        $this->assertEquals('Target', $targetExam->program_type);

        $examFilterAcademic = $this->actingAs($this->adminUser)->get(route('admin.exams', ['program_type' => 'Academic']));
        $examFilterAcademic->assertStatus(200);
        $examFilterAcademic->assertSee('SSC Board Physics Midterm Test');

        $examFilterTarget = $this->actingAs($this->adminUser)->get(route('admin.exams', ['program_type' => 'Target']));
        $examFilterTarget->assertStatus(200);
        $examFilterTarget->assertSee('BUET Admission Advance Model Test 1');

        // 6. Results: Academic Result (with GPA) vs Target Result (with Merit Rank)
        $academicResult = Result::create([
            'exam_id' => $academicExam->id,
            'student_id' => $academicStudent->id,
            'program_type' => 'Academic',
            'obtained_marks' => 88,
            'total_marks' => 100,
            'gpa' => 5.00,
            'grade' => 'A+',
            'remarks' => 'Outstanding academic board preparation',
        ]);
        $academicResult->items()->create([
            'subject_id' => $academicSubject->id,
            'subject_name' => $academicSubject->name,
            'obtained_marks' => 88,
            'total_marks' => 100,
            'gpa' => 5.00,
            'grade' => 'A+',
        ]);

        $targetResult = Result::create([
            'exam_id' => $targetExam->id,
            'student_id' => $targetStudent->id,
            'program_type' => 'Target',
            'merit_position' => 1,
            'obtained_marks' => 176,
            'total_marks' => 200,
            'gpa' => 5.00,
            'grade' => 'A+',
            'remarks' => 'Top merit candidate for BUET Target batch',
        ]);
        $targetResult->items()->create([
            'subject_id' => $targetSubject->id,
            'subject_name' => $targetSubject->name,
            'obtained_marks' => 176,
            'total_marks' => 200,
            'gpa' => 5.00,
            'grade' => 'A+',
        ]);

        $this->assertEquals(1, $targetResult->merit_position);
        $this->assertEquals('Target', $targetResult->program_type);

        // 7. Results index verification
        $resFilterAcademic = $this->actingAs($this->adminUser)->get(route('admin.results', ['program_type' => 'Academic']));
        $resFilterAcademic->assertStatus(200);
        $resFilterAcademic->assertSee('Tanzim Academic Student');
        $resFilterAcademic->assertSee('GPA 5.00');

        $resFilterTarget = $this->actingAs($this->adminUser)->get(route('admin.results', ['program_type' => 'Target']));
        $resFilterTarget->assertStatus(200);
        $resFilterTarget->assertSee('Fahim Target Student');
        $resFilterTarget->assertSee('Merit: #1');

        // 8. Marksheet Rendering: Academic Marksheet vs Target Scorecard
        $academicMarksheet = $this->actingAs($this->adminUser)->get(route('admin.results.marksheet', $academicResult->id));
        $academicMarksheet->assertStatus(200);
        $academicMarksheet->assertSee('Student Academic Examination Marksheet');
        $academicMarksheet->assertSee('Official Transcript');
        $academicMarksheet->assertSee('GPA');

        $targetMarksheet = $this->actingAs($this->adminUser)->get(route('admin.results.marksheet', $targetResult->id));
        $targetMarksheet->assertStatus(200);
        $targetMarksheet->assertSee('Target Batch Performance &amp; Merit Position Certificate', false);
        $targetMarksheet->assertSee('Target Scorecard');
        $targetMarksheet->assertSee('Merit Position');
        $targetMarksheet->assertSee('Rank #1');
        $targetMarksheet->assertSee('QUALIFIED');

        // Clean up
        $academicResult->items()->delete();
        $academicResult->delete();
        $targetResult->items()->delete();
        $targetResult->delete();
        $academicExam->delete();
        $targetExam->delete();
        $academicSubject->delete();
        $targetSubject->delete();
        $academicStudent->delete();
        $targetStudent->delete();
        $academicBatch->delete();
        $targetBatch->delete();
        $academicClass->delete();
        $targetClass->delete();
    }

    public function test_student_creation_with_login_and_installment_fees_and_collection(): void
    {
        // 1. Create Academic Class for testing
        $testClass = AcademicClass::create([
            'name' => 'Class XI Science Fees Test',
            'numeric_level' => 11,
            'class_type' => 'Academic',
            'status' => 'Active',
        ]);

        // Clean up previous test users/students if any
        User::where('email', 'sumaiya.login@edumanage.com')->delete();
        Student::where('email', 'sumaiya.login@edumanage.com')->delete();
        User::where('email', 'hasan.inst@edumanage.com')->delete();
        Student::where('email', 'hasan.inst@edumanage.com')->delete();

        // 2. Student Creation with Login Credentials and One-time Fee
        $respOneTime = $this->actingAs($this->adminUser)->post(route('admin.students.store'), [
            'name' => 'Sumaiya Rahman',
            'email' => 'sumaiya.login@edumanage.com',
            'password' => 'Sumaiya@2026',
            'phone' => '01711223388',
            'guardian_name' => 'Rahman Miah',
            'guardian_phone' => '01711223399',
            'program_type' => 'Academic',
            'class_name' => 'Class XI Science Fees Test',
            'status' => 'Active',
            'setup_fees' => '1',
            'fee_type' => 'onetime',
            'fee_amount' => 5000,
            'fee_discount' => 500,
            'fee_paid_amount' => 2000,
            'fee_payment_method' => 'bKash',
            'fee_payment_date' => '2026-11-25',
            'fee_notes' => 'Admission initial pay',
        ]);
        $respOneTime->assertRedirect(route('admin.students'));
        $respOneTime->assertSessionHas('success');

        // Verify Student & User Login account
        $sumaiya = Student::where('email', 'sumaiya.login@edumanage.com')->first();
        $this->assertNotNull($sumaiya);
        $sumaiyaUser = User::where('email', 'sumaiya.login@edumanage.com')->first();
        $this->assertNotNull($sumaiyaUser);
        $this->assertEquals('student', $sumaiyaUser->role);
        $this->assertTrue(Hash::check('Sumaiya@2026', $sumaiyaUser->password));

        // Test login with created student credentials
        $loginResp = $this->post(route('login.post'), [
            'login' => 'sumaiya.login@edumanage.com',
            'password' => 'Sumaiya@2026',
        ]);
        $loginResp->assertRedirect(route('student.dashboard'));

        // Test login with Student ID as well
        $loginIdResp = $this->post(route('login.post'), [
            'login' => $sumaiya->student_id,
            'password' => 'Sumaiya@2026',
        ]);
        $loginIdResp->assertRedirect(route('student.dashboard'));

        // Verify One-time Fee record
        $oneTimeFee = Fee::where('student_id', $sumaiya->id)->first();
        $this->assertNotNull($oneTimeFee);
        $this->assertEquals('onetime', $oneTimeFee->fee_type);
        $this->assertEquals(5000, $oneTimeFee->amount);
        $this->assertEquals(500, $oneTimeFee->discount);
        $this->assertEquals(2000, $oneTimeFee->paid_amount);
        $this->assertEquals(2500, $oneTimeFee->due_amount);
        $this->assertEquals('Partial', $oneTimeFee->status);

        // Verify Accounts income entry
        $accIncome1 = Account::where('reference', $oneTimeFee->invoice_no)->first();
        $this->assertNotNull($accIncome1);
        $this->assertEquals(2000, $accIncome1->amount);
        $this->assertEquals('income', $accIncome1->type);

        // 3. Student Creation with Installment Fee Plan
        $respInstallment = $this->actingAs($this->adminUser)->post(route('admin.students.store'), [
            'name' => 'Hasan Mahmud',
            'email' => 'hasan.inst@edumanage.com',
            'password' => 'Hasan@2026',
            'phone' => '01799887766',
            'guardian_name' => 'Mahmud Ali',
            'guardian_phone' => '01799887755',
            'program_type' => 'Academic',
            'class_name' => 'Class XI Science Fees Test',
            'status' => 'Active',
            'setup_fees' => '1',
            'fee_type' => 'installment',
            'total_installments' => 3,
            'installment_interval' => 'monthly',
            'fee_amount' => 6000,
            'fee_discount' => 0,
            'fee_paid_amount' => 2000,
            'fee_payment_method' => 'Cash',
            'fee_payment_date' => '2026-11-25',
            'fee_notes' => 'Admission installment setup',
        ]);
        $respInstallment->assertRedirect(route('admin.students'));

        $hasan = Student::where('email', 'hasan.inst@edumanage.com')->first();
        $this->assertNotNull($hasan);

        // Verify 3 Installments created
        $installments = Fee::where('student_id', $hasan->id)->orderBy('installment_no')->get();
        $this->assertCount(3, $installments);

        // Installment 1: fully paid by down payment
        $inst1 = $installments[0];
        $this->assertEquals(1, $inst1->installment_no);
        $this->assertEquals(3, $inst1->total_installments);
        $this->assertEquals(2000, $inst1->amount);
        $this->assertEquals(2000, $inst1->paid_amount);
        $this->assertEquals(0, $inst1->due_amount);
        $this->assertEquals('Paid', $inst1->status);

        // Installment 2: Due
        $inst2 = $installments[1];
        $this->assertEquals(2, $inst2->installment_no);
        $this->assertEquals(2000, $inst2->amount);
        $this->assertEquals(0, $inst2->paid_amount);
        $this->assertEquals(2000, $inst2->due_amount);
        $this->assertEquals('Due', $inst2->status);

        // Installment 3: Due
        $inst3 = $installments[2];
        $this->assertEquals(3, $inst3->installment_no);
        $this->assertEquals(2000, $inst3->amount);
        $this->assertEquals(0, $inst3->paid_amount);
        $this->assertEquals(2000, $inst3->due_amount);
        $this->assertEquals('Due', $inst3->status);

        // 4. Fee Collection Flow against Installment 2 ("sei onujai fees collect korte parbe")
        // Partial collection: 1200 of 2000
        $payResp = $this->actingAs($this->adminUser)->post(route('admin.fees.pay', $inst2->id), [
            'paying_amount' => 1200,
            'payment_method' => 'Nagad',
            'payment_date' => '2026-11-26',
            'notes' => 'Partial payment for Inst 2',
        ]);
        $payResp->assertRedirect(route('admin.fees'));
        $payResp->assertSessionHas('success');

        $inst2->refresh();
        $this->assertEquals(1200, $inst2->paid_amount);
        $this->assertEquals(800, $inst2->due_amount);
        $this->assertEquals('Partial', $inst2->status);

        // Verify Accounts income logged
        $accIncome2 = Account::where('reference', $inst2->invoice_no)->first();
        $this->assertNotNull($accIncome2);
        $this->assertEquals(1200, $accIncome2->amount);

        // Complete remaining due of 800
        $payResp2 = $this->actingAs($this->adminUser)->post(route('admin.fees.pay', $inst2->id), [
            'paying_amount' => 800,
            'payment_method' => 'Cash',
            'payment_date' => '2026-11-27',
            'notes' => 'Remaining due clear for Inst 2',
        ]);
        $payResp2->assertRedirect(route('admin.fees'));
        $payResp2->assertSessionHas('success');

        $inst2->refresh();
        $this->assertEquals(2000, $inst2->paid_amount);
        $this->assertEquals(0, $inst2->due_amount);
        $this->assertEquals('Paid', $inst2->status);

        // 5. Student Creation with UNLIMITED / ONGOING Installment Plan
        User::where('email', 'unlimited.student@edumanage.com')->delete();
        Student::where('email', 'unlimited.student@edumanage.com')->delete();

        $respUnlimited = $this->actingAs($this->adminUser)->post(route('admin.students.store'), [
            'name' => 'Mehedi Hasan Unlimited',
            'email' => 'unlimited.student@edumanage.com',
            'password' => 'Mehedi@2026',
            'phone' => '01733445566',
            'class_name' => 'Class XI Science Fees Test',
            'status' => 'Active',
            'setup_fees' => '1',
            'fee_type' => 'installment',
            'installment_mode' => 'unlimited',
            'fee_amount' => 2500,
            'fee_discount' => 0,
            'fee_paid_amount' => 1500,
            'fee_payment_method' => 'bKash',
            'fee_payment_date' => '2026-11-25',
            'fee_notes' => 'Admission under monthly ongoing plan',
        ]);
        $respUnlimited->assertRedirect(route('admin.students'));

        $mehedi = Student::where('email', 'unlimited.student@edumanage.com')->first();
        $this->assertNotNull($mehedi);

        $unlimitedFee = Fee::where('student_id', $mehedi->id)->first();
        $this->assertNotNull($unlimitedFee);
        $this->assertEquals('installment', $unlimitedFee->fee_type);
        $this->assertEquals(1, $unlimitedFee->installment_no);
        $this->assertNull($unlimitedFee->total_installments); // null represents unlimited
        $this->assertEquals(2500, $unlimitedFee->amount);
        $this->assertEquals(1500, $unlimitedFee->paid_amount);
        $this->assertEquals(1000, $unlimitedFee->due_amount);
        $this->assertEquals('Partial', $unlimitedFee->status);

        // Check view rendering in fees table
        $feesPage = $this->actingAs($this->adminUser)->get(route('admin.fees'));
        $feesPage->assertStatus(200);
        $feesPage->assertSee('Inst. 1 (Unlimited)');
        $feesPage->assertSee('feeStudentSearchInput');

        // 6. Student Creation with CUSTOM HIGH installment count (e.g. 15 installments)
        User::where('email', 'custom.inst@edumanage.com')->delete();
        Student::where('email', 'custom.inst@edumanage.com')->delete();

        $respCustomCount = $this->actingAs($this->adminUser)->post(route('admin.students.store'), [
            'name' => 'Custom Count Student',
            'email' => 'custom.inst@edumanage.com',
            'password' => 'Custom@2026',
            'phone' => '01755667788',
            'class_name' => 'Class XI Science Fees Test',
            'status' => 'Active',
            'setup_fees' => '1',
            'fee_type' => 'installment',
            'installment_mode' => 'fixed',
            'total_installments' => 15,
            'fee_amount' => 15000,
            'fee_discount' => 0,
            'fee_paid_amount' => 1000,
            'fee_payment_method' => 'Cash',
            'fee_payment_date' => '2026-11-25',
        ]);
        $respCustomCount->assertRedirect(route('admin.students'));

        $customStudent = Student::where('email', 'custom.inst@edumanage.com')->first();
        $this->assertNotNull($customStudent);
        $customInsts = Fee::where('student_id', $customStudent->id)->get();
        $this->assertCount(15, $customInsts);
        $this->assertEquals(15, $customInsts->first()->total_installments);

        // Clean up test data
        Fee::where('student_id', $sumaiya->id)->delete();
        Fee::where('student_id', $hasan->id)->delete();
        Fee::where('student_id', $mehedi->id)->delete();
        Fee::where('student_id', $customStudent->id)->delete();
        Account::whereIn('reference', [$oneTimeFee->invoice_no, $inst1->invoice_no, $inst2->invoice_no, $inst3->invoice_no, $unlimitedFee->invoice_no])->delete();
        $hasanUserId = User::where('student_id', $hasan->id)->value('id');
        $mehediUserId = User::where('student_id', $mehedi->id)->value('id');
        $customUserId = User::where('student_id', $customStudent->id)->value('id');
        User::whereIn('id', array_filter([$sumaiyaUser->id, $hasanUserId, $mehediUserId, $customUserId]))->delete();
        $sumaiya->delete();
        $hasan->delete();
        $mehedi->delete();
        $customStudent->delete();
        $testClass->delete();
    }
}
