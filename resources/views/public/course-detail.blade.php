@extends('layouts.public')

@section('title', ($course->title ?? 'Course Details') . ' - EduManage Pro')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection

@section('content')
<div class="page-banner">
  <div class="container">
    <h1>{{ $course->title ?? 'SSC Science Complete Batch 2025' }}</h1>
    <p>{{ $course->description ? Str::limit($course->description, 120) : 'Comprehensive coaching preparation with experienced faculty and regular mock tests' }}</p>
    <div class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span>/</span>
      <a href="{{ route('courses') }}">Courses</a>
      <span>/</span>
      <span>{{ $course->title ?? 'Course Details' }}</span>
    </div>
  </div>
</div>

<section class="section-padding">
  <div class="container grid-2" style="gap:40px;align-items:flex-start;">
    <div>
      <img src="{{ !empty($course->image) ? asset($course->image) : asset('images/hero_classroom_1790497065510.jpg') }}" alt="{{ $course->title ?? 'Course' }}" style="border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);width:100%;margin-bottom:24px;max-height:420px;object-fit:cover;">
      
      <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:20px;">
        <h4 style="font-weight:700;margin-bottom:16px;">About This Course</h4>
        <p style="margin-bottom:12px;">{{ $course->description ?? 'Our comprehensive coaching program is designed to give students the highest quality preparation with experienced teachers, regular tests, and personalized care.' }}</p>
        <p>This program covers the complete syllabus with emphasis on critical problem solving, exam techniques, time management, and conceptual clarity to ensure top grades.</p>
      </div>

      <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:20px;">
        <h4 style="font-weight:700;margin-bottom:16px;">Course Syllabus &amp; Modules</h4>
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--gray-100);">
          <div style="width:28px;height:28px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;">1</div>
          <span style="font-size:.875rem;">Fundamental Concepts &amp; Core Theory Overview</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--gray-100);">
          <div style="width:28px;height:28px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;">2</div>
          <span style="font-size:.875rem;">Chapter-wise In-depth Lectures &amp; Problem Solving</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--gray-100);">
          <div style="width:28px;height:28px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;">3</div>
          <span style="font-size:.875rem;">Weekly Chapter Assessments &amp; Personalized Feedback</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--gray-100);">
          <div style="width:28px;height:28px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;">4</div>
          <span style="font-size:.875rem;">Board &amp; Admission Standard Creative Questions (CQ) Practice</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--gray-100);">
          <div style="width:28px;height:28px;background:var(--primary-light);color:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;">5</div>
          <span style="font-size:.875rem;">Full-Length Model Tests with Real Exam Atmosphere</span>
        </div>
      </div>

      @if(isset($relatedCourses) && $relatedCourses->count() > 0)
      <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;">
        <h4 style="font-weight:700;margin-bottom:16px;">Other Available Courses</h4>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">
          @foreach($relatedCourses as $rc)
          <div style="border:1px solid var(--gray-200);border-radius:var(--radius);padding:14px;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
              <span class="badge badge-primary" style="font-size:.7rem;margin-bottom:8px;">{{ $rc->category }}</span>
              <h6 style="font-weight:700;margin-bottom:6px;">{{ $rc->title }}</h6>
              <p style="font-size:.8rem;color:var(--gray-500);margin-bottom:10px;">{{ Str::limit($rc->description, 60) }}</p>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid var(--gray-100);">
              <strong style="color:var(--primary);font-size:.9rem;">৳{{ number_format($rc->fee) }}</strong>
              <a href="{{ route('course.detail', $rc->slug ?? $rc->id) }}" class="btn btn-primary btn-sm" style="font-size:.75rem;padding:4px 10px;">Details</a>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>

    <!-- Enrollment Sidebar -->
    <div style="position:sticky;top:88px;">
      <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-xl);padding:28px;box-shadow:var(--shadow-lg);margin-bottom:20px;">
        <div style="text-align:center;margin-bottom:20px;">
          <span style="font-size:2.5rem;font-weight:800;color:var(--primary);">৳{{ number_format($course->fee ?? 2500) }}</span>
          <span style="color:var(--gray-500);font-size:.9rem;"> / month</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:.875rem;">
            <span style="color:var(--gray-600);"><i class="bi bi-clock" style="color:var(--primary);margin-right:6px;"></i> Duration</span>
            <strong>{{ $course->duration ?? '12 Months' }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:.875rem;">
            <span style="color:var(--gray-600);"><i class="bi bi-people" style="color:var(--primary);margin-right:6px;"></i> Max Seats</span>
            <strong>{{ $course->seat_limit ?? '100' }} Students</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:.875rem;">
            <span style="color:var(--gray-600);"><i class="bi bi-tags" style="color:var(--primary);margin-right:6px;"></i> Category</span>
            <strong>{{ $course->category ?? 'General' }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:.875rem;">
            <span style="color:var(--gray-600);"><i class="bi bi-geo-alt" style="color:var(--primary);margin-right:6px;"></i> Location</span>
            <strong>Main Campus</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:.875rem;">
            <span style="color:var(--gray-600);"><i class="bi bi-translate" style="color:var(--primary);margin-right:6px;"></i> Medium</span>
            <strong>Bangla &amp; English</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:.875rem;">
            <span style="color:var(--gray-600);"><i class="bi bi-award" style="color:var(--primary);margin-right:6px;"></i> Certificate</span>
            <strong>Upon Completion</strong>
          </div>
        </div>
        
        <a href="{{ route('contact') }}" class="btn btn-primary" style="width:100%;padding:14px;font-size:1rem;margin-bottom:10px;justify-content:center;text-decoration:none;">
          <i class="bi bi-person-plus-fill"></i> Enroll in this Course
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline" style="width:100%;justify-content:center;text-decoration:none;">
          <i class="bi bi-telephone"></i> Call for Info
        </a>
      </div>

      <div style="background:var(--primary-light);border-radius:var(--radius-lg);padding:20px;">
        <h5 style="font-weight:700;margin-bottom:12px;color:var(--primary);">Included in This Course</h5>
        <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;">
          <li style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gray-700);font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Live lectures &amp; discussion classes</li>
          <li style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gray-700);font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Chapter-wise mock tests and model exams</li>
          <li style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gray-700);font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Exclusive printed lecture sheets</li>
          <li style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gray-700);font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Individual doubt-clearing sessions</li>
          <li style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gray-700);font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> SMS notification to parents for attendance</li>
          <li style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--gray-700);font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Student portal &amp; mobile report access</li>
        </ul>
      </div>
    </div>
  </div>
</section>
@endsection
