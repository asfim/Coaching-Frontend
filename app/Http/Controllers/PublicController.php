<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Teacher;
use App\Models\Notice;
use App\Models\Student;
use App\Models\Result;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', 'Active')->take(3)->get();
        $teachers = Teacher::where('status', 'Active')->take(4)->get();
        $notices = Notice::where('status', 'published')->latest('published_at')->take(4)->get();

        $studentCount = Student::count() ?: 5240;
        $teacherCount = Teacher::count() ?: 48;
        $courseCount = Course::count() ?: 24;

        return view('public.index', compact('courses', 'teachers', 'notices', 'studentCount', 'teacherCount', 'courseCount'));
    }

    public function about()
    {
        $teachers = Teacher::where('status', 'Active')->take(4)->get();
        return view('public.about', compact('teachers'));
    }

    public function courses(Request $request)
    {
        $query = Course::where('status', 'Active')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $courses = $query->paginate(9);
        $categories = CourseCategory::where('status', 'Active')->orderBy('name')->get();
        return view('public.courses', compact('courses', 'categories'));
    }

    public function courseDetail($slug = null)
    {
        $course = $slug ? Course::where('slug', $slug)->orWhere('id', $slug)->first()
                        : Course::where('status', 'Active')->first();

        $relatedCourses = Course::where('id', '!=', $course?->id)->take(3)->get();

        return view('public.course-detail', compact('course', 'relatedCourses'));
    }

    public function teachers()
    {
        $teachers = Teacher::where('status', 'Active')->paginate(12);
        return view('public.teachers', compact('teachers'));
    }

    public function results(Request $request)
    {
        $query = Result::with(['student.batch', 'exam'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('student', function($q) use ($s) {
                $q->where('student_id', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        $results = $query->paginate(10);
        return view('public.results', compact('results'));
    }

    public function notice(Request $request)
    {
        $query = Notice::where('status', 'published')->latest('published_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $notices = $query->paginate(9);
        return view('public.notice', compact('notices'));
    }

    public function blog()
    {
        return view('public.blog');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Thank you! Your message has been sent successfully. We will get back to you soon.');
    }
}
