@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>exam management</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Exam Management</h2><p>Schedule and manage all examinations</p></div>
<button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Schedule Exam</button></div>

<!-- Tabs -->
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
  <button class="btn btn-primary btn-sm">All Exams</button>
  <button class="btn btn-outline btn-sm" style="border-color:var(--warning);color:var(--warning);">Upcoming (3)</button>
  <button class="btn btn-outline btn-sm" style="border-color:var(--success);color:var(--success);">Running (1)</button>
  <button class="btn btn-outline btn-sm">Completed (12)</button>
</div>

<!-- Summary Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card yellow"><div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div><div class="stat-content"><h3>3</h3><p>Upcoming</p></div></div>
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-play-circle-fill"></i></div><div class="stat-content"><h3>1</h3><p>Running</p></div></div>
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-check-circle-fill"></i></div><div class="stat-content"><h3>12</h3><p>Completed</p></div></div>
  <div class="stat-card purple"><div class="stat-icon purple"><i class="bi bi-people-fill"></i></div><div class="stat-content"><h3>4,820</h3><p>Participants</p></div></div>
</div>

<!-- Running Exam Alert -->
<div class="alert alert-success" style="margin-bottom:16px;"><i class="bi bi-play-circle-fill"></i> <strong>SSC Model Test</strong> is currently running — Exam Hall A — Started 9:00 AM &nbsp; <button class="btn btn-success btn-sm" style="margin-left:8px;">Mark Attendance</button></div>

<!-- Exam Table -->
<div class="table-wrapper">
  <table class="data-table">
    <thead><tr><th>Exam Name</th><th>Class/Batch</th><th>Subjects</th><th>Date</th><th>Time</th><th>Duration</th><th>Total Marks</th><th>Participants</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <tr><td><strong>SSC Model Test</strong></td><td>Class X / SSC-A</td><td>All Subjects</td><td>27 Sep 2026</td><td>9:00 AM</td><td>3 hrs</td><td>600</td><td>85</td><td><span class="badge badge-success" style="animation:pulse 2s infinite;">Running</span></td><td><div class="action-btns"><button class="btn btn-primary btn-sm">Marks Entry</button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-eye"></i></button></div></td></tr>
      <tr><td><strong>Monthly Test — Oct</strong></td><td>Class XI / HSC-A</td><td>Physics, Math</td><td>05 Oct 2026</td><td>2:00 PM</td><td>2 hrs</td><td>200</td><td>120</td><td><span class="badge badge-warning">Upcoming</span></td><td><div class="action-btns"><button class="btn btn-outline btn-sm">Edit</button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr>
      <tr><td><strong>HSC Pre-Test</strong></td><td>Class XII / HSC-B</td><td>All Subjects</td><td>15 Oct 2026</td><td>9:00 AM</td><td>3 hrs</td><td>600</td><td>65</td><td><span class="badge badge-warning">Upcoming</span></td><td><div class="action-btns"><button class="btn btn-outline btn-sm">Edit</button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-trash"></i></button></div></td></tr>
      <tr><td><strong>SSC Trial Exam</strong></td><td>Class X / SSC-B</td><td>All Subjects</td><td>01 Sep 2026</td><td>9:00 AM</td><td>3 hrs</td><td>600</td><td>80</td><td><span class="badge badge-gray">Completed</span></td><td><div class="action-btns"><button class="btn btn-success btn-sm">Publish Result</button><button class="btn btn-outline btn-icon btn-sm"><i class="bi bi-eye"></i></button></div></td></tr>
      <tr><td><strong>Monthly Test — Sep</strong></td><td>Class IX / SSC-C</td><td>Math, Science</td><td>15 Sep 2026</td><td>2:00 PM</td><td>2 hrs</td><td>200</td><td>70</td><td><span class="badge badge-primary">Result Published</span></td><td><div class="action-btns"><button class="btn btn-primary btn-sm">View Result</button></div></td></tr>
    </tbody>
  </table>
</div>
@endsection

