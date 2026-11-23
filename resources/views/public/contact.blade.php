@extends('layouts.public')

@section('title', 'Contact Us - EduManage Pro')

@section('content')
<section style="background:linear-gradient(135deg,var(--primary-light),var(--secondary-light));padding:48px 0 36px;border-bottom:1px solid var(--border);">
  <div class="container text-center">
    <span class="badge badge-primary" style="margin-bottom:10px;padding:6px 16px;border-radius:50px;">Get In Touch</span>
    <h1 style="margin-bottom:10px;font-weight:800;">Contact EduManage Pro</h1>
    <p style="font-size:1.05rem;color:var(--gray-600);max-width:600px;margin:0 auto;">
      Have questions about batch schedules, admissions, or fees? Our team is here to assist you.
    </p>
  </div>
</section>

<section class="section-padding">
  <div class="container">
    @if(session('success'))
      <div style="background:#ecfdf5;border-left:4px solid #059669;padding:16px;border-radius:var(--radius);margin-bottom:28px;color:#065f46;max-width:900px;margin-left:auto;margin-right:auto;">
        <i class="bi bi-check-circle-fill" style="margin-right:8px;font-size:1.2rem;color:#059669;"></i> {{ session('success') }}
      </div>
    @endif

    <div class="grid-2" style="max-width:1050px;margin:0 auto;gap:48px;align-items:flex-start;">
      <!-- Contact Info -->
      <div>
        <h3 style="margin-bottom:16px;font-weight:800;">Visit or Message Our Main Branch</h3>
        <p style="color:var(--gray-600);margin-bottom:28px;line-height:1.6;">
          Our office is open Saturday through Thursday, 9:00 AM to 8:00 PM for on-site counseling, student admissions, and fee payments.
        </p>

        <div style="display:flex;flex-direction:column;gap:20px;">
          <div style="display:flex;gap:16px;align-items:flex-start;">
            <div style="width:44px;height:44px;border-radius:12px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
              <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div>
              <strong style="display:block;margin-bottom:2px;">Campus Address</strong>
              <span style="color:var(--gray-600);font-size:0.9rem;">House 12, Road 5, Dhanmondi, Dhaka-1205, Bangladesh</span>
            </div>
          </div>

          <div style="display:flex;gap:16px;align-items:flex-start;">
            <div style="width:44px;height:44px;border-radius:12px;background:var(--success-light);color:var(--success);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
              <i class="bi bi-telephone-fill"></i>
            </div>
            <div>
              <strong style="display:block;margin-bottom:2px;">Phone Numbers</strong>
              <span style="color:var(--gray-600);font-size:0.9rem;">+880 1700-123456, +880 1800-654321</span>
            </div>
          </div>

          <div style="display:flex;gap:16px;align-items:flex-start;">
            <div style="width:44px;height:44px;border-radius:12px;background:var(--warning-light);color:var(--warning);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
              <i class="bi bi-envelope-fill"></i>
            </div>
            <div>
              <strong style="display:block;margin-bottom:2px;">Email Address</strong>
              <span style="color:var(--gray-600);font-size:0.9rem;">info@edumanagepro.com.bd, support@edumanagepro.com.bd</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-xl);padding:32px;box-shadow:var(--shadow-md);">
        <h4 style="margin-bottom:18px;font-weight:700;">Send us an Inquiry</h4>
        <form action="{{ route('contact.submit') }}" method="POST">
          @csrf
          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Your Full Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Tanvir Ahmed" required>
          </div>
          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Mobile Phone *</label>
            <input type="text" name="phone" class="form-control" placeholder="01700-000000" required>
          </div>
          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Interested Course / Program</label>
            <select name="course_interest" class="form-select">
              <option value="SSC Science">SSC Science Complete Batch</option>
              <option value="HSC Math Physics">HSC Higher Math &amp; Physics</option>
              <option value="Admission">University Admission Crash Course</option>
              <option value="General Inquiry">General Inquiry</option>
            </select>
          </div>
          <div style="margin-bottom:20px;">
            <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Your Message / Query *</label>
            <textarea name="message" class="form-control" rows="4" placeholder="Write your question or request..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">
            <i class="bi bi-send-fill"></i> Submit Message
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
