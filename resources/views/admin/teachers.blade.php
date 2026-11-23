@extends('layouts.admin')

@section('title', 'Teacher Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Teachers</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Teacher Management</h2>
    <p>Manage faculty members, assignments, subjects, salary, and contact profiles</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addTeacherModal').style.display='flex'"><i class="bi bi-person-plus-fill"></i> Add Teacher</button>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $teachers->total() }}</h3>
      <p>Total Teachers</p>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
    <div class="stat-content">
      <h3>{{ \App\Models\Teacher::where('status', 'Active')->count() }}</h3>
      <p>Active Faculty</p>
    </div>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon orange"><i class="bi bi-journal-bookmark-fill"></i></div>
    <div class="stat-content">
      <h3>{{ \App\Models\Teacher::distinct('subject')->count('subject') }}</h3>
      <p>Distinct Subjects</p>
    </div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-collection-fill"></i></div>
    <div class="stat-content">
      <h3>{{ \App\Models\Batch::count() }}</h3>
      <p>Batches</p>
    </div>
  </div>
</div>

<!-- Search & Filters -->
<form method="GET" action="{{ route('admin.teachers') }}" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px;margin-bottom:16px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
  <div class="input-icon-wrap" style="flex:1;min-width:200px;">
    <i class="bi bi-search input-icon"></i>
    <input type="text" name="search" class="form-control" placeholder="Search teacher name, subject, phone..." value="{{ request('search') }}">
  </div>
  <input type="text" name="subject" class="form-control" style="width:160px;" placeholder="Filter Subject..." value="{{ request('subject') }}">
  <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
  @if(request()->anyFilled(['search', 'subject']))
    <a href="{{ route('admin.teachers') }}" class="btn btn-outline">Reset</a>
  @endif
</form>

<div class="table-wrapper">
  <table class="data-table">
    <thead>
      <tr>
        <th>Teacher</th>
        <th>Subject</th>
        <th>Qualification</th>
        <th>Phone</th>
        <th>Salary</th>
        <th>Join Date</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($teachers as $t)
      <tr>
        <td>
          <div class="avatar-cell">
            <img src="{{ asset($t->image ?: 'images/teacher_male_1_1790497096029.jpg') }}" alt="{{ $t->name }}">
            <div>
              <strong>{{ $t->name }}</strong>
              @if($t->email)
                <div style="color:var(--gray-500);font-size:0.75rem;">{{ $t->email }}</div>
              @endif
              @if($t->designation)
                <span class="badge badge-primary" style="font-size:0.7rem;padding:2px 6px;">{{ $t->designation }}</span>
              @endif
            </div>
          </div>
        </td>
        <td><strong>{{ $t->subject }}</strong></td>
        <td>{{ $t->qualification ?: '-' }}</td>
        <td>{{ $t->phone ?: '-' }}</td>
        <td>৳{{ number_format($t->salary ?: 0, 2) }}</td>
        <td>{{ $t->join_date ? \Carbon\Carbon::parse($t->join_date)->format('d M Y') : '-' }}</td>
        <td>
          <span class="badge {{ $t->status === 'Active' ? 'badge-success' : 'badge-danger' }}">{{ $t->status }}</span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-outline btn-icon btn-sm" onclick='openEditTeacherModal(@json($t))' title="Edit">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.teachers.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this teacher?');" style="display:inline;">
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
        <td colspan="8" style="text-align:center;padding:30px;color:var(--gray-500);">
          No teachers found. Click "Add Teacher" to add new faculty.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($teachers->hasPages())
  <div style="margin-top:16px;">
    {{ $teachers->links() }}
  </div>
@endif

<!-- Add Teacher Modal -->
<div id="addTeacherModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:580px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-person-plus-fill" style="color:var(--primary);"></i> Add Teacher</h4>
      <button onclick="document.getElementById('addTeacherModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.teachers.store') }}" method="POST">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Full Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Abdul Karim" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject *</label>
          <input type="text" name="subject" class="form-control" placeholder="e.g. Higher Math" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Phone</label>
          <input type="text" name="phone" class="form-control" placeholder="01700-000000">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Email</label>
          <input type="email" name="email" class="form-control" placeholder="teacher@edumanagepro.com">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Designation</label>
          <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Lecturer">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Qualification</label>
          <input type="text" name="qualification" class="form-control" placeholder="e.g. M.Sc. BUET, Ph.D. DU">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Monthly Salary (৳)</label>
          <input type="number" step="0.01" name="salary" class="form-control" placeholder="40000">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Join Date</label>
          <input type="date" name="join_date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Short Bio / Remarks</label>
        <textarea name="bio" class="form-control" rows="2" placeholder="Experience details..."></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addTeacherModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Teacher</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Teacher Modal -->
<div id="editTeacherModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:580px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Teacher</h4>
      <button onclick="document.getElementById('editTeacherModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editTeacherForm" method="POST">
      @csrf
      @method('PUT')
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Full Name *</label>
          <input type="text" name="name" id="editTeacherName" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject *</label>
          <input type="text" name="subject" id="editTeacherSubject" class="form-control" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Phone</label>
          <input type="text" name="phone" id="editTeacherPhone" class="form-control">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Email</label>
          <input type="email" name="email" id="editTeacherEmail" class="form-control">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Designation</label>
          <input type="text" name="designation" id="editTeacherDesignation" class="form-control">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Qualification</label>
          <input type="text" name="qualification" id="editTeacherQual" class="form-control">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Salary (৳)</label>
          <input type="number" step="0.01" name="salary" id="editTeacherSalary" class="form-control">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" id="editTeacherStatus" class="form-select" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editTeacherModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Teacher</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditTeacherModal(t) {
  document.getElementById('editTeacherModal').style.display = 'flex';
  document.getElementById('editTeacherForm').action = '/admin/teachers/' + t.id;
  document.getElementById('editTeacherName').value = t.name || '';
  document.getElementById('editTeacherSubject').value = t.subject || '';
  document.getElementById('editTeacherPhone').value = t.phone || '';
  document.getElementById('editTeacherEmail').value = t.email || '';
  document.getElementById('editTeacherDesignation').value = t.designation || '';
  document.getElementById('editTeacherQual').value = t.qualification || '';
  document.getElementById('editTeacherSalary').value = t.salary || '';
  document.getElementById('editTeacherStatus').value = t.status || 'Active';
}
</script>
@endsection
