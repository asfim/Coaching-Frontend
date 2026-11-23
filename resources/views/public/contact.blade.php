@extends('layouts.public')

@section('title', 'Contact Us - EduManage Pro')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection
@section('content')
<div class="page-banner">
  <div class="container"><h1>Contact Us</h1><p>Get in touch with us for admissions, queries and support</p>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><span>Contact</span></div>
  </div>
</div>

<section class="section-padding">
  <div class="container grid-2" style="gap:56px;align-items:flex-start;">
    <!-- Contact Form -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-xl);padding:36px;box-shadow:var(--shadow);">
      <h4 style="font-weight:700;margin-bottom:24px;">Send Us a Message</h4>
      <div class="alert alert-primary" style="margin-bottom:20px;"><i class="bi bi-info-circle-fill"></i> We typically respond within 2-4 hours during business hours.</div>
      <div class="form-group"><label class="form-label">Full Name *</label><input class="form-control" placeholder="Your full name"></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="form-group"><label class="form-label">Phone *</label><input class="form-control" placeholder="01700-000000"></div>
        <div class="form-group"><label class="form-label">Email</label><input class="form-control" type="email" placeholder="your@email.com"></div>
      </div>
      <div class="form-group"><label class="form-label">Subject *</label><select class="form-select"><option>General Inquiry</option><option>Admission Query</option><option>Fee Information</option><option>Course Details</option><option>Technical Support</option></select></div>
      <div class="form-group"><label class="form-label">Message *</label><textarea class="form-control" rows="5" placeholder="Write your message here..."></textarea></div>
      <button class="btn btn-primary" style="width:100%;padding:14px;font-size:1rem;"><i class="bi bi-send-fill"></i> Send Message</button>
    </div>

    <!-- Contact Info -->
    <div>
      <div style="background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:var(--radius-xl);padding:32px;color:#fff;margin-bottom:20px;">
        <h4 style="color:#fff;margin-bottom:24px;"><i class="bi bi-geo-alt-fill"></i> Find Us</h4>
        <div style="display:flex;flex-direction:column;gap:18px;">
          <div style="display:flex;align-items:flex-start;gap:14px;"><div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-geo-alt-fill"></i></div><div><strong style="display:block;color:#fff;margin-bottom:2px;">Address</strong><span style="color:rgba(255,255,255,.8);font-size:.9rem;">House 12, Road 5, Dhanmondi, Dhaka-1205, Bangladesh</span></div></div>
          <div style="display:flex;align-items:center;gap:14px;"><div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-telephone-fill"></i></div><div><strong style="display:block;color:#fff;margin-bottom:2px;">Phone</strong><span style="color:rgba(255,255,255,.8);font-size:.9rem;">+880 1700-123456</span></div></div>
          <div style="display:flex;align-items:center;gap:14px;"><div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-envelope-fill"></i></div><div><strong style="display:block;color:#fff;margin-bottom:2px;">Email</strong><span style="color:rgba(255,255,255,.8);font-size:.9rem;">info@edumanagepro.com.bd</span></div></div>
          <div style="display:flex;align-items:center;gap:14px;"><div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-clock-fill"></i></div><div><strong style="display:block;color:#fff;margin-bottom:2px;">Office Hours</strong><span style="color:rgba(255,255,255,.8);font-size:.9rem;">Sat–Thu: 8:00 AM – 9:00 PM<br>Friday: 2:00 PM – 8:00 PM</span></div></div>
        </div>
      </div>

      <!-- Quick Links -->
      <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;">
        <h5 style="font-weight:700;margin-bottom:16px;">Quick Actions</h5>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <a href="{{ route('student.dashboard') }}" class="btn btn-outline" style="justify-content:flex-start;"><i class="bi bi-person-badge"></i> Student Login</a>
          <a href="{{ route('parent.dashboard') }}" class="btn btn-outline" style="justify-content:flex-start;"><i class="bi bi-people"></i> Parent Login</a>
          <a href="{{ route('courses') }}" class="btn btn-outline" style="justify-content:flex-start;"><i class="bi bi-journal-bookmark"></i> View Courses</a>
          <a href="{{ route('notice') }}" class="btn btn-outline" style="justify-content:flex-start;"><i class="bi bi-megaphone"></i> Notice Board</a>
          <a href="{{ route('results') }}" class="btn btn-outline" style="justify-content:flex-start;"><i class="bi bi-trophy"></i> Check Results</a>
        </div>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
          <p style="font-size:.82rem;color:var(--gray-600);margin-bottom:10px;">Follow us on social media:</p>
          <div class="social-links" style="margin-top:0;">
            <a class="social-link" href="#" style="background:var(--primary);color:#fff;"><i class="bi bi-facebook"></i></a>
            <a class="social-link" href="#" style="background:#25D366;color:#fff;"><i class="bi bi-whatsapp"></i></a>
            <a class="social-link" href="#" style="background:var(--danger);color:#fff;"><i class="bi bi-youtube"></i></a>
            <a class="social-link" href="#"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
