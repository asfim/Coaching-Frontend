@extends('layouts.admin')

@section('title', 'Batch Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Batches</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Batch Management</h2>
    <p>Manage batches, student allotments, class timings, and seat capacity</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addBatchModal').style.display='flex'"><i class="bi bi-plus-circle"></i> Create New Batch</button>
</div>

<!-- Batches Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;margin-bottom:28px;">
  @forelse($batches as $b)
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
      <div>
        <span class="badge badge-primary" style="font-size:0.75rem;padding:4px 8px;margin-bottom:6px;display:inline-block;">{{ $b->batch_code }}</span>
        <h5 style="margin:0;font-weight:700;">{{ $b->name }}</h5>
        <span style="font-size:0.8rem;color:var(--gray-500);">{{ $b->class_name }}</span>
      </div>
      <span class="badge {{ $b->status === 'Active' ? 'badge-success' : 'badge-danger' }}">{{ $b->status }}</span>
    </div>

    <div style="background:var(--gray-50);border-radius:var(--radius);padding:12px;margin-bottom:16px;display:flex;flex-direction:column;gap:6px;font-size:0.82rem;color:var(--gray-700);">
      <div><i class="bi bi-calendar3" style="color:var(--primary);margin-right:6px;"></i> <strong>Days:</strong> {{ $b->days }}</div>
      <div><i class="bi bi-clock" style="color:var(--warning);margin-right:6px;"></i> <strong>Time:</strong> {{ $b->time_slot }}</div>
      <div><i class="bi bi-geo-alt" style="color:var(--danger);margin-right:6px;"></i> <strong>Room:</strong> {{ $b->room }}</div>
      @if($b->course)
        <div><i class="bi bi-journal-bookmark" style="color:var(--success);margin-right:6px;"></i> <strong>Course:</strong> {{ $b->course->title }}</div>
      @endif
    </div>

    <div style="margin-bottom:16px;">
      <div style="display:flex;justify-content:space-between;font-size:0.8rem;margin-bottom:4px;">
        <span>Enrolled Students</span>
        <strong>{{ $b->students_count ?? 0 }} / {{ $b->max_students }}</strong>
      </div>
      @php
        $pct = $b->max_students > 0 ? min(100, round((($b->students_count ?? 0) / $b->max_students) * 100)) : 0;
      @endphp
      <div class="progress" style="height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden;">
        <div class="progress-bar" style="width:{{ $pct }}%;background:var(--primary);height:100%;"></div>
      </div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:12px;border-top:1px solid var(--border);">
      <a href="{{ route('admin.students', ['batch_id' => $b->id]) }}" class="btn btn-outline btn-sm" style="font-size:0.78rem;">
        <i class="bi bi-people"></i> View Students
      </a>
      <div style="display:flex;gap:6px;">
        <button class="btn btn-outline btn-sm" style="padding:4px 8px;" onclick='openEditBatchModal(@json($b))' title="Edit">
          <i class="bi bi-pencil"></i>
        </button>
        <form action="{{ route('admin.batches.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Delete this batch?');" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline btn-sm" style="padding:4px 8px;color:var(--danger);border-color:var(--border);" title="Delete">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div style="grid-column:1/-1;text-align:center;padding:40px;background:#fff;border-radius:var(--radius-lg);border:1px solid var(--border);">
    <p style="color:var(--gray-500);">No batches found. Click "Create New Batch" to add one.</p>
  </div>
  @endforelse
</div>

<!-- Add Batch Modal -->
<div id="addBatchModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-plus-circle" style="color:var(--primary);"></i> Create New Batch</h4>
      <button onclick="document.getElementById('addBatchModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.batches.store') }}" method="POST">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. SSC Batch A" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch Code *</label>
          <input type="text" name="batch_code" class="form-control" placeholder="e.g. SSC-A" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class *</label>
          <select name="class_name" class="form-select" required>
            <option value="Class IX">Class IX</option>
            <option value="Class X" selected>Class X</option>
            <option value="Class XI">Class XI</option>
            <option value="Class XII">Class XII</option>
            <option value="Admission">University Admission</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Course</label>
          <select name="course_id" class="form-select">
            <option value="">Select Associated Course</option>
            @foreach($courses as $c)
              <option value="{{ $c->id }}">{{ $c->title }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Days *</label>
          <input type="text" name="days" class="form-control" placeholder="e.g. Sun, Tue, Thu" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Time Slot *</label>
          <input type="text" name="time_slot" class="form-control" placeholder="e.g. 09:00 AM - 11:00 AM" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Room No. *</label>
          <input type="text" name="room" class="form-control" placeholder="e.g. Room 201" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Max Capacity *</label>
          <input type="number" name="max_students" class="form-control" value="40" required>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addBatchModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Batch</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Batch Modal -->
<div id="editBatchModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Batch</h4>
      <button onclick="document.getElementById('editBatchModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editBatchForm" method="POST">
      @csrf
      @method('PUT')
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch Name *</label>
          <input type="text" name="name" id="editBatchName" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch Code *</label>
          <input type="text" name="batch_code" id="editBatchCode" class="form-control" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class *</label>
          <select name="class_name" id="editBatchClass" class="form-select" required>
            <option value="Class IX">Class IX</option>
            <option value="Class X">Class X</option>
            <option value="Class XI">Class XI</option>
            <option value="Class XII">Class XII</option>
            <option value="Admission">University Admission</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Course</label>
          <select name="course_id" id="editBatchCourse" class="form-select">
            <option value="">Select Associated Course</option>
            @foreach($courses as $c)
              <option value="{{ $c->id }}">{{ $c->title }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Days *</label>
          <input type="text" name="days" id="editBatchDays" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Time Slot *</label>
          <input type="text" name="time_slot" id="editBatchTime" class="form-control" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Room *</label>
          <input type="text" name="room" id="editBatchRoom" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" id="editBatchStatus" class="form-select" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editBatchModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Batch</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditBatchModal(b) {
  document.getElementById('editBatchModal').style.display = 'flex';
  document.getElementById('editBatchForm').action = '/admin/batches/' + b.id;
  document.getElementById('editBatchName').value = b.name || '';
  document.getElementById('editBatchCode').value = b.batch_code || '';
  document.getElementById('editBatchClass').value = b.class_name || 'Class X';
  document.getElementById('editBatchCourse').value = b.course_id || '';
  document.getElementById('editBatchDays').value = b.days || '';
  document.getElementById('editBatchTime').value = b.time_slot || '';
  document.getElementById('editBatchRoom').value = b.room || '';
  document.getElementById('editBatchStatus').value = b.status || 'Active';
}
</script>
@endsection
