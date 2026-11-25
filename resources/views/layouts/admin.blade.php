<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>@yield('title', 'EduManage Pro Admin')</title>
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
body{overflow-x:hidden;}
.main-content{margin-left:260px;display:flex;flex-direction:column;min-height:100vh;}
@media(max-width:1024px){.main-content{margin-left:0;}}
</style>
@yield('styles')
</head>
<body>
<div class="sidebar" id="sidebar" style="position:fixed;left:0;top:0;bottom:0;width:260px;background:#fff;border-right:1px solid var(--border);z-index:100;overflow-y:auto;box-shadow:2px 0 20px rgba(0,0,0,.05);">
  <div class="sidebar-brand">
    @if(!empty($siteSettings['site_logo']))
      <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;text-decoration:none;padding:4px 0;">
        <img src="{{ asset($siteSettings['site_logo']) }}" alt="{{ $siteSettings['coaching_name'] ?? 'EduManage Pro' }}" style="max-height:42px;max-width:190px;object-fit:contain;">
      </a>
    @else
      <div class="logo-icon"><i class="bi bi-book-half"></i></div>
      <div class="brand-text"><h6>{{ $siteSettings['coaching_name'] ?? 'EduManage Pro' }}</h6><span>Admin Panel</span></div>
    @endif
  </div>
  <nav class="sidebar-nav">
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.students') }}" class="nav-item {{ request()->routeIs('admin.students*') ? 'active' : '' }}"><i class="bi bi-person-vcard"></i> Students</a>
    <a href="{{ route('admin.teachers') }}" class="nav-item {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Teachers</a>
    <a href="{{ route('admin.courses') }}" class="nav-item {{ request()->routeIs('admin.courses*') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i> Courses</a>
    <a href="{{ route('admin.classes.index') }}" class="nav-item {{ request()->routeIs('admin.classes*') ? 'active' : '' }}"><i class="bi bi-book"></i> Classes</a>
    <a href="{{ route('admin.subjects.index') }}" class="nav-item {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}"><i class="bi bi-journals"></i> Subjects</a>
    <a href="{{ route('admin.batches') }}" class="nav-item {{ request()->routeIs('admin.batches*') ? 'active' : '' }}"><i class="bi bi-collection"></i> Batches</a>
    <a href="{{ route('admin.attendance') }}" class="nav-item {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}"><i class="bi bi-calendar2-check"></i> Attendance</a>
    <a href="{{ route('admin.exams') }}" class="nav-item {{ request()->routeIs('admin.exams*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i> Exams</a>
    <a href="{{ route('admin.results') }}" class="nav-item {{ request()->routeIs('admin.results*') ? 'active' : '' }}"><i class="bi bi-trophy"></i> Results</a>
    <a href="{{ route('admin.routine') }}" class="nav-item {{ request()->routeIs('admin.routine*') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Routine</a>
    <a href="{{ route('admin.fees') }}" class="nav-item {{ request()->routeIs('admin.fees*') ? 'active' : '' }}"><i class="bi bi-cash-coin"></i> Fees</a>
    <a href="{{ route('admin.invoice') }}" class="nav-item {{ request()->routeIs('admin.invoice*') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Invoices</a>
    <!-- Accounts Menu requested by user -->
    <a href="{{ route('admin.accounts.index') }}" class="nav-item {{ request()->routeIs('admin.accounts*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i> Accounts</a>
    <a href="{{ route('admin.notices') }}" class="nav-item {{ request()->routeIs('admin.notices*') ? 'active' : '' }}"><i class="bi bi-megaphone"></i> Notices</a>
    <a href="{{ route('admin.sms') }}" class="nav-item {{ request()->routeIs('admin.sms*') ? 'active' : '' }}"><i class="bi bi-chat-dots"></i> SMS / Notify</a>
    <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="bi bi-gear"></i> Settings</a>
    <div style="margin-top:16px;padding:0 8px;display:flex;flex-direction:column;gap:8px;">
      <a href="{{ route('home') }}" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;"><i class="bi bi-box-arrow-left"></i> Back to Site</a>
      <form action="{{ route('logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;"><i class="bi bi-box-arrow-right"></i> Logout</button>
      </form>
    </div>
  </nav>
</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:99;"></div>

<div class="main-content">
  <div class="topbar">
    <div class="topbar-left">
      <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
      <div class="topbar-search"><i class="bi bi-search"></i><input type="text" placeholder="Search..."></div>
    </div>
    <div class="topbar-right">
      <div class="icon-btn"><i class="bi bi-bell"></i><span class="notif-dot"></span></div>
      <div class="icon-btn"><i class="bi bi-envelope"></i></div>
      <div class="topbar-user">
        <img src="{{ !empty(auth()->user()?->avatar) ? asset(auth()->user()->avatar) : asset('images/teacher_male_2_1790497121228.jpg') }}" alt="User">
        <div class="user-info">
          <strong>{{ auth()->user()?->name ?? 'Administrator' }}</strong>
          <span>{{ ucfirst(auth()->user()?->role ?? 'Admin') }}</span>
        </div>
      </div>
      <form action="{{ route('logout') }}" method="POST" style="margin-left:6px;">
        @csrf
        <button type="submit" class="btn btn-outline btn-sm" title="Sign Out" style="padding:6px 12px;font-size:.8rem;">
          <i class="bi bi-box-arrow-right"></i> Logout
        </button>
      </form>
    </div>
  </div>
  <div class="breadcrumb-bar">
    @yield('breadcrumb')
  </div>
  <div class="page-content">
    @if(session('success'))
      <div style="background:#ecfdf5;border-left:4px solid #059669;padding:12px 16px;border-radius:8px;margin-bottom:16px;color:#065f46;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div><i class="bi bi-check-circle-fill" style="margin-right:8px;color:#059669;font-size:1.1rem;"></i> <strong>Success:</strong> {{ session('success') }}</div>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#065f46;font-size:1.2rem;"><i class="bi bi-x"></i></button>
      </div>
    @endif
    @if(session('error'))
      <div style="background:#fef2f2;border-left:4px solid #dc2626;padding:12px 16px;border-radius:8px;margin-bottom:16px;color:#991b1b;display:flex;align-items:center;justify-content:space-between;">
        <div><i class="bi bi-exclamation-triangle-fill" style="margin-right:8px;color:#dc2626;"></i> <strong>Error:</strong> {{ session('error') }}</div>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#991b1b;font-size:1.2rem;"><i class="bi bi-x"></i></button>
      </div>
    @endif
    @if($errors->any())
      <div style="background:#fef2f2;border-left:4px solid #dc2626;padding:12px 16px;border-radius:8px;margin-bottom:16px;color:#991b1b;">
        <div style="font-weight:600;margin-bottom:4px;"><i class="bi bi-exclamation-circle-fill"></i> Please fix the following errors:</div>
        <ul style="margin:0;padding-left:20px;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </div>
</div>

<script>
function toggleSidebar(){
  const s=document.getElementById('sidebar');
  const o=document.getElementById('sidebarOverlay');
  s.classList.toggle('open');
  if(s.classList.contains('open')){s.style.transform='translateX(0)';o.style.display='block';}
  else{s.style.transform='translateX(-100%)';o.style.display='none';}
}
function closeSidebar(){
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar').style.transform='translateX(-100%)';
  document.getElementById('sidebarOverlay').style.display='none';
}
if(window.innerWidth<=1024){document.getElementById('sidebar').style.transform='translateX(-100%)';}
window.addEventListener('resize',()=>{
  const s=document.getElementById('sidebar');
  if(window.innerWidth>1024){s.style.transform='';s.style.position='fixed';}
  else if(!s.classList.contains('open')){s.style.transform='translateX(-100%)';}
});
</script>
@yield('scripts')
@stack('scripts')
</body>
</html>
