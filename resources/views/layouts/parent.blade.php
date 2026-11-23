<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>@yield('title', 'Parent Dashboard - EduManage Pro')</title>
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
.pub-dash-header{background:linear-gradient(135deg,var(--primary),var(--secondary));padding:0;position:sticky;top:0;z-index:200;box-shadow:var(--shadow-sm);}
.pub-dash-inner{max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;height:64px;gap:16px;}
</style>
@yield('styles')
</head>
<body>
<div class="pub-dash-header">
  <div class="pub-dash-inner">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none;"><div style="width:36px;height:36px;background:rgba(255,255,255,.2);border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-book-half"></i></div><strong style="font-size:.95rem;">EduManage Pro</strong></a>
    <div style="margin-left:auto;display:flex;align-items:center;gap:12px;">
      <div class="icon-btn" style="background:rgba(255,255,255,.15);color:#fff;border:none;"><i class="bi bi-bell"></i></div>
      <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border-radius:var(--radius);padding:8px 14px;">
        <img src="{{ asset('images/teacher_female_1_1790497081063.jpg') }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
        <div><strong style="display:block;font-size:.82rem;color:#fff;">Shahida Begum</strong><span style="font-size:.7rem;color:rgba(255,255,255,.7);">Parent Portal</span></div>
      </div>
      <a href="{{ route('home') }}" style="color:rgba(255,255,255,.8);font-size:.82rem;text-decoration:none;"><i class="bi bi-box-arrow-right"></i> Logout</a>
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
