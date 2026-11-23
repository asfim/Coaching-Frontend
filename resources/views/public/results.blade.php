@extends('layouts.public')

@section('title', 'Results - EduManage Pro')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection
@section('content')
<div class="page-banner">
  <div class="container"><h1>Student Results</h1><p>Check exam results and academic performance</p>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><span>Results</span></div>
  </div>
</div>

<section class="section-padding">
  <div class="container">
    <!-- Search Result -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-xl);padding:32px;max-width:600px;margin:0 auto 48px;box-shadow:var(--shadow-lg);">
      <h4 style="font-weight:700;margin-bottom:20px;text-align:center;">Check Your Result</h4>
      <div class="form-group"><label class="form-label">Student ID</label><input class="form-control" placeholder="Enter your Student ID (e.g. STD-2401)"></div>
      <div class="form-group"><label class="form-label">Exam</label><select class="form-select"><option>Select Exam</option><option>SSC Model Test 2026</option><option>Monthly Test September</option><option>HSC Pre-Test</option></select></div>
      <button class="btn btn-primary" style="width:100%;"><i class="bi bi-search"></i> Search Result</button>
    </div>

    <!-- Achievement Stats -->
    <div style="background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:var(--radius-xl);padding:40px;margin-bottom:48px;">
      <div class="section-header" style="margin-bottom:32px;"><span class="badge-label" style="background:rgba(255,255,255,.2);color:#fff;">SSC 2024 Results</span><h2 style="color:#fff;margin-bottom:8px;">Outstanding Achievements</h2><p style="color:rgba(255,255,255,.8);">Our students achieved exceptional results in SSC 2024 examinations</p></div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:20px;text-align:center;">
        <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:20px;"><h3 style="color:#fff;font-size:2rem;margin-bottom:4px;">1,248</h3><p style="color:rgba(255,255,255,.8);font-size:.85rem;margin:0;">GPA 5.0 Students</p></div>
        <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:20px;"><h3 style="color:#fff;font-size:2rem;margin-bottom:4px;">98.4%</h3><p style="color:rgba(255,255,255,.8);font-size:.85rem;margin:0;">Pass Rate</p></div>
        <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:20px;"><h3 style="color:#fff;font-size:2rem;margin-bottom:4px;">342</h3><p style="color:rgba(255,255,255,.8);font-size:.85rem;margin:0;">Board Positions</p></div>
        <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:20px;"><h3 style="color:#fff;font-size:2rem;margin-bottom:4px;">4.67</h3><p style="color:rgba(255,255,255,.8);font-size:.85rem;margin:0;">Average GPA</p></div>
      </div>
    </div>

    <!-- Top Achievers -->
    <div class="section-header" style="margin-bottom:32px;"><span class="badge-label bg-warning-light text-warning">Top Achievers</span><h2>SSC 2024 — GPA 5.0 Stars</h2><span class="divider-line"></span></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
      <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;box-shadow:var(--shadow-sm);transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <div style="position:relative;display:inline-block;margin-bottom:12px;">
          <img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--warning);">
          <div style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;background:var(--warning);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;">1</div>
        </div>
        <h5 style="font-weight:700;font-size:.9rem;margin-bottom:4px;">Fatima Begum</h5>
        <p style="font-size:.78rem;color:var(--gray-500);margin-bottom:8px;">Roll: 001 — Class X</p>
        <span class="badge badge-success" style="font-size:.82rem;">GPA 5.0</span>
      </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;box-shadow:var(--shadow-sm);transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <div style="position:relative;display:inline-block;margin-bottom:12px;">
          <img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--warning);">
          <div style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;background:var(--warning);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;">2</div>
        </div>
        <h5 style="font-weight:700;font-size:.9rem;margin-bottom:4px;">Rakibul Hasan</h5>
        <p style="font-size:.78rem;color:var(--gray-500);margin-bottom:8px;">Roll: 002 — Class X</p>
        <span class="badge badge-success" style="font-size:.82rem;">GPA 5.0</span>
      </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;box-shadow:var(--shadow-sm);transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <div style="position:relative;display:inline-block;margin-bottom:12px;">
          <img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--warning);">
          <div style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;background:var(--warning);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;">3</div>
        </div>
        <h5 style="font-weight:700;font-size:.9rem;margin-bottom:4px;">Sumaiya Islam</h5>
        <p style="font-size:.78rem;color:var(--gray-500);margin-bottom:8px;">Roll: 003 — Class X</p>
        <span class="badge badge-success" style="font-size:.82rem;">GPA 5.0</span>
      </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;box-shadow:var(--shadow-sm);transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <div style="position:relative;display:inline-block;margin-bottom:12px;">
          <img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--warning);">
          <div style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;background:var(--warning);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;">4</div>
        </div>
        <h5 style="font-weight:700;font-size:.9rem;margin-bottom:4px;">Mahbub Rahman</h5>
        <p style="font-size:.78rem;color:var(--gray-500);margin-bottom:8px;">Roll: 004 — Class X</p>
        <span class="badge badge-success" style="font-size:.82rem;">GPA 5.0</span>
      </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;box-shadow:var(--shadow-sm);transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <div style="position:relative;display:inline-block;margin-bottom:12px;">
          <img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--warning);">
          <div style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;background:var(--warning);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;">5</div>
        </div>
        <h5 style="font-weight:700;font-size:.9rem;margin-bottom:4px;">Nadia Akter</h5>
        <p style="font-size:.78rem;color:var(--gray-500);margin-bottom:8px;">Roll: 005 — Class X</p>
        <span class="badge badge-success" style="font-size:.82rem;">GPA 5.0</span>
      </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;box-shadow:var(--shadow-sm);transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <div style="position:relative;display:inline-block;margin-bottom:12px;">
          <img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--warning);">
          <div style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;background:var(--warning);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;">6</div>
        </div>
        <h5 style="font-weight:700;font-size:.9rem;margin-bottom:4px;">Sazid Khan</h5>
        <p style="font-size:.78rem;color:var(--gray-500);margin-bottom:8px;">Roll: 006 — Class X</p>
        <span class="badge badge-success" style="font-size:.82rem;">GPA 5.0</span>
      </div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="container"><h2 style="margin-bottom:14px;">Check Your Latest Exam Results</h2><p>Log in to your student portal to view your detailed results and performance analytics.</p>
    <div class="cta-actions"><a href="{{ route('student.dashboard') }}" class="btn btn-white btn-lg"><i class="bi bi-person-circle"></i> Student Login</a></div>
  </div>
</section>
@endsection
