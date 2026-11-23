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
        // 1. Create Subject
        $response = $this->actingAs($this->adminUser)->post(route('admin.subjects.store'), [
            'name' => 'Advanced Robotics & AI',
            'code' => 'ROBO-301',
            'description' => 'Applied robotics and coding curriculum',
            'status' => 'Active',
        ]);
        $response->assertRedirect(route('admin.subjects.index'));
        $response->assertSessionHas('success');

        $subject = Subject::where('code', 'ROBO-301')->first();
        $this->assertNotNull($subject);
        $this->assertEquals('Advanced Robotics & AI', $subject->name);

        // 2. Update Subject
        $up = $this->actingAs($this->adminUser)->put(route('admin.subjects.update', $subject->id), [
            'name' => 'Advanced Robotics & Automation',
            'code' => 'ROBO-301',
            'description' => 'Updated curriculum notes',
            'status' => 'Active',
        ]);
        $up->assertRedirect(route('admin.subjects.index'));
        $up->assertSessionHas('success');

        $subject->refresh();
        $this->assertEquals('Advanced Robotics & Automation', $subject->name);

        // 3. Bind with Exam
        $batch = Batch::first() ?? Batch::factory()->create();
        $examRes = $this->actingAs($this->adminUser)->post(route('admin.exams.store'), [
            'title' => 'Robotics Midterm Lab Exam',
            'exam_type' => 'Class Test',
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

        // 4. Delete Subject (exam's subject_id becomes null safely)
        $del = $this->actingAs($this->adminUser)->delete(route('admin.subjects.destroy', $subject->id));
        $del->assertRedirect(route('admin.subjects.index'));
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
}
