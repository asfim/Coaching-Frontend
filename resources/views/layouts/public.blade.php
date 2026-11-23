<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>@yield('title', 'EduManage Pro - Smart Coaching Management System Bangladesh')</title>
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
</style>
@yield('styles')
</head>
<body>
<header class="pub-navbar">
  <div class="container" style="display:flex;align-items:center;height:70px;gap:20px;">
    <a href="{{ route('home') }}" class="logo">
      <div class="logo-icon"><i class="bi bi-book-half"></i></div>
      <div><h6 style="font-family:Outfit,sans-serif;">EduManage <span style="color:var(--primary)">Pro</span></h6><span>Coaching Management</span></div>
    </a>
    <nav class="pub-nav-links" id="pubNavLinks">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
      <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
      <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses*') ? 'active' : '' }}">Courses</a>
      <a href="{{ route('teachers') }}" class="{{ request()->routeIs('teachers*') ? 'active' : '' }}">Teachers</a>
      <a href="{{ route('results') }}" class="{{ request()->routeIs('results*') ? 'active' : '' }}">Results</a>
      <a href="{{ route('notice') }}" class="{{ request()->routeIs('notice*') ? 'active' : '' }}">Notice</a>
      <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a>
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact*') ? 'active' : '' }}">Contact</a>
    </nav>
    <div class="pub-nav-actions">
      @auth
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm"><i class="bi bi-speedometer2"></i> Admin Panel</a>
        @elseif(auth()->user()->role === 'teacher')
          <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-badge"></i> Teacher Portal</a>
        @elseif(auth()->user()->role === 'parent')
          <a href="{{ route('parent.dashboard') }}" class="btn btn-primary btn-sm"><i class="bi bi-people"></i> Parent Portal</a>
        @else
          <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-sm"><i class="bi bi-mortarboard"></i> Student Portal</a>
        @endif
        <form action="{{ route('logout') }}" method="POST" style="display:inline;margin:0;">
          @csrf
          <button type="submit" class="btn btn-outline btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-in-right"></i> Portal Sign In</a>
      @endauth
    </div>
    <button class="pub-hamburger" onclick="document.getElementById('pubNavLinks').classList.toggle('open')"><i class="bi bi-list"></i></button>
  </div>
</header>

@yield('content')

<footer class="site-footer">
  <div class="container" style="display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:40px;">
    <div class="footer-brand">
      <div class="logo-icon"><i class="bi bi-book-half"></i></div>
      <h4>EduManage <span style="color:var(--primary)">Pro</span></h4>
      <p>Bangladesh's comprehensive coaching management system.</p>
      <div class="social-links">
        <a class="social-link" href="#"><i class="bi bi-facebook"></i></a>
        <a class="social-link" href="#"><i class="bi bi-twitter-x"></i></a>
        <a class="social-link" href="#"><i class="bi bi-youtube"></i></a>
        <a class="social-link" href="#"><i class="bi bi-linkedin"></i></a>
      </div>
    </div>
    <div>
      <div class="footer-heading">Quick Links</div>
      <ul class="footer-links">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('courses') }}">Courses</a></li>
        <li><a href="{{ route('teachers') }}">Teachers</a></li>
        <li><a href="{{ route('results') }}">Results</a></li>
        <li><a href="{{ route('notice') }}">Notices</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-heading">Portals</div>
      <ul class="footer-links">
        <li><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
        <li><a href="{{ route('student.dashboard') }}">Student Portal</a></li>
        <li><a href="{{ route('parent.dashboard') }}">Parent Portal</a></li>
        <li><a href="{{ route('admin.attendance') }}">Attendance</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-heading">Contact</div>
      <div class="footer-contact">
        <div class="contact-item"><i class="bi bi-geo-alt-fill"></i><span>House 12, Road 5, Dhanmondi, Dhaka-1205</span></div>
        <div class="contact-item"><i class="bi bi-telephone-fill"></i><span>+880 1700-123456</span></div>
        <div class="contact-item"><i class="bi bi-envelope-fill"></i><span>info@edumanagepro.com.bd</span></div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">&copy; {{ date('Y') }} EduManage Pro. All rights reserved.</div>
  </div>
</footer>

@yield('scripts')
@stack('scripts')
</body>
</html>
