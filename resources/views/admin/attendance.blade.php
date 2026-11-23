@extends('layouts.admin')

@section('title', 'Daily Attendance Tracking - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Attendance</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Attendance Management</h2>
    <p>Mark daily student attendance, view stats, and track absenteeism</p>
  </div>
  <div style="display:flex;gap:10px;">
    <button class="btn btn-outline btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Print Sheet</button>
  </div>
</div>

<!-- Attendance Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $presentCount }}</h3>
      <p>Present Today</p>
    </div>
  </div>
  <div class="stat-card red">
    <div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $absentCount }}</h3>
      <p>Absent Today</p>
    </div>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon orange"><i class="bi bi-clock-history"></i></div>
    <div class="stat-content">
      <h3>{{ $lateCount }}</h3>
      <p>Late Arrivals</p>
    </div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-calendar2-range"></i></div>
    <div class="stat-content">
      <h3>{{ $leaveCount }}</h3>
      <p>On Leave</p>
    </div>
  </div>
</div>

<!-- Date and Batch Filter -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px;margin-bottom:20px;">
  <form method="GET" action="{{ route('admin.attendance') }}" style="display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end;">
    <div style="min-width:180px;">
      <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Date</label>
      <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
    </div>
    <div style="min-width:220px;">
      <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Select Batch</label>
      <select name="batch_id" class="form-select" onchange="this.form.submit()">
        @foreach($batches as $b)
          <option value="{{ $b->id }}" {{ $selectedBatch && $selectedBatch->id == $b->id ? 'selected' : '' }}>
            {{ $b->name }} ({{ $b->batch_code }})
          </option>
        @endforeach
      </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Load Sheet</button>
  </form>
</div>

<!-- Mark Attendance Form -->
<form action="{{ route('admin.attendance.mark') }}" method="POST">
  @csrf
  <input type="hidden" name="date" value="{{ $date }}">
  <input type="hidden" name="batch_id" value="{{ $selectedBatch?->id }}">

  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:20px;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:var(--gray-50);">
      <div>
        <h5 style="margin:0;font-weight:700;">
          {{ $selectedBatch ? $selectedBatch->name : 'All Batches' }} — Attendance for {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
        </h5>
        <span style="font-size:0.8rem;color:var(--gray-500);">Total Students: {{ $students->count() }}</span>
      </div>
      <div style="display:flex;gap:8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="markAll('present')"><i class="bi bi-check-all"></i> Mark All Present</button>
      </div>
    </div>

    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Class</th>
            <th>Phone</th>
            <th style="text-align:center;">Attendance Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $st)
            @php
              $currentStatus = isset($attendances[$st->id]) ? $attendances[$st->id]->status : 'present';
            @endphp
            <tr>
              <td><strong>#{{ $st->student_id }}</strong></td>
              <td>
                <div class="avatar-cell">
                  <img src="{{ asset($st->avatar ?: 'images/student_female_profile_1790497748277.jpg') }}" alt="{{ $st->name }}">
                  <div>
                    <strong>{{ $st->name }}</strong>
                    <div style="font-size:0.75rem;color:var(--gray-500);">Guardian: {{ $st->guardian_phone ?: $st->phone }}</div>
                  </div>
                </div>
              </td>
              <td>{{ $st->class_name }}</td>
              <td>{{ $st->phone }}</td>
              <td style="text-align:center;">
                <div style="display:inline-flex;gap:8px;">
                  <label style="display:flex;align-items:center;gap:4px;padding:6px 12px;border-radius:6px;border:1px solid #10b981;cursor:pointer;background:{{ $currentStatus === 'present' ? '#ecfdf5' : '#fff' }};">
                    <input type="radio" name="status[{{ $st->id }}]" value="present" {{ $currentStatus === 'present' ? 'checked' : '' }} class="att-radio-present">
                    <span style="color:#059669;font-weight:600;font-size:0.8rem;">Present</span>
                  </label>
                  <label style="display:flex;align-items:center;gap:4px;padding:6px 12px;border-radius:6px;border:1px solid #ef4444;cursor:pointer;background:{{ $currentStatus === 'absent' ? '#fef2f2' : '#fff' }};">
                    <input type="radio" name="status[{{ $st->id }}]" value="absent" {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                    <span style="color:#dc2626;font-weight:600;font-size:0.8rem;">Absent</span>
                  </label>
                  <label style="display:flex;align-items:center;gap:4px;padding:6px 12px;border-radius:6px;border:1px solid #f59e0b;cursor:pointer;background:{{ $currentStatus === 'late' ? '#fffbeb' : '#fff' }};">
                    <input type="radio" name="status[{{ $st->id }}]" value="late" {{ $currentStatus === 'late' ? 'checked' : '' }}>
                    <span style="color:#d97706;font-weight:600;font-size:0.8rem;">Late</span>
                  </label>
                  <label style="display:flex;align-items:center;gap:4px;padding:6px 12px;border-radius:6px;border:1px solid #3b82f6;cursor:pointer;background:{{ $currentStatus === 'leave' ? '#eff6ff' : '#fff' }};">
                    <input type="radio" name="status[{{ $st->id }}]" value="leave" {{ $currentStatus === 'leave' ? 'checked' : '' }}>
                    <span style="color:#2563eb;font-weight:600;font-size:0.8rem;">Leave</span>
                  </label>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center;padding:30px;color:var(--gray-500);">
                No students enrolled in this batch yet.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($students->count() > 0)
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
      <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check2-circle"></i> Save Attendance Sheet</button>
    </div>
    @endif
  </div>
</form>

<script>
function markAll(status) {
  if (status === 'present') {
    document.querySelectorAll('.att-radio-present').forEach(el => el.checked = true);
  }
}
</script>
@endsection
