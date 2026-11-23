<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>@yield('title', 'Student Dashboard - EduManage Pro')</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
<link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}
@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}
@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
body{background:var(--gray-50);}
</style>
@yield('styles')
</head>
<body>
<div style="background:linear-gradient(135deg,var(--primary),var(--secondary));position:sticky;top:0;z-index:200;box-shadow:var(--shadow-sm);">
  <div style="max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;height:64px;gap:16px;">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none;"><div style="width:36px;height:36px;background:rgba(255,255,255,.2);border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-book-half"></i></div><strong style="font-size:.95rem;">EduManage Pro</strong></a>
    <nav style="display:flex;gap:4px;margin-left:16px;">
      <a href="{{ route('student.dashboard') }}" style="color:rgba(255,255,255,.8);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;{{ request()->routeIs('student.dashboard') ? 'background:rgba(255,255,255,.15);' : '' }}">Dashboard</a>
      <a href="{{ route('admin.students.profile') }}" style="color:rgba(255,255,255,.7);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;">Profile</a>
      <a href="{{ route('admin.routine') }}" style="color:rgba(255,255,255,.7);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;">Routine</a>
      <a href="{{ route('results') }}" style="color:rgba(255,255,255,.7);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;">Results</a>
      <a href="{{ route('notice') }}" style="color:rgba(255,255,255,.7);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;">Notices</a>
    </nav>
    <div style="margin-left:auto;display:flex;align-items:center;gap:10px;">
      <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border-radius:var(--radius);padding:8px 14px;">
        <img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
        <div><strong style="display:block;font-size:.82rem;color:#fff;">Fatima Begum</strong><span style="font-size:.7rem;color:rgba(255,255,255,.7);">#STD-2401</span></div>
      </div>
      <a href="{{ route('home') }}" style="color:rgba(255,255,255,.8);font-size:.82rem;text-decoration:none;"><i class="bi bi-box-arrow-right"></i> Exit</a>
    </div>
  </div>
</div>

<div style="max-width:1200px;margin:0 auto;padding:28px 24px;">
  @yield('content')
</div>

@yield('scripts')
@stack('scripts')
</body>
</html>
