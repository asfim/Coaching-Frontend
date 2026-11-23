@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>notice management</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Notice Management</h2><p>Publish and manage notices and announcements</p></div>
<button class="btn btn-primary" onclick="document.getElementById('addNoticeModal').classList.add('open')"><i class="bi bi-plus-circle"></i> Add Notice</button></div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-megaphone-fill"></i></div><div class="stat-content"><h3>48</h3><p>Total Notices</p></div></div>
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-eye-fill"></i></div><div class="stat-content"><h3>35</h3><p>Published</p></div></div>
  <div class="stat-card yellow"><div class="stat-icon yellow"><i class="bi bi-pencil-fill"></i></div><div class="stat-content"><h3>8</h3><p>Drafts</p></div></div>
  <div class="stat-card red"><div class="stat-icon red"><i class="bi bi-exclamation-circle-fill"></i></div><div class="stat-content"><h3>5</h3><p>Urgent</p></div></div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px;">
  <div class="notice-card urgent" style="background:#fff;border-radius:0 var(--radius-lg) var(--radius-lg) 0;padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div class="notice-title">SSC Model Test – Registration Open</div>
      <span class="badge badge-danger">Exam</span>
    </div>
    <p style="font-size:.84rem;color:var(--gray-600);margin-bottom:12px;line-height:1.6;">All Class X students must register for the SSC Model Test by October 5, 2026. Registration fee is ৳100.</p>
    <div class="notice-meta" style="justify-content:space-between;">
      <div style="display:flex;gap:12px;"><i class="bi bi-calendar3"></i> 27 Sep 2026</div>
      <div style="display:flex;gap:8px;">
        <span class="badge badge-success">Published</span>
        <button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button>
      </div>
    </div>
  </div><div class="notice-card " style="background:#fff;border-radius:0 var(--radius-lg) var(--radius-lg) 0;padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div class="notice-title">Monthly Fee Payment Deadline</div>
      <span class="badge badge-warning">Finance</span>
    </div>
    <p style="font-size:.84rem;color:var(--gray-600);margin-bottom:12px;line-height:1.6;">Please pay October 2026 fees by October 10. A late fee of ৳200 will apply after the deadline.</p>
    <div class="notice-meta" style="justify-content:space-between;">
      <div style="display:flex;gap:12px;"><i class="bi bi-calendar3"></i> 25 Sep 2026</div>
      <div style="display:flex;gap:8px;">
        <span class="badge badge-success">Published</span>
        <button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button>
      </div>
    </div>
  </div><div class="notice-card " style="background:#fff;border-radius:0 var(--radius-lg) var(--radius-lg) 0;padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div class="notice-title">New HSC Batch Orientation</div>
      <span class="badge badge-success">Admission</span>
    </div>
    <p style="font-size:.84rem;color:var(--gray-600);margin-bottom:12px;line-height:1.6;">The orientation program for the new HSC 2026-2027 batch will be held on October 15 at 10:00 AM.</p>
    <div class="notice-meta" style="justify-content:space-between;">
      <div style="display:flex;gap:12px;"><i class="bi bi-calendar3"></i> 22 Sep 2026</div>
      <div style="display:flex;gap:8px;">
        <span class="badge badge-success">Published</span>
        <button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button>
      </div>
    </div>
  </div><div class="notice-card " style="background:#fff;border-radius:0 var(--radius-lg) var(--radius-lg) 0;padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div class="notice-title">Eid Holiday Notice</div>
      <span class="badge badge-info">Holiday</span>
    </div>
    <p style="font-size:.84rem;color:var(--gray-600);margin-bottom:12px;line-height:1.6;">The coaching center will remain closed during Eid-ul-Adha holidays from October 18-22, 2026.</p>
    <div class="notice-meta" style="justify-content:space-between;">
      <div style="display:flex;gap:12px;"><i class="bi bi-calendar3"></i> 18 Sep 2026</div>
      <div style="display:flex;gap:8px;">
        <span class="badge badge-success">Published</span>
        <button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button>
      </div>
    </div>
  </div><div class="notice-card " style="background:#fff;border-radius:0 var(--radius-lg) var(--radius-lg) 0;padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div class="notice-title">New Course Launch — IELTS Premium</div>
      <span class="badge badge-secondary">Course</span>
    </div>
    <p style="font-size:.84rem;color:var(--gray-600);margin-bottom:12px;line-height:1.6;">We are launching a new premium IELTS preparation course. Early bird discount of 20% for first 20 students.</p>
    <div class="notice-meta" style="justify-content:space-between;">
      <div style="display:flex;gap:12px;"><i class="bi bi-calendar3"></i> 10 Sep 2026</div>
      <div style="display:flex;gap:8px;">
        <span class="badge badge-gray">Draft</span>
        <button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button>
      </div>
    </div>
  </div>
</div>

<!-- Add Notice Modal -->
<div class="modal-overlay" id="addNoticeModal">
  <div class="modal-box" style="max-width:600px;">
    <div class="modal-header"><h5><i class="bi bi-megaphone-fill" style="color:var(--primary);"></i> Add New Notice</h5><div class="modal-close" onclick="document.getElementById('addNoticeModal').classList.remove('open')"><i class="bi bi-x-lg"></i></div></div>
    <div class="modal-body">
      <div class="form-group"><label class="form-label">Notice Title *</label><input class="form-control" type="text" placeholder="Enter notice title..."></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="form-group"><label class="form-label">Category</label><select class="form-select"><option>General</option><option>Exam</option><option>Finance</option><option>Admission</option><option>Holiday</option><option>Urgent</option></select></div>
        <div class="form-group"><label class="form-label">Audience</label><select class="form-select"><option>All</option><option>Students</option><option>Parents</option><option>Teachers</option></select></div>
      </div>
      <div class="form-group"><label class="form-label">Notice Content *</label><textarea class="form-control" rows="4" placeholder="Enter full notice details..."></textarea></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="form-group"><label class="form-label">Expiry Date</label><input class="form-control" type="date"></div>
        <div class="form-group"><label class="form-label">Attachment</label><input class="form-control" type="file"></div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;"><input type="checkbox" id="sendSMS"><label for="sendSMS" style="font-size:.875rem;font-weight:500;">Send SMS notification</label></div>
    </div>
    <div class="modal-footer"><button class="btn btn-outline" onclick="document.getElementById('addNoticeModal').classList.remove('open')">Cancel</button><button class="btn btn-outline">Save Draft</button><button class="btn btn-primary"><i class="bi bi-check-circle"></i> Publish</button></div>
  </div>
</div>
@endsection

