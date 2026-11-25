<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>@yield('title', 'Teacher Portal - EduManage Pro')</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
<link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
@if(!empty($siteSettings['site_favicon']))
<link rel="icon" href="{{ asset($siteSettings['site_favicon']) }}"/>
@endif
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}
@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}
@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
body{background:var(--gray-50);}
.teacher-navbar{background:linear-gradient(135deg,#047857,#059669);position:sticky;top:0;z-index:200;box-shadow:var(--shadow-sm);}
.teacher-nav-inner{max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;height:64px;gap:16px;}
</style>
@yield('styles')
</head>
<body>
<div class="teacher-navbar">
  <div class="teacher-nav-inner">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none;">
      @if(!empty($siteSettings['site_logo']))
        <img src="{{ asset($siteSettings['site_logo']) }}" alt="{{ $siteSettings['coaching_name'] ?? 'EduManage Pro' }}" style="max-height:38px;max-width:180px;object-fit:contain;">
      @else
        <div style="width:36px;height:36px;background:rgba(255,255,255,.2);border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-person-badge"></i>
        </div>
        <div>
          <strong style="font-size:.95rem;display:block;line-height:1.2;">{{ $siteSettings['coaching_name'] ?? 'EduManage Pro' }}</strong>
          <span style="font-size:.7rem;color:rgba(255,255,255,.8);">Teacher Portal</span>
        </div>
      @endif
    </a>
    <nav style="display:flex;gap:6px;margin-left:24px;">
      <a href="{{ route('teacher.dashboard') }}" style="color:rgba(255,255,255,.85);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:600;text-decoration:none;{{ request()->routeIs('teacher.dashboard') ? 'background:rgba(255,255,255,.2);color:#fff;' : '' }}">Dashboard</a>
      @if(auth()->user()?->hasPermission('routine'))
        <a href="{{ route('teacher.routine') }}" style="color:rgba(255,255,255,.85);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:600;text-decoration:none;{{ request()->routeIs('teacher.routine') ? 'background:rgba(255,255,255,.2);color:#fff;' : '' }}">Class Routine</a>
      @endif
      @if(auth()->user()?->hasPermission('attendance'))
        <a href="{{ route('teacher.attendance') }}" style="color:rgba(255,255,255,.85);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:600;text-decoration:none;{{ request()->routeIs('teacher.attendance') ? 'background:rgba(255,255,255,.2);color:#fff;' : '' }}">Take Attendance</a>
      @endif
      <a href="{{ route('notice') }}" style="color:rgba(255,255,255,.85);padding:6px 12px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:600;text-decoration:none;">Notices</a>
    </nav>
    <div style="margin-left:auto;display:flex;align-items:center;gap:12px;">
      <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border-radius:var(--radius);padding:6px 12px;">
        <img src="{{ !empty(auth()->user()?->avatar) ? asset(auth()->user()->avatar) : asset('images/teacher_male_1_1790497096029.jpg') }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid #fff;">
        <div>
          <strong style="display:block;font-size:.82rem;color:#fff;">{{ auth()->user()?->name ?? 'Teacher' }}</strong>
          <span style="font-size:.7rem;color:rgba(255,255,255,.8);">Faculty Member</span>
        </div>
      </div>
      <form action="{{ route('logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.9);cursor:pointer;font-size:.82rem;display:flex;align-items:center;gap:4px;">
          <i class="bi bi-box-arrow-right"></i> Logout
        </button>
      </form>
    </div>
  </div>
</div>

<div style="max-width:1200px;margin:0 auto;padding:28px 24px;">
  @if(session('success'))
    <div style="background:#ecfdf5;border-left:4px solid #059669;padding:12px 16px;border-radius:8px;margin-bottom:16px;color:#065f46;">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div style="background:#fef2f2;border-left:4px solid #dc2626;padding:12px 16px;border-radius:8px;margin-bottom:16px;color:#991b1b;">
      <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    </div>
  @endif
  @yield('content')
</div>

@yield('scripts')
@stack('scripts')
</body>
</html>
