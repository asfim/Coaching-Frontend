@extends('layouts.public')

@section('title', 'Notice Board - EduManage Pro')

@section('styles')
<style>
.container{max-width:1320px;margin:0 auto;padding:0 24px;}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}@media(max-width:900px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr 1fr;}}@media(max-width:576px){.grid-2,.grid-3,.grid-4{grid-template-columns:1fr;}}
</style>
@endsection
@section('content')
<div class="page-banner">
  <div class="container"><h1>Notice Board</h1><p>Stay updated with the latest announcements and news</p>
    <div class="breadcrumb"><a href="{{ route('home') }}">Home</a><span>/</span><span>Notice</span></div>
  </div>
</div>

<section class="section-padding">
  <div class="container">
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:28px;">
      <button class="btn btn-primary btn-sm">All Notices</button>
      <button class="btn btn-outline btn-sm" style="border-color:var(--danger);color:var(--danger);">Urgent</button>
      <button class="btn btn-outline btn-sm" style="border-color:var(--warning);color:var(--warning);">Exam</button>
      <button class="btn btn-outline btn-sm" style="border-color:var(--success);color:var(--success);">General</button>
      <button class="btn btn-outline btn-sm" style="border-color:var(--info);color:var(--info);">Finance</button>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:20px;">
      <div class="card" style="border-left:4px solid var(--danger);">
        <div class="card-body">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <span class="badge badge-danger">Exam</span>
            <span style="font-size:.75rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> 27 Sep 2026</span>
          </div>
          <h5 style="font-weight:700;margin-bottom:10px;font-size:1rem;line-height:1.4;">SSC Model Test – Registration Open</h5>
          <p style="font-size:.85rem;color:var(--gray-600);line-height:1.7;margin-bottom:14px;">All Class X students must register for the SSC Model Test by October 5, 2026. Registration fee is ৳100. Bring your student ID card on the exam day.</p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.78rem;color:var(--gray-500);"><i class="bi bi-person-fill"></i> Admin</span>
            <a href="#" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> View Full</a>
          </div>
        </div>
      </div><div class="card" style="border-left:4px solid var(--warning);">
        <div class="card-body">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <span class="badge badge-warning">Finance</span>
            <span style="font-size:.75rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> 25 Sep 2026</span>
          </div>
          <h5 style="font-weight:700;margin-bottom:10px;font-size:1rem;line-height:1.4;">Monthly Fee Payment Deadline – October 2026</h5>
          <p style="font-size:.85rem;color:var(--gray-600);line-height:1.7;margin-bottom:14px;">Please pay your October 2026 monthly fees by October 10, 2026. A late fee of ৳200 will be applied after the deadline.</p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.78rem;color:var(--gray-500);"><i class="bi bi-person-fill"></i> Admin</span>
            <a href="#" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> View Full</a>
          </div>
        </div>
      </div><div class="card" style="border-left:4px solid var(--success);">
        <div class="card-body">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <span class="badge badge-success">Admission</span>
            <span style="font-size:.75rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> 22 Sep 2026</span>
          </div>
          <h5 style="font-weight:700;margin-bottom:10px;font-size:1rem;line-height:1.4;">New HSC Batch Starting October 2026</h5>
          <p style="font-size:.85rem;color:var(--gray-600);line-height:1.7;margin-bottom:14px;">Admissions are now open for the new HSC 2026-2027 Science batch. Limited seats available. Apply at the office or call us.</p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.78rem;color:var(--gray-500);"><i class="bi bi-person-fill"></i> Admin</span>
            <a href="#" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> View Full</a>
          </div>
        </div>
      </div><div class="card" style="border-left:4px solid var(--info);">
        <div class="card-body">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <span class="badge badge-info">Holiday</span>
            <span style="font-size:.75rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> 18 Sep 2026</span>
          </div>
          <h5 style="font-weight:700;margin-bottom:10px;font-size:1rem;line-height:1.4;">Holiday Notice – Eid-ul-Adha</h5>
          <p style="font-size:.85rem;color:var(--gray-600);line-height:1.7;margin-bottom:14px;">The coaching center will remain closed during Eid-ul-Adha holidays from October 18-22, 2026. Classes will resume normally from October 23.</p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.78rem;color:var(--gray-500);"><i class="bi bi-person-fill"></i> Admin</span>
            <a href="#" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> View Full</a>
          </div>
        </div>
      </div><div class="card" style="border-left:4px solid var(--secondary);">
        <div class="card-body">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <span class="badge badge-secondary">Meeting</span>
            <span style="font-size:.75rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> 15 Sep 2026</span>
          </div>
          <h5 style="font-weight:700;margin-bottom:10px;font-size:1rem;line-height:1.4;">Parent-Teacher Meeting – October 20</h5>
          <p style="font-size:.85rem;color:var(--gray-600);line-height:1.7;margin-bottom:14px;">A parent-teacher meeting is scheduled for October 20, 2026 at 10:00 AM in the main auditorium. All parents are requested to attend.</p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.78rem;color:var(--gray-500);"><i class="bi bi-person-fill"></i> Admin</span>
            <a href="#" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> View Full</a>
          </div>
        </div>
      </div><div class="card" style="border-left:4px solid var(--primary);">
        <div class="card-body">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <span class="badge badge-primary">Course</span>
            <span style="font-size:.75rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> 10 Sep 2026</span>
          </div>
          <h5 style="font-weight:700;margin-bottom:10px;font-size:1rem;line-height:1.4;">New IELTS Preparation Course Launch</h5>
          <p style="font-size:.85rem;color:var(--gray-600);line-height:1.7;margin-bottom:14px;">We are launching a premium IELTS preparation course. Early bird discount of 20% for the first 20 enrolled students. Contact us now.</p>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.78rem;color:var(--gray-500);"><i class="bi bi-person-fill"></i> Admin</span>
            <a href="#" class="btn btn-outline btn-sm"><i class="bi bi-eye"></i> View Full</a>
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
