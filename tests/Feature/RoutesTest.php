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
}
