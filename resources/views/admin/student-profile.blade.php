@extends('layouts.admin')

@section('title', ($student->name ?? 'Student Profile') . ' - EduManage Pro')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><a href="{{ route('admin.students') }}">Students</a><i class="bi bi-chevron-right"></i><span>Profile</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Student Academic Profile</h2>
    <p>Detailed performance report, attendance stats, and fee history for {{ $student->name }}</p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="{{ route('admin.students') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Back to Students</a>
    <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Print Profile</button>
  </div>
</div>

<div class="profile-card-top" style="margin-bottom:24px;background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);">
  <div style="display:flex;align-items:flex-start;gap:24px;flex-wrap:wrap;">
    <div class="profile-avatar-wrap">
      <img src="{{ asset($student->avatar ?: 'images/student_female_profile_1790497748277.jpg') }}" class="avatar avatar-2xl avatar-circle" style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--primary-light);">
    </div>
    <div style="flex:1;">
      <h2 class="profile-name" style="margin-bottom:6px;font-weight:800;">{{ $student->name }}</h2>
      <div class="profile-meta" style="display:flex;gap:16px;flex-wrap:wrap;font-size:0.85rem;color:var(--gray-600);margin-bottom:16px;">
        <span><i class="bi bi-card-text"></i> #{{ $student->student_id }}</span>
        <span><i class="bi bi-journal-bookmark"></i> {{ $student->class_name }} — {{ $student->batch?->name ?: 'No Batch' }}</span>
        <span><i class="bi bi-telephone"></i> {{ $student->phone }}</span>
        <span><i class="bi bi-calendar3"></i> Admitted: {{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M, Y') : '-' }}</span>
      </div>
      <div class="profile-info-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;background:var(--gray-50);padding:14px;border-radius:var(--radius);">
        <div class="profile-info-item"><label style="display:block;font-size:0.75rem;color:var(--gray-500);">Guardian</label><strong>{{ $student->guardian_name ?: '-' }}</strong></div>
        <div class="profile-info-item"><label style="display:block;font-size:0.75rem;color:var(--gray-500);">Guardian Phone</label><strong>{{ $student->guardian_phone ?: '-' }}</strong></div>
        <div class="profile-info-item"><label style="display:block;font-size:0.75rem;color:var(--gray-500);">Blood Group</label><strong>{{ $student->blood_group ?: '-' }}</strong></div>
        <div class="profile-info-item"><label style="display:block;font-size:0.75rem;color:var(--gray-500);">Attendance</label><strong>{{ $attRate }}%</strong></div>
        <div class="profile-info-item"><label style="display:block;font-size:0.75rem;color:var(--gray-500);">Fee Due</label><strong style="color:{{ $totalDue > 0 ? 'var(--danger)' : 'var(--success)' }};">৳{{ number_format($totalDue, 0) }}</strong></div>
        <div class="profile-info-item"><label style="display:block;font-size:0.75rem;color:var(--gray-500);">Status</label><strong style="color:var(--success);">{{ $student->status }}</strong></div>
      </div>
    </div>
  </div>
</div>

<!-- Stats Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-calendar2-check"></i></div>
    <div class="stat-content">
      <h3>{{ $attRate }}%</h3>
      <p>Attendance Rate</p>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-trophy-fill"></i></div>
    <div class="stat-content">
      <h3>{{ number_format($avgGpa, 2) }}</h3>
      <p>Average GPA</p>
    </div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-cash-stack"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($totalPaid, 0) }}</h3>
      <p>Total Fees Paid</p>
    </div>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon orange"><i class="bi bi-cash-coin"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($totalDue, 0) }}</h3>
      <p>Outstanding Due</p>
    </div>
  </div>
</div>

<!-- Examination Results Table -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:24px;">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
    <h5 style="margin:0;font-weight:700;">Examination &amp; Test Results</h5>
  </div>
  <div class="table-wrapper">
    <table class="data-table">
      <thead>
        <tr>
          <th>Exam</th>
          <th>Subject</th>
          <th>Obtained Marks</th>
          <th>Total Marks</th>
          <th>GPA</th>
          <th>Grade</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        @forelse($student->results as $res)
        <tr>
          <td><strong>{{ $res->exam?->title ?: 'Model Test' }}</strong></td>
          <td>{{ $res->exam?->subject ?: '-' }}</td>
          <td><strong>{{ $res->obtained_marks }}</strong></td>
          <td>{{ $res->total_marks }}</td>
          <td><strong style="color:var(--primary);">{{ number_format($res->gpa, 2) }}</strong></td>
          <td><span class="badge badge-success">{{ $res->grade }}</span></td>
          <td>{{ $res->remarks ?: '-' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:24px;color:var(--gray-500);">No exam results recorded for this student yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Fee Payment History Table -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
    <h5 style="margin:0;font-weight:700;">Fee Invoices &amp; Payment History</h5>
  </div>
  <div class="table-wrapper">
    <table class="data-table">
      <thead>
        <tr>
          <th>Invoice No</th>
          <th>Month</th>
          <th>Payable</th>
          <th>Paid Amount</th>
          <th>Due Amount</th>
          <th>Payment Date</th>
          <th>Status</th>
          <th>Receipt</th>
        </tr>
      </thead>
      <tbody>
        @forelse($student->fees as $fee)
        <tr>
          <td><code>{{ $fee->invoice_no }}</code></td>
          <td><strong>{{ $fee->month }} {{ $fee->year }}</strong></td>
          <td>৳{{ number_format($fee->amount - $fee->discount, 2) }}</td>
          <td><strong style="color:var(--success);">৳{{ number_format($fee->paid_amount, 2) }}</strong></td>
          <td>৳{{ number_format($fee->due_amount, 2) }}</td>
          <td>{{ $fee->payment_date ? \Carbon\Carbon::parse($fee->payment_date)->format('d M Y') : '-' }}</td>
          <td><span class="badge {{ $fee->status === 'Paid' ? 'badge-success' : 'badge-danger' }}">{{ $fee->status }}</span></td>
          <td>
            <a href="{{ route('admin.invoice', $fee->id) }}" class="btn btn-outline btn-sm"><i class="bi bi-printer"></i> Receipt</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center;padding:24px;color:var(--gray-500);">No fee records found for this student.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
