@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Overview</span>
@endsection

@section('content')
<div class="page-header">
  <div><h2>Student Management</h2><p>Manage all student records, admissions and information</p></div>
  <button class="btn btn-primary" onclick="document.getElementById('addStudentModal').classList.add('open')"><i class="bi bi-person-plus-fill"></i> Add New Student</button>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-people-fill"></i></div><div class="stat-content"><h3>5,243</h3><p>Total Students</p></div></div>
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div><div class="stat-content"><h3>5,012</h3><p>Active</p></div></div>
  <div class="stat-card orange"><div class="stat-icon orange"><i class="bi bi-person-plus-fill"></i></div><div class="stat-content"><h3>48</h3><p>New This Month</p></div></div>
  <div class="stat-card red"><div class="stat-icon red"><i class="bi bi-cash-coin"></i></div><div class="stat-content"><h3>৳1.2L</h3><p>Total Due Fees</p></div></div>
</div>

<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px;margin-bottom:18px;display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end;">
  <div class="input-icon-wrap" style="flex:1;min-width:200px;"><i class="bi bi-search input-icon"></i><input type="text" class="form-control" placeholder="Search by name, ID, phone..."></div>
  <select class="form-select" style="width:140px;"><option>All Classes</option><option>Class IX</option><option>Class X</option><option>Class XI</option><option>Class XII</option></select>
  <select class="form-select" style="width:140px;"><option>All Batches</option><option>SSC-A</option><option>SSC-B</option><option>HSC-A</option></select>
  <select class="form-select" style="width:130px;"><option>All Status</option><option>Active</option><option>Inactive</option></select>
  <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
  <button class="btn btn-outline"><i class="bi bi-download"></i> Export</button>
</div>

<div class="alert alert-info" style="margin-bottom:16px;"><i class="bi bi-info-circle-fill"></i> Showing 248 students. 3 students have overdue fees. <a href="{{ route('admin.fees') }}" style="color:var(--info);font-weight:600;">View Fee Report &rarr;</a></div>

<div class="table-wrapper">
  <table class="data-table">
    <thead><tr><th><input type="checkbox" style="width:16px;height:16px;"></th><th>ID</th><th>Name</th><th>Class</th><th>Batch</th><th>Phone</th><th>Guardian</th><th>Admitted</th><th>Fee</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <tr><td><input type="checkbox" style="width:16px;height:16px;"></td><td><strong>#STD-2401</strong></td><td><div class="avatar-cell"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" alt=""><div><strong>Fatima Begum</strong></div></div></td><td>Class X</td><td><span class="badge badge-primary">SSC-A</span></td><td>01712-345678</td><td>Karim Hossain</td><td>27 Sep 2026</td><td><span class="badge badge-success">Paid</span></td><td><span class="badge badge-success">Active</span></td><td><div class="action-btns"><a href="{{ route('admin.students.profile') }}" class="btn btn-primary btn-icon btn-sm" title="View"><i class="bi bi-eye"></i></a><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr><tr><td><input type="checkbox" style="width:16px;height:16px;"></td><td><strong>#STD-2402</strong></td><td><div class="avatar-cell"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" alt=""><div><strong>Rakibul Hasan</strong></div></div></td><td>Class XI</td><td><span class="badge badge-secondary">HSC-B</span></td><td>01813-456789</td><td>Jamal Hasan</td><td>25 Sep 2026</td><td><span class="badge badge-warning">Partial</span></td><td><span class="badge badge-success">Active</span></td><td><div class="action-btns"><a href="{{ route('admin.students.profile') }}" class="btn btn-primary btn-icon btn-sm" title="View"><i class="bi bi-eye"></i></a><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr><tr><td><input type="checkbox" style="width:16px;height:16px;"></td><td><strong>#STD-2403</strong></td><td><div class="avatar-cell"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" alt=""><div><strong>Sumaiya Islam</strong></div></div></td><td>Class IX</td><td><span class="badge badge-success">SSC-C</span></td><td>01914-567890</td><td>Rahim Islam</td><td>22 Sep 2026</td><td><span class="badge badge-danger">Due</span></td><td><span class="badge badge-warning">Pending</span></td><td><div class="action-btns"><a href="{{ route('admin.students.profile') }}" class="btn btn-primary btn-icon btn-sm" title="View"><i class="bi bi-eye"></i></a><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr><tr><td><input type="checkbox" style="width:16px;height:16px;"></td><td><strong>#STD-2404</strong></td><td><div class="avatar-cell"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" alt=""><div><strong>Mahbub Rahman</strong></div></div></td><td>Class XII</td><td><span class="badge badge-info">HSC-A</span></td><td>01615-678901</td><td>Anwar Rahman</td><td>20 Sep 2026</td><td><span class="badge badge-success">Paid</span></td><td><span class="badge badge-success">Active</span></td><td><div class="action-btns"><a href="{{ route('admin.students.profile') }}" class="btn btn-primary btn-icon btn-sm" title="View"><i class="bi bi-eye"></i></a><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-pencil"></i></button><button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr>
    </tbody>
  </table>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;flex-wrap:wrap;gap:12px;">
  <span style="font-size:.85rem;color:var(--gray-500);">Showing 1–10 of 248 students</span>
  <div style="display:flex;gap:6px;"><div class="page-item disabled"><i class="bi bi-chevron-left"></i></div><div class="page-item active">1</div><div class="page-item">2</div><div class="page-item">3</div><div class="page-item"><i class="bi bi-chevron-right"></i></div></div>
</div>

<div class="modal-overlay" id="addStudentModal">
  <div class="modal-box">
    <div class="modal-header"><h5><i class="bi bi-person-plus-fill" style="color:var(--primary);"></i> Add New Student</h5><div class="modal-close" onclick="document.getElementById('addStudentModal').classList.remove('open')"><i class="bi bi-x-lg"></i></div></div>
    <div class="modal-body">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="form-group"><label class="form-label">First Name *</label><input class="form-control" type="text" placeholder="Enter first name"></div>
        <div class="form-group"><label class="form-label">Last Name *</label><input class="form-control" type="text" placeholder="Enter last name"></div>
        <div class="form-group"><label class="form-label">Phone *</label><input class="form-control" type="tel" placeholder="01700-000000"></div>
        <div class="form-group"><label class="form-label">Guardian Name *</label><input class="form-control" type="text" placeholder="Parent/Guardian name"></div>
        <div class="form-group"><label class="form-label">Class *</label><select class="form-select"><option>Select Class</option><option>Class IX</option><option>Class X</option><option>Class XI</option><option>Class XII</option></select></div>
        <div class="form-group"><label class="form-label">Batch *</label><select class="form-select"><option>Select Batch</option><option>SSC-A</option><option>SSC-B</option><option>HSC-A</option></select></div>
      </div>
      <div class="form-group"><label class="form-label">Address</label><input class="form-control" type="text" placeholder="Full address"></div>
    </div>
    <div class="modal-footer"><button class="btn btn-outline" onclick="document.getElementById('addStudentModal').classList.remove('open')">Cancel</button><button class="btn btn-primary"><i class="bi bi-check-circle"></i> Add Student</button></div>
  </div>
</div>
@endsection

