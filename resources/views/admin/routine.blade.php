@extends('layouts.admin')

@section('title', 'Class Routine / Timetable - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Routine</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Class Routine / Timetable</h2>
    <p>Weekly timetable schedule for batches, subjects, and assigned teachers</p>
  </div>
  <div style="display:flex;gap:10px;">
    <button class="btn btn-primary btn-sm" onclick="document.getElementById('addRoutineModal').style.display='flex'"><i class="bi bi-plus-circle"></i> Add Routine Slot</button>
    <button class="btn btn-outline btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
  </div>
</div>

<!-- Batch Selector -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px 20px;margin-bottom:20px;">
  <form method="GET" action="{{ route('admin.routine') }}" style="display:flex;gap:14px;align-items:center;">
    <label style="font-weight:600;font-size:0.85rem;white-space:nowrap;">Select Batch:</label>
    <select name="batch_id" class="form-select" style="max-width:280px;" onchange="this.form.submit()">
      @foreach($batches as $b)
        <option value="{{ $b->id }}" {{ $selectedBatch && $selectedBatch->id == $b->id ? 'selected' : '' }}>
          {{ $b->name }} ({{ $b->batch_code }}) — {{ $b->class_name }}
        </option>
      @endforeach
    </select>
  </form>
</div>

<!-- Routine Timetable Display -->
@php
  $weekDays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;margin-bottom:28px;">
  @foreach($weekDays as $day)
    @php
      $slots = $routines->get($day, collect());
    @endphp
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">
      <div style="background:linear-gradient(135deg,var(--primary-light),#f8fafc);padding:12px 18px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
        <h5 style="margin:0;font-weight:700;color:var(--primary);"><i class="bi bi-calendar-event"></i> {{ $day }}</h5>
        <span class="badge badge-primary" style="font-size:0.75rem;">{{ $slots->count() }} Classes</span>
      </div>
      <div style="padding:14px;display:flex;flex-direction:column;gap:10px;">
        @forelse($slots as $slot)
        <div style="background:var(--gray-50);border:1px solid var(--border);border-radius:var(--radius);padding:12px;display:flex;justify-content:space-between;align-items:flex-start;">
          <div>
            <div style="font-weight:700;font-size:0.92rem;color:var(--gray-900);">{{ $slot->subject }}</div>
            <div style="font-size:0.8rem;color:var(--primary);margin:2px 0;"><i class="bi bi-clock"></i> {{ $slot->start_time }} – {{ $slot->end_time }}</div>
            <div style="font-size:0.78rem;color:var(--gray-600);"><i class="bi bi-person"></i> {{ $slot->teacher?->name ?: 'Teacher Unassigned' }} &nbsp;|&nbsp; <i class="bi bi-geo-alt"></i> {{ $slot->room ?: 'TBD' }}</div>
          </div>
          <form action="{{ route('admin.routine.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Delete this slot?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);padding:2px 6px;border-color:transparent;" title="Delete Slot"><i class="bi bi-trash"></i></button>
          </form>
        </div>
        @empty
        <div style="text-align:center;padding:16px;color:var(--gray-400);font-size:0.82rem;">No classes scheduled</div>
        @endforelse
      </div>
    </div>
  @endforeach
</div>

<!-- Add Routine Slot Modal -->
<div id="addRoutineModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:520px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-clock-history" style="color:var(--primary);"></i> Add Class Schedule Slot</h4>
      <button onclick="document.getElementById('addRoutineModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.routine.store') }}" method="POST">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch *</label>
          <select name="batch_id" class="form-select" required>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" {{ $selectedBatch && $selectedBatch->id == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->batch_code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Day of Week *</label>
          <select name="day" class="form-select" required>
            <option value="Sunday">Sunday</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject *</label>
          <input type="text" name="subject" class="form-control" placeholder="e.g. Higher Math" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Teacher</label>
          <select name="teacher_id" class="form-select">
            <option value="">Select Instructor</option>
            @foreach($teachers as $t)
              <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->subject }})</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Start Time *</label>
          <input type="text" name="start_time" class="form-control" placeholder="e.g. 09:00 AM" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">End Time *</label>
          <input type="text" name="end_time" class="form-control" placeholder="e.g. 10:15 AM" required>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Room / Classroom</label>
        <input type="text" name="room" class="form-control" placeholder="Room 201">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addRoutineModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Schedule</button>
      </div>
    </form>
  </div>
</div>
@endsection
