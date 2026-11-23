@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>teacher management</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Teacher Management</h2><p>Manage teacher profiles, assignments and schedules</p></div>
<button class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Add Teacher</button></div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div><div class="stat-content"><h3>48</h3><p>Total Teachers</p></div></div>
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div><div class="stat-content"><h3>45</h3><p>Active Teachers</p></div></div>
  <div class="stat-card orange"><div class="stat-icon orange"><i class="bi bi-journal-bookmark-fill"></i></div><div class="stat-content"><h3>24</h3><p>Subjects Covered</p></div></div>
  <div class="stat-card purple"><div class="stat-icon purple"><i class="bi bi-collection-fill"></i></div><div class="stat-content"><h3>36</h3><p>Batches Managed</p></div></div>
</div>

<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px;margin-bottom:16px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
  <div class="input-icon-wrap" style="flex:1;min-width:200px;"><i class="bi bi-search input-icon"></i><input type="text" class="form-control" placeholder="Search teacher name, subject..."></div>
  <select class="form-select" style="width:150px;"><option>All Subjects</option><option>Physics</option><option>Math</option><option>Chemistry</option></select>
  <select class="form-select" style="width:140px;"><option>All Status</option><option>Active</option><option>On Leave</option></select>
  <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
</div>

<div class="table-wrapper">
  <table class="data-table">
    <thead><tr><th>Teacher</th><th>Subject</th><th>Qualification</th><th>Phone</th><th>Batches</th><th>Experience</th><th>Salary</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/teacher_female_1_1790497081063.jpg') }}" alt=""><div><strong>Nasrin Sultana</strong><small style="color:var(--gray-500);">nasrin@email.com</small></div></div></td><td>Bangladesh Studies</td><td>M.A. History, DU</td><td>01712-111111</td><td><span class="badge badge-primary">SSC-A</span> <span class="badge badge-success">SSC-B</span></td><td>12 Yrs</td><td>৳35,000</td><td><span class="badge badge-success">Active</span></td><td><div class="action-btns"><button class="btn btn-primary btn-icon btn-sm"><i class="bi bi-eye"></i></button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/teacher_male_1_1790497096029.jpg') }}" alt=""><div><strong>Md. Rafiqul Islam</strong><small style="color:var(--gray-500);">rafiq@email.com</small></div></div></td><td>Physics &amp; Math</td><td>M.Sc. Physics, BUET</td><td>01813-222222</td><td><span class="badge badge-secondary">HSC-A</span></td><td>8 Yrs</td><td>৳42,000</td><td><span class="badge badge-success">Active</span></td><td><div class="action-btns"><button class="btn btn-primary btn-icon btn-sm"><i class="bi bi-eye"></i></button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/teacher_male_2_1790497121228.jpg') }}" alt=""><div><strong>Prof. Abdul Karim</strong><small style="color:var(--gray-500);">karim@email.com</small></div></div></td><td>Higher Mathematics</td><td>Ph.D. Math, DU</td><td>01914-333333</td><td><span class="badge badge-info">HSC-A</span> <span class="badge badge-warning">Admission</span></td><td>18 Yrs</td><td>৳65,000</td><td><span class="badge badge-success">Active</span></td><td><div class="action-btns"><button class="btn btn-primary btn-icon btn-sm"><i class="bi bi-eye"></i></button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/teacher_female_2_1790497140028.jpg') }}" alt=""><div><strong>Farhana Akter</strong><small style="color:var(--gray-500);">farhana@email.com</small></div></div></td><td>Chemistry &amp; Biology</td><td>M.Sc. Chemistry, RU</td><td>01615-444444</td><td><span class="badge badge-success">SSC-C</span></td><td>6 Yrs</td><td>৳30,000</td><td><span class="badge badge-warning">On Leave</span></td><td><div class="action-btns"><button class="btn btn-primary btn-icon btn-sm"><i class="bi bi-eye"></i></button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr>
    </tbody>
  </table>
</div>
@endsection

