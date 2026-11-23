@extends('layouts.public')

@section('title', 'EduManage Pro - Smart Coaching Management System Bangladesh')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section style="background:linear-gradient(135deg,#f0f7ff 0%,#faf5ff 55%,#f0fdf4 100%);padding:90px 0 70px;position:relative;overflow:hidden;">
  <div class="container grid-2">
    <div>
      <div class="hero-badge"><i class="bi bi-lightning-charge-fill"></i> Bangladesh's #1 Coaching Management Platform</div>
      <h1 style="margin-bottom:18px;">Smart Coaching Management for <span style="color:var(--primary);">Modern Education</span></h1>
      <p style="font-size:1.05rem;max-width:500px;margin-bottom:30px;">Streamline your coaching center — manage students, teachers, courses, attendance, exams, fees and results from one powerful platform.</p>
      <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:28px;">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg"><i class="bi bi-rocket-takeoff-fill"></i> Go to Admin Portal</a>
        <a href="{{ route('courses') }}" class="btn btn-outline btn-lg"><i class="bi bi-play-circle"></i> Explore Courses</a>
      </div>
      <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
        <div style="display:flex;">
          <img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" style="width:34px;height:34px;border-radius:50%;border:2px solid #fff;object-fit:cover;margin-right:-8px;">
          <img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:34px;height:34px;border-radius:50%;border:2px solid #fff;object-fit:cover;margin-right:-8px;">
          <img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" style="width:34px;height:34px;border-radius:50%;border:2px solid #fff;object-fit:cover;">
        </div>
        <span style="font-size:.82rem;color:var(--gray-600);"><strong style="color:var(--gray-900);">{{ $studentCount }}+ students</strong> enrolled</span>
        <div style="display:flex;gap:2px;color:#fbbf24;font-size:.8rem;align-items:center;">
          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
          <span style="color:var(--gray-600);margin-left:4px;">4.9/5</span>
        </div>
      </div>
    </div>
    <div style="position:relative;">
      <img src="{{ asset('images/hero_classroom_1790497065510.jpg') }}" alt="Classroom" style="border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,.18);width:100%;">
      <div style="position:absolute;top:-18px;right:-12px;background:#fff;border-radius:14px;padding:13px 16px;box-shadow:0 8px 30px rgba(0,0,0,.15);display:flex;align-items:center;gap:10px;animation:float 4s ease-in-out infinite;z-index:5;">
        <div style="width:38px;height:38px;background:var(--success-light);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--success);"><i class="bi bi-people-fill"></i></div>
        <div><strong style="display:block;font-size:.88rem;">{{ $studentCount }}</strong><span style="font-size:.7rem;color:var(--gray-500);">Total Students</span></div>
      </div>
      <div style="position:absolute;bottom:-18px;left:20px;background:#fff;border-radius:14px;padding:13px 16px;box-shadow:0 8px 30px rgba(0,0,0,.15);display:flex;align-items:center;gap:10px;animation:float 4s ease-in-out -2s infinite;z-index:5;">
        <div style="width:38px;height:38px;background:var(--primary-light);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--primary);"><i class="bi bi-graph-up-arrow"></i></div>
        <div><strong style="display:block;font-size:.88rem;">98.4%</strong><span style="font-size:.7rem;color:var(--gray-500);">Pass Rate</span></div>
      </div>
    </div>
  </div>
</section>

<!-- Quick Stats Bar -->
<section style="background:linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%);padding:40px 0;">
  <div class="container grid-4">
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">{{ $studentCount }}+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-mortarboard"></i> Students Enrolled</p></div>
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">{{ $teacherCount }}+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-person-badge"></i> Expert Teachers</p></div>
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">{{ $courseCount }}+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-journal-bookmark"></i> Active Courses</p></div>
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">10+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-calendar-check"></i> Years Excellence</p></div>
  </div>
</section>

<!-- Platform Features -->
<section class="section-padding" style="background:var(--gray-50);">
  <div class="container">
    <div class="section-header"><span class="badge-label bg-primary-light text-primary">Platform Features</span><h2>Everything You Need to Run a Coaching Center</h2><p>A comprehensive suite of tools for Bangladesh's coaching institutes</p><span class="divider-line"></span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:18px;">
      <div class="feature-card f-blue"><div class="feature-icon" style="background:var(--primary-light);color:var(--primary);"><i class="bi bi-person-vcard"></i></div><h5>Student Management</h5><p>Complete profiles, admission tracking and guardian info</p></div>
      <div class="feature-card f-purple"><div class="feature-icon" style="background:var(--secondary-light);color:var(--secondary);"><i class="bi bi-person-badge"></i></div><h5>Teacher Management</h5><p>Profiles, schedules, salary and performance tracking</p></div>
      <div class="feature-card f-green"><div class="feature-icon" style="background:var(--success-light);color:var(--success);"><i class="bi bi-journal-bookmark-fill"></i></div><h5>Course Management</h5><p>Create courses, set fees and manage syllabus</p></div>
      <div class="feature-card f-orange"><div class="feature-icon" style="background:var(--orange-light);color:var(--orange);"><i class="bi bi-calendar2-check"></i></div><h5>Attendance</h5><p>Daily tracking: present/absent/late/leave</p></div>
      <div class="feature-card f-cyan"><div class="feature-icon" style="background:var(--info-light);color:var(--info);"><i class="bi bi-file-earmark-text"></i></div><h5>Exam &amp; Result</h5><p>Schedule exams, auto-calc GPA and publish results</p></div>
      <div class="feature-card f-yellow"><div class="feature-icon" style="background:var(--warning-light);color:var(--warning);"><i class="bi bi-cash-coin"></i></div><h5>Fees Management</h5><p>Collect fees, track dues, generate invoices</p></div>
      <div class="feature-card f-red"><div class="feature-icon" style="background:var(--danger-light);color:var(--danger);"><i class="bi bi-megaphone-fill"></i></div><h5>Notice Board</h5><p>Post notices and announcements instantly</p></div>
      <div class="feature-card f-teal"><div class="feature-icon" style="background:#f0fdfa;color:#0d9488;"><i class="bi bi-clock-history"></i></div><h5>Class Routine</h5><p>Weekly timetable for all batches</p></div>
      <div class="feature-card f-pink"><div class="feature-icon" style="background:#fdf2f8;color:#db2777;"><i class="bi bi-chat-dots-fill"></i></div><h5>SMS Notification</h5><p>Bulk SMS to students and parents</p></div>
      <div class="feature-card f-indigo"><div class="feature-icon" style="background:#eef2ff;color:#4338ca;"><i class="bi bi-bar-chart-fill"></i></div><h5>Accounts &amp; Analytics</h5><p>Income, expense and financial balance tracking</p></div>
    </div>
  </div>
</section>

<!-- About Section -->
<section class="section-padding">
  <div class="container grid-2" style="gap:64px;">
    <div style="position:relative;"><img src="{{ asset('images/about_building_1790497156877.jpg') }}" alt="Center" style="border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);width:100%;"><div style="position:absolute;bottom:-20px;right:-20px;background:#fff;border-radius:var(--radius-lg);padding:16px 20px;box-shadow:var(--shadow-lg);border:1px solid var(--border);display:flex;gap:12px;align-items:center;"><div style="width:46px;height:46px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;"><i class="bi bi-award-fill"></i></div><div><h3 style="font-size:1.7rem;line-height:1;margin-bottom:2px;">10+</h3><p style="font-size:.75rem;color:var(--gray-500);margin:0;">Years Excellence</p></div></div></div>
    <div>
      <span class="badge badge-primary" style="margin-bottom:14px;font-size:.8rem;padding:6px 16px;border-radius:50px;">About Our Center</span>
      <h2 style="margin-bottom:14px;">Transforming Education in Bangladesh Since 2014</h2>
      <p style="margin-bottom:18px;">EduManage Pro powers coaching centers across Bangladesh, from tutoring centers to large multi-branch institutions in Dhaka, Chittagong, Rajshahi and Sylhet.</p>
      <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Trusted by 500+ coaching centers nationwide</li>
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> 98.4% student pass rate across all institutes</li>
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Real-time attendance, accounts and result management</li>
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Mobile-responsive portal for students and parents</li>
      </ul>
      <a href="{{ route('about') }}" class="btn btn-primary"><i class="bi bi-arrow-right-circle"></i> Read More</a>
    </div>
  </div>
</section>

<!-- Popular Courses -->
<section class="section-padding" style="background:var(--gray-50);">
  <div class="container">
    <div class="section-header"><span class="badge-label bg-success-light text-success">Our Courses</span><h2>Popular Courses</h2><span class="divider-line"></span></div>
    <div class="grid-3">
      @forelse($courses as $c)
      <div class="course-card">
        <div class="course-img">
          <img src="{{ $c->image ? asset($c->image) : asset('images/hero_classroom_1790497065510.jpg') }}" alt="{{ $c->title }}">
          <span class="badge badge-primary" style="position:absolute;top:12px;left:12px;">{{ $c->category }}</span>
        </div>
        <div class="course-body">
          <div class="course-meta">
            <span><i class="bi bi-clock"></i> {{ $c->duration }}</span>
            <span><i class="bi bi-people"></i> {{ $c->seat_limit ?? '100' }} Seats</span>
          </div>
          <h5 style="margin-top:8px;">{{ $c->title }}</h5>
          <p style="font-size:.84rem;color:var(--gray-500);">{{ Str::limit($c->description, 80) }}</p>
        </div>
        <div class="course-footer">
          <div class="course-price">&#2547; {{ number_format($c->fee) }}<span>/mo</span></div>
          <a href="{{ route('course.detail', $c->slug ?? $c->id) }}" class="btn btn-primary btn-sm">View Details</a>
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--gray-500);">No active courses found.</div>
      @endforelse
    </div>
    <div style="text-align:center;margin-top:36px;"><a href="{{ route('courses') }}" class="btn btn-outline btn-lg"><i class="bi bi-grid"></i> View All Courses</a></div>
  </div>
</section>

<!-- Faculty Section -->
<section class="section-padding">
  <div class="container">
    <div class="section-header"><span class="badge-label bg-orange-light" style="color:var(--orange);">Our Faculty</span><h2>Expert Teachers</h2><span class="divider-line"></span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;">
      @forelse($teachers as $t)
      <div class="teacher-card">
        <div class="teacher-img-wrap">
          <img src="{{ $t->image ? asset($t->image) : asset('images/teacher_male_1_1790497096029.jpg') }}" alt="{{ $t->name }}">
        </div>
        <div class="card-body">
          <h5>{{ $t->name }}</h5>
          <div class="subject">{{ $t->subject }}</div>
          <div class="qual">{{ $t->qualification }}</div>
          <div class="teacher-social">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--gray-500);">No faculty records found.</div>
      @endforelse
    </div>
    <div style="text-align:center;margin-top:36px;"><a href="{{ route('teachers') }}" class="btn btn-outline btn-lg">View All Teachers</a></div>
  </div>
</section>

<!-- Notices and Results -->
<section class="section-padding" style="background:var(--gray-50);">
  <div class="container grid-2" style="gap:56px;align-items:flex-start;">
    <div>
      <span class="badge badge-danger" style="margin-bottom:14px;font-size:.8rem;padding:6px 16px;border-radius:50px;">Latest Notices</span>
      <h2 style="margin-bottom:24px;">Announcements</h2>
      <div style="display:flex;flex-direction:column;gap:12px;">
        @forelse($notices as $n)
        <div class="notice-card {{ strtolower($n->category) == 'exam' ? 'urgent' : (strtolower($n->category) == 'admission' ? 'general' : 'exam') }}">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <div class="notice-title">{{ $n->title }}</div>
            <span class="badge {{ $n->category == 'Exam' ? 'badge-danger' : ($n->category == 'Admission' ? 'badge-success' : 'badge-warning') }}">{{ $n->category }}</span>
          </div>
          <p style="font-size:.83rem;color:var(--gray-600);margin:6px 0 8px;">{{ Str::limit($n->description, 90) }}</p>
          <div class="notice-meta">
            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($n->published_at)->format('d M Y') }} &nbsp;
            <a href="{{ route('notice') }}" style="color:var(--primary);font-weight:600;">Read More &rarr;</a>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:var(--gray-500);">No notices published yet.</div>
        @endforelse
      </div>
      <a href="{{ route('notice') }}" class="btn btn-outline" style="margin-top:20px;"><i class="bi bi-megaphone"></i> All Notices</a>
    </div>
    <div>
      <span class="badge badge-success" style="margin-bottom:14px;font-size:.8rem;padding:6px 16px;border-radius:50px;">Success Rate</span>
      <h2 style="margin-bottom:14px;">Outstanding Results</h2>
      <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
        <div><div style="display:flex;justify-content:space-between;margin-bottom:6px;"><span style="font-size:.85rem;font-weight:600;">SSC Pass Rate</span><strong style="color:var(--success);">98.4%</strong></div><div class="progress"><div class="progress-bar green" style="width:98.4%;"></div></div></div>
        <div><div style="display:flex;justify-content:space-between;margin-bottom:6px;"><span style="font-size:.85rem;font-weight:600;">HSC A+ Rate</span><strong style="color:var(--primary);">76.2%</strong></div><div class="progress"><div class="progress-bar blue" style="width:76.2%;"></div></div></div>
        <div><div style="display:flex;justify-content:space-between;margin-bottom:6px;"><span style="font-size:.85rem;font-weight:600;">Uni Admission</span><strong style="color:var(--secondary);">89.5%</strong></div><div class="progress"><div class="progress-bar purple" style="width:89.5%;"></div></div></div>
        <div><div style="display:flex;justify-content:space-between;margin-bottom:6px;"><span style="font-size:.85rem;font-weight:600;">Student Satisfaction</span><strong style="color:var(--orange);">97%</strong></div><div class="progress"><div class="progress-bar orange" style="width:97%;"></div></div></div>
      </div>
      <a href="{{ route('results') }}" class="btn btn-success"><i class="bi bi-trophy"></i> View All Results</a>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="section-padding">
  <div class="container">
    <div class="section-header"><span class="badge-label bg-primary-light text-primary">Testimonials</span><h2>What Students &amp; Parents Say</h2><span class="divider-line"></span></div>
    <div class="grid-3">
      <div class="testimonial-card"><div class="quote-icon">"</div><p>"EduManage Pro completely transformed our coaching center. Attendance, fees and results are all digital now!"</p><div class="testimonial-author"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" alt=""><div><div class="name">Rakibul Hasan</div><div class="role">SSC 2024 — GPA 5.0</div></div><div class="stars" style="margin-left:auto;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div></div></div>
      <div class="testimonial-card"><div class="quote-icon">"</div><p>"As a parent I can track my daughter's attendance from my phone. The SMS alerts are very helpful!"</p><div class="testimonial-author"><img src="{{ asset('images/teacher_female_1_1790497081063.jpg') }}" alt=""><div><div class="name">Shahida Begum</div><div class="role">Parent, Class X</div></div><div class="stars" style="margin-left:auto;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div></div></div>
      <div class="testimonial-card"><div class="quote-icon">"</div><p>"Managing 300+ students manually was a nightmare. EduManage Pro automated everything!"</p><div class="testimonial-author"><img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" alt=""><div><div class="name">Md. Rafiqul Islam</div><div class="role">Physics Teacher</div></div><div class="stars" style="margin-left:auto;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div></div></div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
  <div class="container"><h2 style="margin-bottom:14px;">Ready to Manage Your Coaching Center Smarter?</h2><p>Join 500+ coaching centers. Free trial — no credit card required.</p>
    <div class="cta-actions"><a href="{{ route('admin.dashboard') }}" class="btn btn-white btn-lg"><i class="bi bi-rocket-takeoff-fill"></i> Get Started Free</a><a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg"><i class="bi bi-telephone"></i> Contact Us</a></div>
  </div>
</section>
@endsection
