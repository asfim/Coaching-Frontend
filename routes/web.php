<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Teacher\TeacherPortalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\RoutineController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ClassController;

/*
|--------------------------------------------------------------------------
| Web Routes - EduManage Pro Coaching Management System
|--------------------------------------------------------------------------
*/

// Public Website Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/courses', [PublicController::class, 'courses'])->name('courses');
Route::get('/course-detail/{slug?}', [PublicController::class, 'courseDetail'])->name('course.detail');
Route::get('/teachers', [PublicController::class, 'teachers'])->name('teachers');
Route::get('/results', [PublicController::class, 'results'])->name('results');
Route::get('/notice', [PublicController::class, 'notice'])->name('notice');
Route::get('/blog', [PublicController::class, 'blog'])->name('blog');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('contact.submit');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
Route::get('/login/quick/{role}', [AuthController::class, 'quickLogin'])->name('login.quick');

// -------------------------------------------------------------------------
// Admin Panel Protected Routes (Middleware: auth, role:admin)
// -------------------------------------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Students CRUD
    Route::get('/students', [StudentController::class, 'index'])->name('students');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/student-profile/{id?}', [StudentController::class, 'show'])->name('students.profile');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Teachers CRUD
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    // Courses CRUD
    Route::get('/courses', [CourseController::class, 'index'])->name('courses');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Dynamic Course Categories
    Route::post('/course-categories', [CourseController::class, 'storeCategory'])->name('course.categories.store');
    Route::put('/course-categories/{id}', [CourseController::class, 'updateCategory'])->name('course.categories.update');
    Route::delete('/course-categories/{id}', [CourseController::class, 'destroyCategory'])->name('course.categories.destroy');

    // Batches CRUD
    Route::get('/batches', [BatchController::class, 'index'])->name('batches');
    Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
    Route::put('/batches/{id}', [BatchController::class, 'update'])->name('batches.update');
    Route::delete('/batches/{id}', [BatchController::class, 'destroy'])->name('batches.destroy');

    // Classes CRUD (Requested by user: "Class eita alada add korrar option thakber aladas menute")
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::put('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');

    // Exams CRUD
    Route::get('/exams', [ExamController::class, 'index'])->name('exams');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exams.destroy');

    // Results CRUD
    Route::get('/results', [ResultController::class, 'index'])->name('results');
    Route::post('/results', [ResultController::class, 'store'])->name('results.store');
    Route::delete('/results/{id}', [ResultController::class, 'destroy'])->name('results.destroy');

    // Routine CRUD
    Route::get('/routine', [RoutineController::class, 'index'])->name('routine');
    Route::post('/routine', [RoutineController::class, 'store'])->name('routine.store');
    Route::delete('/routine/{id}', [RoutineController::class, 'destroy'])->name('routine.destroy');

    // Fees CRUD
    Route::get('/fees', [FeeController::class, 'index'])->name('fees');
    Route::post('/fees', [FeeController::class, 'store'])->name('fees.store');
    Route::get('/invoice/{id?}', [FeeController::class, 'invoice'])->name('invoice');
    Route::delete('/fees/{id}', [FeeController::class, 'destroy'])->name('fees.destroy');

    // ACCOUNTS CRUD
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    // Notices CRUD
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::put('/notices/{id}', [NoticeController::class, 'update'])->name('notices.update');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');

    // SMS Notifications
    Route::get('/sms', [SmsController::class, 'index'])->name('sms');
    Route::post('/sms/send', [SmsController::class, 'send'])->name('sms.send');
    Route::delete('/sms/{id}', [SmsController::class, 'destroy'])->name('sms.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// -------------------------------------------------------------------------
// Teacher Portal Protected Routes (Middleware: auth, role:teacher,admin)
// -------------------------------------------------------------------------
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher,admin'])->group(function () {
    Route::get('/dashboard', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/routine', [TeacherPortalController::class, 'routine'])->name('routine');
    Route::get('/attendance', [TeacherPortalController::class, 'attendance'])->name('attendance');
});

// -------------------------------------------------------------------------
// Student Portal Protected Routes (Middleware: auth, role:student,admin)
// -------------------------------------------------------------------------
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student,admin'])->group(function () {
    Route::get('/dashboard', [PortalController::class, 'studentDashboard'])->name('dashboard');
});

// -------------------------------------------------------------------------
// Parent Portal Protected Routes (Middleware: auth, role:parent,admin)
// -------------------------------------------------------------------------
Route::prefix('parent')->name('parent.')->middleware(['auth', 'role:parent,admin'])->group(function () {
    Route::get('/dashboard', [PortalController::class, 'parentDashboard'])->name('dashboard');
});
