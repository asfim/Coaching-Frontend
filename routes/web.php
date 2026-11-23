<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PortalController;

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

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::get('/student-profile/{id?}', [AdminController::class, 'studentProfile'])->name('students.profile');
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
    Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    Route::get('/batches', [AdminController::class, 'batches'])->name('batches');
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('attendance');
    Route::get('/exams', [AdminController::class, 'exams'])->name('exams');
    Route::get('/results', [AdminController::class, 'results'])->name('results');
    Route::get('/routine', [AdminController::class, 'routine'])->name('routine');
    Route::get('/fees', [AdminController::class, 'fees'])->name('fees');
    Route::get('/invoice/{id?}', [AdminController::class, 'invoice'])->name('invoice');
    Route::get('/notices', [AdminController::class, 'notices'])->name('notices');
    Route::get('/sms', [AdminController::class, 'sms'])->name('sms');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

// Student & Parent Portals
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'studentDashboard'])->name('dashboard');
});

Route::prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'parentDashboard'])->name('dashboard');
});
