@extends('layouts.public')

@section('title', 'EduManage Pro - Smart Coaching Management System Bangladesh')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection
@section('content')
<section style="background:linear-gradient(135deg,#f0f7ff 0%,#faf5ff 55%,#f0fdf4 100%);padding:90px 0 70px;position:relative;overflow:hidden;">
  <div class="container grid-2">
    <div>
      <div class="hero-badge"><i class="bi bi-lightning-charge-fill"></i> Bangladesh's #1 Coaching Management Platform</div>
      <h1 style="margin-bottom:18px;">Smart Coaching Management for <span style="color:var(--primary);">Modern Education</span></h1>
      <p style="font-size:1.05rem;max-width:500px;margin-bottom:30px;">Streamline your coaching center — manage students, teachers, courses, attendance, exams, fees and results from one powerful platform.</p>
      <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:28px;">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg"><i class="bi bi-rocket-takeoff-fill"></i> Get Started Free</a>
        <a href="{{ route('courses') }}" class="btn btn-outline btn-lg"><i class="bi bi-play-circle"></i> Explore Features</a>
      </div>
      <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
        <div style="display:flex;"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" style="width:34px;height:34px;border-radius:50%;border:2px solid #fff;object-fit:cover;margin-right:-8px;"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:34px;height:34px;border-radius:50%;border:2px solid #fff;object-fit:cover;margin-right:-8px;"><img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" style="width:34px;height:34px;border-radius:50%;border:2px solid #fff;object-fit:cover;"></div>
        <span style="font-size:.82rem;color:var(--gray-600);"><strong style="color:var(--gray-900);">5,000+ students</strong> enrolled</span>
        <div style="display:flex;gap:2px;color:#fbbf24;font-size:.8rem;align-items:center;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><span style="color:var(--gray-600);margin-left:4px;">4.9/5</span></div>
      </div>
    </div>
    <div style="position:relative;">
      <img src="{{ asset('images/hero_classroom_1790497065510.jpg') }}" alt="Classroom" style="border-radius:24px;box-shadow:0 20px 60px rgba(0,0,0,.18);width:100%;">
      <div style="position:absolute;top:-18px;right:-12px;background:#fff;border-radius:14px;padding:13px 16px;box-shadow:0 8px 30px rgba(0,0,0,.15);display:flex;align-items:center;gap:10px;animation:float 4s ease-in-out infinite;z-index:5;"><div style="width:38px;height:38px;background:var(--success-light);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--success);"><i class="bi bi-people-fill"></i></div><div><strong style="display:block;font-size:.88rem;">5,243</strong><span style="font-size:.7rem;color:var(--gray-500);">Total Students</span></div></div>
      <div style="position:absolute;bottom:-18px;left:20px;background:#fff;border-radius:14px;padding:13px 16px;box-shadow:0 8px 30px rgba(0,0,0,.15);display:flex;align-items:center;gap:10px;animation:float 4s ease-in-out -2s infinite;z-index:5;"><div style="width:38px;height:38px;background:var(--primary-light);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--primary);"><i class="bi bi-graph-up-arrow"></i></div><div><strong style="display:block;font-size:.88rem;">98.4%</strong><span style="font-size:.7rem;color:var(--gray-500);">Pass Rate</span></div></div>
    </div>
  </div>
</section>

<section style="background:linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%);padding:40px 0;">
  <div class="container grid-4">
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">5,000+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-mortarboard"></i> Students Enrolled</p></div>
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">100+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-person-badge"></i> Expert Teachers</p></div>
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">50+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-journal-bookmark"></i> Active Courses</p></div>
    <div style="text-align:center;"><h3 style="color:#fff;font-size:2.4rem;font-weight:800;">10+</h3><p style="color:rgba(255,255,255,.8);margin:4px 0 0;"><i class="bi bi-calendar-check"></i> Years Excellence</p></div>
  </div>
</section>

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
      <div class="feature-card f-indigo"><div class="feature-icon" style="background:#eef2ff;color:#4338ca;"><i class="bi bi-bar-chart-fill"></i></div><h5>Reports &amp; Analytics</h5><p>Charts, reports and data export</p></div>
    </div>
  </div>
</section>

<section class="section-padding">
  <div class="container grid-2" style="gap:64px;">
    <div style="position:relative;"><img src="{{ asset('images/about_building_1790497156877.jpg') }}" alt="Center" style="border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);width:100%;"><div style="position:absolute;bottom:-20px;right:-20px;background:#fff;border-radius:var(--radius-lg);padding:16px 20px;box-shadow:var(--shadow-lg);border:1px solid var(--border);display:flex;gap:12px;align-items:center;"><div style="width:46px;height:46px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;"><i class="bi bi-award-fill"></i></div><div><h3 style="font-size:1.7rem;line-height:1;margin-bottom:2px;">10+</h3><p style="font-size:.75rem;color:var(--gray-500);margin:0;">Years Excellence</p></div></div></div>
    <div>
      <span class="badge badge-primary" style="margin-bottom:14px;font-size:.8rem;padding:6px 16px;border-radius:50px;">About Our Center</span>
      <h2 style="margin-bottom:14px;">Transforming Education in Bangladesh Since 2014</h2>
      <p style="margin-bottom:18px;">EduManage Pro powers hundreds of coaching centers across Bangladesh, from small tutoring centers to large multi-branch institutions in Dhaka, Chittagong, Rajshahi and Sylhet.</p>
      <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Trusted by 500+ coaching centers nationwide</li>
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> 98.4% student pass rate across all institutes</li>
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Real-time attendance, fees and result management</li>
        <li style="display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:500;"><i class="bi bi-check-circle-fill" style="color:var(--success);"></i> Mobile-responsive portal for students and parents</li>
      </ul>
      <a href="{{ route('about') }}" class="btn btn-primary"><i class="bi bi-arrow-right-circle"></i> Read More</a>
    </div>
  </div>
</section>

<section class="section-padding" style="background:var(--gray-50);">
  <div class="container">
    <div class="section-header"><span class="badge-label bg-success-light text-success">Our Courses</span><h2>Popular Courses</h2><span class="divider-line"></span></div>
    <div class="grid-3">
      <div class="course-card"><div class="course-img"><img src="{{ asset('images/hero_classroom_1790497065510.jpg') }}" alt=""><span class="badge badge-primary" style="position:absolute;top:12px;left:12px;">Science</span></div><div class="course-body"><div class="course-meta"><span><i class="bi bi-clock"></i> 12 Months</span><span><i class="bi bi-people"></i> 85</span></div><h5>SSC Science Complete Batch 2025</h5><p style="font-size:.84rem;color:var(--gray-500);">Physics, Chemistry, Biology and Math full prep.</p></div><div class="course-footer"><div class="course-price">&#2547; 2,500<span>/mo</span></div><a href="{{ route('courses') }}" class="btn btn-primary btn-sm">View Details</a></div></div>
      <div class="course-card"><div class="course-img"><img src="{{ asset('images/students_group_study_1790497179383.jpg') }}" alt=""><span class="badge badge-secondary" style="position:absolute;top:12px;left:12px;">HSC</span></div><div class="course-body"><div class="course-meta"><span><i class="bi bi-clock"></i> 18 Months</span><span><i class="bi bi-people"></i> 120</span></div><h5>HSC Higher Math &amp; Physics Special</h5><p style="font-size:.84rem;color:var(--gray-500);">Advanced HSC prep for A+ students.</p></div><div class="course-footer"><div class="course-price">&#2547; 3,500<span>/mo</span></div><a href="{{ route('courses') }}" class="btn btn-primary btn-sm">View Details</a></div></div>
      <div class="course-card"><div class="course-img"><img src="{{ asset('images/exam_hall_1790497202709.jpg') }}" alt=""><span class="badge badge-warning" style="position:absolute;top:12px;left:12px;">Admission</span></div><div class="course-body"><div class="course-meta"><span><i class="bi bi-clock"></i> 6 Months</span><span><i class="bi bi-people"></i> 200</span></div><h5>University Admission Crash Course</h5><p style="font-size:.84rem;color:var(--gray-500);">DU, BUET admission intensive preparation.</p></div><div class="course-footer"><div class="course-price">&#2547; 5,000<span>/mo</span></div><a href="{{ route('courses') }}" class="btn btn-primary btn-sm">View Details</a></div></div>
    </div>
    <div style="text-align:center;margin-top:36px;"><a href="{{ route('courses') }}" class="btn btn-outline btn-lg"><i class="bi bi-grid"></i> View All Courses</a></div>
  </div>
</section>

<section class="section-padding" style="background:var(--gray-50);">
  <div class="container">
    <div class="section-header"><span class="badge-label bg-orange-light" style="color:var(--orange);">Our Faculty</span><h2>Expert Teachers</h2><span class="divider-line"></span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;">
      <div class="teacher-card"><div class="teacher-img-wrap"><img src="{{ asset('images/teacher_female_1_1790497081063.jpg') }}" alt=""></div><div class="card-body"><h5>Nasrin Sultana</h5><div class="subject">Bangladesh Studies</div><div class="qual">M.A., DU</div><div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a></div></div></div>
      <div class="teacher-card"><div class="teacher-img-wrap"><img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" alt=""></div><div class="card-body"><h5>Md. Rafiqul Islam</h5><div class="subject">Physics &amp; Math</div><div class="qual">M.Sc., BUET</div><div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a></div></div></div>
      <div class="teacher-card"><div class="teacher-img-wrap"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""></div><div class="card-body"><h5>Prof. Abdul Karim</h5><div class="subject">Higher Math</div><div class="qual">Ph.D., DU</div><div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a></div></div></div>
      <div class="teacher-card"><div class="teacher-img-wrap"><img src="{{ asset('images/teacher_female_2_1790497140028.jpg') }}" alt=""></div><div class="card-body"><h5>Farhana Akter</h5><div class="subject">Chem &amp; Bio</div><div class="qual">M.Sc., RU</div><div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a></div></div></div>
    </div>
    <div style="text-align:center;margin-top:36px;"><a href="{{ route('teachers') }}" class="btn btn-outline btn-lg">View All Teachers</a></div>
  </div>
</section>

<section class="section-padding">
  <div class="container grid-2" style="gap:56px;align-items:flex-start;">
    <div><span class="badge badge-danger" style="margin-bottom:14px;font-size:.8rem;padding:6px 16px;border-radius:50px;">Latest Notices</span><h2 style="margin-bottom:24px;">Announcements</h2>
      <div style="display:flex;flex-direction:column;gap:12px;">
        <div class="notice-card urgent"><div style="display:flex;justify-content:space-between;"><div class="notice-title">SSC Model Test – Registration Open</div><span class="badge badge-danger">Urgent</span></div><p style="font-size:.83rem;color:var(--gray-600);margin:6px 0 8px;">Register before Oct 5, 2026.</p><div class="notice-meta"><i class="bi bi-calendar3"></i> 27 Sep 2026 &nbsp;<a href="{{ route('notice') }}" style="color:var(--primary);font-weight:600;">Read More &rarr;</a></div></div>
        <div class="notice-card exam"><div style="display:flex;justify-content:space-between;"><div class="notice-title">Monthly Fee Deadline</div><span class="badge badge-warning">Fee</span></div><p style="font-size:.83rem;color:var(--gray-600);margin:6px 0 8px;">Pay October fees by Oct 10.</p><div class="notice-meta"><i class="bi bi-calendar3"></i> 25 Sep 2026 &nbsp;<a href="{{ route('notice') }}" style="color:var(--primary);font-weight:600;">Read More &rarr;</a></div></div>
        <div class="notice-card general"><div style="display:flex;justify-content:space-between;"><div class="notice-title">New HSC Batch — October 2026</div><span class="badge badge-success">New</span></div><p style="font-size:.83rem;color:var(--gray-600);margin:6px 0 8px;">Admissions open. Limited seats.</p><div class="notice-meta"><i class="bi bi-calendar3"></i> 22 Sep 2026 &nbsp;<a href="{{ route('notice') }}" style="color:var(--primary);font-weight:600;">Read More &rarr;</a></div></div>
      </div>
      <a href="{{ route('notice') }}" class="btn btn-outline" style="margin-top:20px;"><i class="bi bi-megaphone"></i> All Notices</a>
    </div>
    <div>
      <span class="badge badge-success" style="margin-bottom:14px;font-size:.8rem;padding:6px 16px;border-radius:50px;">Success Rate</span><h2 style="margin-bottom:14px;">Outstanding Results</h2>
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

<section class="section-padding" style="background:var(--gray-50);">
  <div class="container">
    <div class="section-header"><span class="badge-label bg-primary-light text-primary">Testimonials</span><h2>What Students &amp; Parents Say</h2><span class="divider-line"></span></div>
    <div class="grid-3">
      <div class="testimonial-card"><div class="quote-icon">"</div><p>"EduManage Pro completely transformed our coaching center. Attendance, fees and results are all digital now!"</p><div class="testimonial-author"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" alt=""><div><div class="name">Rakibul Hasan</div><div class="role">SSC 2024 — GPA 5.0</div></div><div class="stars" style="margin-left:auto;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div></div></div>
      <div class="testimonial-card"><div class="quote-icon">"</div><p>"As a parent I can track my daughter's attendance from my phone. The SMS alerts are very helpful!"</p><div class="testimonial-author"><img src="{{ asset('images/teacher_female_1_1790497081063.jpg') }}" alt=""><div><div class="name">Shahida Begum</div><div class="role">Parent, Class X</div></div><div class="stars" style="margin-left:auto;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div></div></div>
      <div class="testimonial-card"><div class="quote-icon">"</div><p>"Managing 300+ students manually was a nightmare. EduManage Pro automated everything!"</p><div class="testimonial-author"><img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" alt=""><div><div class="name">Md. Rafiqul Islam</div><div class="role">Physics Teacher</div></div><div class="stars" style="margin-left:auto;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div></div></div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="container"><h2 style="margin-bottom:14px;">Ready to Manage Your Coaching Center Smarter?</h2><p>Join 500+ coaching centers. Free trial — no credit card required.</p>
    <div class="cta-actions"><a href="{{ route('admin.dashboard') }}" class="btn btn-white btn-lg"><i class="bi bi-rocket-takeoff-fill"></i> Get Started Free</a><a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg"><i class="bi bi-telephone"></i> Contact Us</a></div>
  </div>
</section>
@endsection
