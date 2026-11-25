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

@if(session('success'))
  <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif

@if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;">
    <strong style="display:block;margin-bottom:4px;"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:</strong>
    <ul style="margin:0;padding-left:20px;font-size:0.85rem;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<!-- Batch Selector -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px 20px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
  <form method="GET" action="{{ route('admin.routine') }}" style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
    <label style="font-weight:700;font-size:0.85rem;white-space:nowrap;"><i class="bi bi-collection" style="color:var(--primary);"></i> Select Batch Schedule:</label>
    <select name="batch_id" class="form-select" style="max-width:320px;" onchange="this.form.submit()">
      @forelse($batches as $b)
        <option value="{{ $b->id }}" {{ $selectedBatch && $selectedBatch->id == $b->id ? 'selected' : '' }}>
          {{ $b->name }} ({{ $b->batch_code }}) — {{ $b->class_name }}
        </option>
      @empty
        <option value="">No batches available</option>
      @endforelse
    </select>
  </form>

  <div>
    <button class="btn btn-primary btn-sm" onclick="document.getElementById('addRoutineModal').style.display='flex'">
      <i class="bi bi-plus-lg"></i> Create New Class Slot
    </button>
  </div>
</div>

@if(!$selectedBatch && $batches->isEmpty())
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:40px;text-align:center;color:var(--gray-500);">
    <i class="bi bi-collection" style="font-size:3rem;color:var(--gray-300);display:block;margin-bottom:12px;"></i>
    <h4>No Batches Found</h4>
    <p>Please create a batch first under <a href="{{ route('admin.batches') }}">Batches Management</a> before setting up class routines.</p>
  </div>
@else

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
        <div style="background:var(--gray-50);border:1px solid var(--border);border-radius:var(--radius);padding:12px;display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
          <div style="flex:1;">
            <div style="font-weight:700;font-size:0.92rem;color:var(--gray-900);">{{ $slot->subject }}</div>
            <div style="font-size:0.8rem;color:var(--primary);margin:2px 0;"><i class="bi bi-clock"></i> {{ $slot->start_time }} – {{ $slot->end_time }}</div>
            <div style="font-size:0.78rem;color:var(--gray-600);"><i class="bi bi-person"></i> {{ $slot->teacher?->name ?: 'Teacher Unassigned' }} &nbsp;|&nbsp; <i class="bi bi-geo-alt"></i> {{ $slot->room ?: 'TBD' }}</div>
          </div>
          <div style="display:flex;gap:4px;">
            <button type="button" class="btn btn-outline btn-sm" style="color:var(--primary);padding:2px 6px;border-color:transparent;" onclick='openEditRoutineModal(@json($slot))' title="Edit Slot">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.routine.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Delete this schedule slot?');" style="margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);padding:2px 6px;border-color:transparent;" title="Delete Slot"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:16px;color:var(--gray-400);font-size:0.82rem;">No classes scheduled</div>
        @endforelse
      </div>
    </div>
  @endforeach
</div>
@endif

<!-- Add Routine Slot Modal -->
<div id="addRoutineModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
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
              <option value="{{ $b->id }}" {{ $selectedBatch && $selectedBatch->id == $b->id ? 'selected' : '' }}>
                {{ $b->name }} ({{ $b->batch_code }})
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Day of Week *</label>
          <select name="day" class="form-select" required>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject *</label>
          <input type="text" name="subject" list="subjectOptionsList" class="form-control" placeholder="Select or type subject" required>
          <datalist id="subjectOptionsList">
            @foreach($subjects as $sub)
              <option value="{{ $sub->name }}">{{ $sub->code ? '('.$sub->code.')' : '' }}</option>
            @endforeach
          </datalist>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Teacher / Instructor</label>
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
        <input type="text" name="room" class="form-control" placeholder="e.g. Room 201 / Lab A">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addRoutineModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Schedule Slot</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Routine Slot Modal -->
<div id="editRoutineModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Class Schedule Slot</h4>
      <button onclick="document.getElementById('editRoutineModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editRoutineForm" method="POST">
      @csrf
      @method('PUT')
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch *</label>
          <select name="batch_id" id="editRoutineBatchId" class="form-select" required>
            @foreach($batches as $b)
              <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->batch_code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Day of Week *</label>
          <select name="day" id="editRoutineDay" class="form-select" required>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject *</label>
          <input type="text" name="subject" id="editRoutineSubject" list="subjectOptionsList" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Teacher / Instructor</label>
          <select name="teacher_id" id="editRoutineTeacherId" class="form-select">
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
          <input type="text" name="start_time" id="editRoutineStartTime" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">End Time *</label>
          <input type="text" name="end_time" id="editRoutineEndTime" class="form-control" required>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Room / Classroom</label>
        <input type="text" name="room" id="editRoutineRoom" class="form-control">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editRoutineModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Schedule Slot</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditRoutineModal(slot) {
  document.getElementById('editRoutineModal').style.display = 'flex';
  document.getElementById('editRoutineForm').action = '/admin/routine/' + slot.id;
  document.getElementById('editRoutineBatchId').value = slot.batch_id || '';
  document.getElementById('editRoutineDay').value = slot.day || 'Sunday';
  document.getElementById('editRoutineSubject').value = slot.subject || '';
  document.getElementById('editRoutineTeacherId').value = slot.teacher_id || '';
  document.getElementById('editRoutineStartTime').value = slot.start_time || '';
  document.getElementById('editRoutineEndTime').value = slot.end_time || '';
  document.getElementById('editRoutineRoom').value = slot.room || '';
}
</script>
@endsection
