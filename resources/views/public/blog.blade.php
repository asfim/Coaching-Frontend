@extends('layouts.public')

@section('title', 'Blog - EduManage Pro')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection
@section('content')
<div class="page-banner">
  <div class="container"><h1>Educational Blog</h1><p>Tips, guides and insights for students, parents and educators</p>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><span>Blog</span></div>
  </div>
</div>

<section class="section-padding">
  <div class="container">
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px;">
      <button class="btn btn-primary btn-sm">All Articles</button>
      <button class="btn btn-outline btn-sm">Study Tips</button>
      <button class="btn btn-outline btn-sm">Exam Prep</button>
      <button class="btn btn-outline btn-sm">Parenting</button>
      <button class="btn btn-outline btn-sm">Career</button>
      <button class="btn btn-outline btn-sm">Technology</button>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:24px;">
      <div class="blog-card">
        <div class="blog-img"><img src="{{ asset('images/students_group_study_1790497179383.jpg') }}" alt="10 Proven Tips to Score GPA 5.0 in SSC 2025"></div>
        <div class="blog-body">
          <span class="blog-category">Study Tips</span>
          <h5>10 Proven Tips to Score GPA 5.0 in SSC 2025</h5>
          <p style="font-size:.84rem;color:var(--gray-500);line-height:1.6;">Achieving GPA 5.0 in SSC requires strategic planning, consistent study habits and smart revision techniques.</p>
          <div class="blog-footer">
            <div class="blog-author"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""><span>Prof. Abdul Karim</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:.75rem;color:var(--gray-500);">
              <span><i class="bi bi-calendar3"></i> Sep 20, 2026</span>
              <span><i class="bi bi-eye"></i> 2,450</span>
            </div>
          </div>
        </div>
      </div><div class="blog-card">
        <div class="blog-img"><img src="{{ asset('images/exam_hall_1790497202709.jpg') }}" alt="How to Prepare for BUET Admission in 6 Months"></div>
        <div class="blog-body">
          <span class="blog-category">Exam Prep</span>
          <h5>How to Prepare for BUET Admission in 6 Months</h5>
          <p style="font-size:.84rem;color:var(--gray-500);line-height:1.6;">A complete 6-month roadmap for BUET admission preparation, covering Physics, Chemistry and Mathematics.</p>
          <div class="blog-footer">
            <div class="blog-author"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""><span>Md. Rafiqul Islam</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:.75rem;color:var(--gray-500);">
              <span><i class="bi bi-calendar3"></i> Sep 15, 2026</span>
              <span><i class="bi bi-eye"></i> 1,820</span>
            </div>
          </div>
        </div>
      </div><div class="blog-card">
        <div class="blog-img"><img src="{{ asset('images/hero_classroom_1790497065510.jpg') }}" alt="How Parents Can Support Children During Exam Season"></div>
        <div class="blog-body">
          <span class="blog-category">Parenting</span>
          <h5>How Parents Can Support Children During Exam Season</h5>
          <p style="font-size:.84rem;color:var(--gray-500);line-height:1.6;">Practical tips for parents to create the right environment and provide emotional support during exams.</p>
          <div class="blog-footer">
            <div class="blog-author"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""><span>Nasrin Sultana</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:.75rem;color:var(--gray-500);">
              <span><i class="bi bi-calendar3"></i> Sep 10, 2026</span>
              <span><i class="bi bi-eye"></i> 1,340</span>
            </div>
          </div>
        </div>
      </div><div class="blog-card">
        <div class="blog-img"><img src="{{ asset('images/about_building_1790497156877.jpg') }}" alt="Top Career Options After HSC in Bangladesh 2026"></div>
        <div class="blog-body">
          <span class="blog-category">Career</span>
          <h5>Top Career Options After HSC in Bangladesh 2026</h5>
          <p style="font-size:.84rem;color:var(--gray-500);line-height:1.6;">A comprehensive guide to university admission options, career pathways and professional courses after HSC.</p>
          <div class="blog-footer">
            <div class="blog-author"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""><span>Farhana Akter</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:.75rem;color:var(--gray-500);">
              <span><i class="bi bi-calendar3"></i> Sep 5, 2026</span>
              <span><i class="bi bi-eye"></i> 3,200</span>
            </div>
          </div>
        </div>
      </div><div class="blog-card">
        <div class="blog-img"><img src="{{ asset('images/student_success_1790497712485.jpg') }}" alt="From Zero to Hero: A Success Story of GPA 5.0"></div>
        <div class="blog-body">
          <span class="blog-category">Motivation</span>
          <h5>From Zero to Hero: A Success Story of GPA 5.0</h5>
          <p style="font-size:.84rem;color:var(--gray-500);line-height:1.6;">Inspiring story of Fatima Begum who went from failing to achieving GPA 5.0 with dedication and coaching.</p>
          <div class="blog-footer">
            <div class="blog-author"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""><span>Admin</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:.75rem;color:var(--gray-500);">
              <span><i class="bi bi-calendar3"></i> Aug 28, 2026</span>
              <span><i class="bi bi-eye"></i> 5,600</span>
            </div>
          </div>
        </div>
      </div><div class="blog-card">
        <div class="blog-img"><img src="{{ asset('images/students_group_study_1790497179383.jpg') }}" alt="How Digital Education is Transforming Coaching in Bangladesh"></div>
        <div class="blog-body">
          <span class="blog-category">Technology</span>
          <h5>How Digital Education is Transforming Coaching in Bangladesh</h5>
          <p style="font-size:.84rem;color:var(--gray-500);line-height:1.6;">The rise of EdTech in Bangladesh is changing how coaching centers operate and students learn.</p>
          <div class="blog-footer">
            <div class="blog-author"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""><span>Admin</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:.75rem;color:var(--gray-500);">
              <span><i class="bi bi-calendar3"></i> Aug 20, 2026</span>
              <span><i class="bi bi-eye"></i> 1,100</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="display:flex;justify-content:center;margin-top:36px;gap:6px;">
      <div class="page-item disabled"><i class="bi bi-chevron-left"></i></div>
      <div class="page-item active">1</div><div class="page-item">2</div><div class="page-item">3</div>
      <div class="page-item"><i class="bi bi-chevron-right"></i></div>
    </div>
  </div>
</section>
@endsection
