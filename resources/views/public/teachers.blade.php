@extends('layouts.public')

@section('title', 'Teachers - EduManage Pro')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection
@section('content')
<div class="page-banner"><div class="container"><h1>Our Expert Teachers</h1><p>Meet the dedicated faculty driving student success</p><div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><span>Teachers</span></div></div></div>

<section class="section-padding">
  <div class="container">
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:32px;display:flex;gap:14px;flex-wrap:wrap;align-items:center;">
      <div class="input-icon-wrap" style="flex:1;min-width:200px;"><i class="bi bi-search input-icon"></i><input type="text" class="form-control" placeholder="Search teachers..."></div>
      <select class="form-select" style="width:160px;"><option>All Subjects</option><option>Physics</option><option>Math</option><option>Chemistry</option></select>
      <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      <div class="teacher-card">
        <div class="teacher-img-wrap"><img src="{{ asset('images/teacher_female_1_1790497081063.jpg') }}" alt="Nasrin Sultana"></div>
        <div class="card-body">
          <h5>Nasrin Sultana</h5><div class="subject">Bangladesh Studies & History</div><div class="qual">M.A. History, University of Dhaka</div>
          <div style="display:flex;justify-content:center;gap:16px;margin:12px 0;font-size:.8rem;color:var(--gray-500);">
            <span><i class="bi bi-people" style="color:var(--primary);"></i> 85</span>
            <span><i class="bi bi-star-fill" style="color:#fbbf24;"></i> 4.9</span>
            <span><i class="bi bi-calendar" style="color:var(--success);"></i> 12 Yrs</span>
          </div>
          <div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a><a href="#"><i class="bi bi-envelope"></i></a></div>
        </div>
      </div><div class="teacher-card">
        <div class="teacher-img-wrap"><img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" alt="Md. Rafiqul Islam"></div>
        <div class="card-body">
          <h5>Md. Rafiqul Islam</h5><div class="subject">Physics & Mathematics</div><div class="qual">M.Sc. Physics, BUET</div>
          <div style="display:flex;justify-content:center;gap:16px;margin:12px 0;font-size:.8rem;color:var(--gray-500);">
            <span><i class="bi bi-people" style="color:var(--primary);"></i> 120</span>
            <span><i class="bi bi-star-fill" style="color:#fbbf24;"></i> 4.8</span>
            <span><i class="bi bi-calendar" style="color:var(--success);"></i> 8 Yrs</span>
          </div>
          <div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a><a href="#"><i class="bi bi-envelope"></i></a></div>
        </div>
      </div><div class="teacher-card">
        <div class="teacher-img-wrap"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt="Prof. Abdul Karim"></div>
        <div class="card-body">
          <h5>Prof. Abdul Karim</h5><div class="subject">Higher Mathematics</div><div class="qual">Ph.D. Mathematics, DU</div>
          <div style="display:flex;justify-content:center;gap:16px;margin:12px 0;font-size:.8rem;color:var(--gray-500);">
            <span><i class="bi bi-people" style="color:var(--primary);"></i> 200</span>
            <span><i class="bi bi-star-fill" style="color:#fbbf24;"></i> 5.0</span>
            <span><i class="bi bi-calendar" style="color:var(--success);"></i> 18 Yrs</span>
          </div>
          <div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a><a href="#"><i class="bi bi-envelope"></i></a></div>
        </div>
      </div><div class="teacher-card">
        <div class="teacher-img-wrap"><img src="{{ asset('images/teacher_female_2_1790497140028.jpg') }}" alt="Farhana Akter"></div>
        <div class="card-body">
          <h5>Farhana Akter</h5><div class="subject">Chemistry & Biology</div><div class="qual">M.Sc. Chemistry, RU</div>
          <div style="display:flex;justify-content:center;gap:16px;margin:12px 0;font-size:.8rem;color:var(--gray-500);">
            <span><i class="bi bi-people" style="color:var(--primary);"></i> 95</span>
            <span><i class="bi bi-star-fill" style="color:#fbbf24;"></i> 4.9</span>
            <span><i class="bi bi-calendar" style="color:var(--success);"></i> 6 Yrs</span>
          </div>
          <div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a><a href="#"><i class="bi bi-envelope"></i></a></div>
        </div>
      </div><div class="teacher-card">
        <div class="teacher-img-wrap"><img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" alt="Sohel Rana"></div>
        <div class="card-body">
          <h5>Sohel Rana</h5><div class="subject">English Language</div><div class="qual">M.A. English Literature, DU</div>
          <div style="display:flex;justify-content:center;gap:16px;margin:12px 0;font-size:.8rem;color:var(--gray-500);">
            <span><i class="bi bi-people" style="color:var(--primary);"></i> 75</span>
            <span><i class="bi bi-star-fill" style="color:#fbbf24;"></i> 4.7</span>
            <span><i class="bi bi-calendar" style="color:var(--success);"></i> 5 Yrs</span>
          </div>
          <div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a><a href="#"><i class="bi bi-envelope"></i></a></div>
        </div>
      </div><div class="teacher-card">
        <div class="teacher-img-wrap"><img src="{{ asset('images/teacher_female_1_1790497081063.jpg') }}" alt="Tahmina Khanam"></div>
        <div class="card-body">
          <h5>Tahmina Khanam</h5><div class="subject">Accounting & Commerce</div><div class="qual">M.Com, Chittagong University</div>
          <div style="display:flex;justify-content:center;gap:16px;margin:12px 0;font-size:.8rem;color:var(--gray-500);">
            <span><i class="bi bi-people" style="color:var(--primary);"></i> 60</span>
            <span><i class="bi bi-star-fill" style="color:#fbbf24;"></i> 4.8</span>
            <span><i class="bi bi-calendar" style="color:var(--success);"></i> 9 Yrs</span>
          </div>
          <div class="teacher-social"><a href="#"><i class="bi bi-facebook"></i></a><a href="#"><i class="bi bi-linkedin"></i></a><a href="#"><i class="bi bi-envelope"></i></a></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-section"><div class="container"><h2 style="margin-bottom:14px;">Join Our Faculty Team</h2><p>We are always looking for passionate educators to join EduManage Pro.</p><div class="cta-actions"><a href="{{ route('contact') }}" class="btn btn-white btn-lg"><i class="bi bi-person-plus"></i> Apply Now</a></div></div></section>
@endsection
