@extends('layouts.admin')

@section('title', 'Exam Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Exams</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Exam Management</h2>
    <p>Schedule tests, manage exam types, question papers, marks, and hall allocation</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addExamModal').style.display='flex'"><i class="bi bi-plus-circle"></i> Schedule New Exam</button>
</div>

<div class="table-wrapper" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:24px;">
  <table class="data-table">
    <thead>
      <tr>
        <th>Exam Title</th>
        <th>Type</th>
        <th>Batch</th>
        <th>Subject</th>
        <th>Date &amp; Time</th>
        <th>Total / Pass</th>
        <th>Room</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($exams as $e)
      <tr>
        <td><strong>{{ $e->title }}</strong></td>
        <td><span class="badge badge-info">{{ $e->exam_type }}</span></td>
        <td>{{ $e->batch ? $e->batch->name : 'All Batches' }}</td>
        <td>{{ $e->subject }}</td>
        <td>
          <div>{{ \Carbon\Carbon::parse($e->date)->format('d M, Y') }}</div>
          <small style="color:var(--gray-500);">{{ $e->time }}</small>
        </td>
        <td>{{ $e->total_marks }} / {{ $e->pass_marks }}</td>
        <td>{{ $e->room ?: '-' }}</td>
        <td>
          <span class="badge {{ $e->status === 'Completed' ? 'badge-success' : ($e->status === 'Upcoming' ? 'badge-warning' : 'badge-primary') }}">
            {{ $e->status }}
          </span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-outline btn-icon btn-sm" onclick='openEditExamModal(@json($e))' title="Edit">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.exams.destroy', $e->id) }}" method="POST" onsubmit="return confirm('Delete this exam?');" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Delete">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" style="text-align:center;padding:30px;color:var(--gray-500);">
          No exams scheduled. Click "Schedule New Exam" to create one.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($exams->hasPages())
  <div>{{ $exams->links() }}</div>
@endif

<!-- Add Exam Modal -->
<div id="addExamModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-file-earmark-plus" style="color:var(--primary);"></i> Schedule Exam</h4>
      <button onclick="document.getElementById('addExamModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.exams.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Title *</label>
        <input type="text" name="title" class="form-control" placeholder="e.g. SSC Model Test 1 - Physics" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Type *</label>
          <select name="exam_type" class="form-select" required>
            <option value="Model Test">Model Test</option>
            <option value="Monthly Test">Monthly Test</option>
            <option value="Term Final">Term Final</option>
            <option value="Class Test">Class Test</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Target Batch *</label>
          <select name="batch_id" class="form-select" required>
            @foreach($batches as $b)
              <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->batch_code }})</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject *</label>
          <input type="text" name="subject" class="form-control" placeholder="e.g. Physics" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Date *</label>
          <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Time Slot *</label>
          <input type="text" name="time" class="form-control" value="09:00 AM - 12:00 PM" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Room / Hall</label>
          <input type="text" name="room" class="form-control" placeholder="Hall A">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Total Marks *</label>
          <input type="number" name="total_marks" class="form-control" value="100" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Pass Marks *</label>
          <input type="number" name="pass_marks" class="form-control" value="40" required>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" class="form-select" required>
          <option value="Upcoming">Upcoming</option>
          <option value="Ongoing">Ongoing</option>
          <option value="Completed">Completed</option>
        </select>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addExamModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Exam</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Exam Modal -->
<div id="editExamModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Exam</h4>
      <button onclick="document.getElementById('editExamModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editExamForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Title *</label>
        <input type="text" name="title" id="editExamTitle" class="form-control" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Type *</label>
          <select name="exam_type" id="editExamType" class="form-select" required>
            <option value="Model Test">Model Test</option>
            <option value="Monthly Test">Monthly Test</option>
            <option value="Term Final">Term Final</option>
            <option value="Class Test">Class Test</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Target Batch *</label>
          <select name="batch_id" id="editExamBatch" class="form-select" required>
            @foreach($batches as $b)
              <option value="{{ $b->id }}">{{ $b->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject *</label>
          <input type="text" name="subject" id="editExamSubject" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Date *</label>
          <input type="date" name="date" id="editExamDate" class="form-control" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Time Slot *</label>
          <input type="text" name="time" id="editExamTime" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" id="editExamStatus" class="form-select" required>
            <option value="Upcoming">Upcoming</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
          </select>
        </div>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editExamModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Exam</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditExamModal(e) {
  document.getElementById('editExamModal').style.display = 'flex';
  document.getElementById('editExamForm').action = '/admin/exams/' + e.id;
  document.getElementById('editExamTitle').value = e.title || '';
  document.getElementById('editExamType').value = e.exam_type || 'Model Test';
  document.getElementById('editExamBatch').value = e.batch_id || '';
  document.getElementById('editExamSubject').value = e.subject || '';
  document.getElementById('editExamDate').value = e.date ? e.date.substring(0, 10) : '';
  document.getElementById('editExamTime').value = e.time || '';
  document.getElementById('editExamStatus').value = e.status || 'Upcoming';
}
</script>
@endsection
