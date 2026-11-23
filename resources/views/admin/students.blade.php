@extends('layouts.admin')

@section('title', 'Student Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Students</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Student Management</h2>
    <p>Manage all student records, admissions, profiles, and academic information</p>
  </div>
  <button class="btn btn-primary" onclick="openAddStudentModal()"><i class="bi bi-person-plus-fill"></i> Add New Student</button>
</div>

<!-- Program Stream Filter Tabs -->
<div style="background:#fff;padding:12px 18px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:20px;box-shadow:var(--shadow-sm);display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
  <span style="font-size:0.85rem;font-weight:700;color:var(--gray-600);margin-right:4px;">
    <i class="bi bi-diagram-3-fill" style="color:var(--primary);"></i> Student Stream:
  </span>
  <a href="{{ route('admin.students') }}" 
     class="btn {{ !request('program_type') ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-people-fill"></i> All Students ({{ $totalStudents }})
  </a>
  <a href="{{ route('admin.students', array_merge(request()->except('page'), ['program_type' => 'Academic'])) }}" 
     class="btn {{ request('program_type') === 'Academic' ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-mortarboard-fill"></i> Academic Class (একাডেমিক - {{ $academicStudentsCount }})
  </a>
  <a href="{{ route('admin.students', array_merge(request()->except('page'), ['program_type' => 'Target'])) }}" 
     class="btn {{ request('program_type') === 'Target' ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-bullseye"></i> Target Batch (টার্গেট ব্যাচ - {{ $targetStudentsCount }})
  </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $students->total() }}</h3>
      <p>Total Students</p>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
    <div class="stat-content">
      <h3>{{ \App\Models\Student::where('status', 'Active')->count() }}</h3>
      <p>Active</p>
    </div>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon orange"><i class="bi bi-person-plus-fill"></i></div>
    <div class="stat-content">
      <h3>{{ \App\Models\Student::whereMonth('created_at', now()->month)->count() }}</h3>
      <p>New This Month</p>
    </div>
  </div>
  <div class="stat-card red">
    <div class="stat-icon red"><i class="bi bi-cash-coin"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format(\App\Models\Fee::sum('due_amount'), 0) }}</h3>
      <p>Total Due Fees</p>
    </div>
  </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.students') }}" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px;margin-bottom:18px;display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end;">
  @if(request('program_type'))
    <input type="hidden" name="program_type" value="{{ request('program_type') }}">
  @endif
  <div class="input-icon-wrap" style="flex:1;min-width:200px;">
    <i class="bi bi-search input-icon"></i>
    <input type="text" name="search" class="form-control" placeholder="Search by name, ID, phone..." value="{{ request('search') }}">
  </div>
  <select name="class_name" class="form-select" style="width:140px;" onchange="this.form.submit()">
    <option value="">All Classes</option>
    @foreach($classes as $cls)
      <option value="{{ $cls->name }}" {{ request('class_name') == $cls->name ? 'selected' : '' }}>{{ $cls->name }}</option>
    @endforeach
  </select>
  <select name="batch_id" class="form-select" style="width:150px;" onchange="this.form.submit()">
    <option value="">All Batches</option>
    @foreach($batches as $b)
      <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->batch_code }})</option>
    @endforeach
  </select>
  <select name="status" class="form-select" style="width:130px;" onchange="this.form.submit()">
    <option value="">All Status</option>
    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
  </select>
  <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
  @if(request()->anyFilled(['search', 'class_name', 'batch_id', 'status']))
    <a href="{{ request('program_type') ? route('admin.students', ['program_type' => request('program_type')]) : route('admin.students') }}" class="btn btn-outline">Reset</a>
  @endif
</form>

<div class="table-wrapper">
  <table class="data-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Stream</th>
        <th>Class</th>
        <th>Batch</th>
        <th>Phone</th>
        <th>Guardian</th>
        <th>Admitted</th>
        <th>Fee Status</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($students as $st)
      <tr>
        <td><strong>#{{ $st->student_id }}</strong></td>
        <td>
          <div class="avatar-cell">
            <img src="{{ asset($st->avatar ?: 'images/student_female_profile_1790497748277.jpg') }}" alt="{{ $st->name }}">
            <div>
              <strong style="color:var(--gray-900);">{{ $st->name }}</strong>
              <div style="font-size:0.75rem;color:var(--gray-500);">{{ $st->email ?: '-' }}</div>
            </div>
          </div>
        </td>
        <td>
          @if($st->program_type === 'Target')
            <span class="badge" style="background:#fef3c7;color:#b45309;border:1px solid #fde68a;font-size:0.75rem;">
              <i class="bi bi-bullseye"></i> Target Batch
            </span>
          @else
            <span class="badge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;font-size:0.75rem;">
              <i class="bi bi-mortarboard"></i> Academic Class
            </span>
          @endif
        </td>
        <td><strong>{{ $st->class_name }}</strong></td>
        <td>
          @if($st->batch)
            <span class="badge badge-primary">{{ $st->batch->batch_code }}</span>
          @else
            <span class="badge badge-secondary">None</span>
          @endif
        </td>
        <td>{{ $st->phone }}</td>
        <td>{{ $st->guardian_name ?: '-' }}</td>
        <td>{{ $st->admission_date ? \Carbon\Carbon::parse($st->admission_date)->format('d M Y') : '-' }}</td>
        <td>
          @php $due = $st->fees->sum('due_amount'); @endphp
          @if($due > 0)
            <span class="badge badge-danger">Due: ৳{{ number_format($due, 0) }}</span>
          @else
            <span class="badge badge-success">Clear</span>
          @endif
        </td>
        <td>
          <span class="badge {{ $st->status === 'Active' ? 'badge-success' : 'badge-warning' }}">{{ $st->status }}</span>
        </td>
        <td>
          <div class="action-btns">
            <a href="{{ route('admin.students.profile', $st->id) }}" class="btn btn-primary btn-icon btn-sm" title="View Profile">
              <i class="bi bi-eye"></i>
            </a>
            <button class="btn btn-outline btn-icon btn-sm" onclick='openEditStudentModal(@json($st))' title="Edit">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.students.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student record?');" style="display:inline;">
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
        <td colspan="10" style="text-align:center;padding:30px;color:var(--gray-500);">
          No students found matching your criteria.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($students->hasPages())
  <div style="margin-top:16px;">
    {{ $students->links() }}
  </div>
@endif

<!-- Add Student Modal -->
<div id="addStudentModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:640px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-person-plus-fill" style="color:var(--primary);"></i> Add New Student</h4>
      <button onclick="document.getElementById('addStudentModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Stream Selector -->
      <div style="margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e2e8f0;">
        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:6px;color:#1e3a8a;">
          <i class="bi bi-diagram-3-fill"></i> Enrollment Stream / Program Type *
        </label>
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="addStreamAcademic" value="Academic" checked onchange="filterStreamOptions('add')">
            <span style="color:#0369a1;"><i class="bi bi-mortarboard"></i> Academic Class (একাডেমিক ক্লাস)</span>
          </label>
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="addStreamTarget" value="Target" onchange="filterStreamOptions('add')">
            <span style="color:#b45309;"><i class="bi bi-bullseye"></i> Target Batch (টার্গেট ব্যাচ)</span>
          </label>
        </div>
      </div>

      <!-- Avatar Upload with Preview -->
      <div style="margin-bottom:16px;background:var(--gray-50);padding:12px 14px;border-radius:var(--radius);border:1px dashed var(--gray-300);">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:6px;color:var(--gray-700);">
          <i class="bi bi-camera-fill" style="color:var(--primary);"></i> Student Photo / Avatar
        </label>
        <div style="display:flex;align-items:center;gap:14px;">
          <div style="width:54px;height:54px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;overflow:hidden;border:2px solid var(--primary-light);flex-shrink:0;">
            <img id="addAvatarPreview" src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:100%;height:100%;object-fit:cover;">
          </div>
          <div style="flex:1;">
            <input type="file" name="avatar" class="form-control" accept="image/*" onchange="previewAvatar(this, 'addAvatarPreview')">
            <span style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">JPG, PNG, WEBP allowed (Max: 5MB). Optional.</span>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Full Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Fatima Begum" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Phone *</label>
          <input type="text" name="phone" class="form-control" placeholder="01700-000000" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Email</label>
          <input type="email" name="email" class="form-control" placeholder="student@example.com">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Blood Group</label>
          <select name="blood_group" class="form-select">
            <option value="">Select Blood Group</option>
            <option value="A+">A+</option><option value="A-">A-</option>
            <option value="B+">B+</option><option value="B-">B-</option>
            <option value="O+">O+</option><option value="O-">O-</option>
            <option value="AB+">AB+</option><option value="AB-">AB-</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Guardian Name</label>
          <input type="text" name="guardian_name" class="form-control" placeholder="Father or Mother's Name">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Guardian Phone</label>
          <input type="text" name="guardian_phone" class="form-control" placeholder="Guardian Phone No">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class / Target Program *</label>
          <select name="class_name" id="addStudentClass" class="form-select" required>
            <option value="">Select Class</option>
            @foreach($classes as $cls)
              <option value="{{ $cls->name }}" data-stream="{{ $cls->class_type ?? 'Academic' }}">{{ $cls->name }} ({{ $cls->class_type ?? 'Academic' }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch</label>
          <select name="batch_id" id="addStudentBatch" class="form-select">
            <option value="">Select Batch (Optional)</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" data-stream="{{ $b->program_type ?? 'Academic' }}">{{ $b->name }} ({{ $b->batch_code }}) [{{ $b->program_type ?? 'Academic' }}]</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Course</label>
          <select name="course_id" class="form-select">
            <option value="">Select Course (Optional)</option>
            @foreach($courses as $c)
              <option value="{{ $c->id }}">{{ $c->title }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Admission Date</label>
          <input type="date" name="admission_date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" class="form-select" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Address</label>
          <input type="text" name="address" class="form-control" placeholder="Residential address">
        </div>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:18px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addStudentModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Student</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Student Modal -->
<div id="editStudentModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:640px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Student</h4>
      <button onclick="document.getElementById('editStudentModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editStudentForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Stream Selector -->
      <div style="margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e2e8f0;">
        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:6px;color:#1e3a8a;">
          <i class="bi bi-diagram-3-fill"></i> Enrollment Stream / Program Type *
        </label>
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="editStreamAcademic" value="Academic" onchange="filterStreamOptions('edit')">
            <span style="color:#0369a1;"><i class="bi bi-mortarboard"></i> Academic Class (একাডেমিক ক্লাস)</span>
          </label>
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="editStreamTarget" value="Target" onchange="filterStreamOptions('edit')">
            <span style="color:#b45309;"><i class="bi bi-bullseye"></i> Target Batch (টার্গেট ব্যাচ)</span>
          </label>
        </div>
      </div>

      <!-- Avatar Change with Preview -->
      <div style="margin-bottom:16px;background:var(--gray-50);padding:12px 14px;border-radius:var(--radius);border:1px dashed var(--gray-300);">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:6px;color:var(--gray-700);">
          <i class="bi bi-camera-fill" style="color:var(--primary);"></i> Change Student Photo
        </label>
        <div style="display:flex;align-items:center;gap:14px;">
          <div style="width:54px;height:54px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;overflow:hidden;border:2px solid var(--primary-light);flex-shrink:0;">
            <img id="editAvatarPreview" src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:100%;height:100%;object-fit:cover;">
          </div>
          <div style="flex:1;">
            <input type="file" name="avatar" class="form-control" accept="image/*" onchange="previewAvatar(this, 'editAvatarPreview')">
            <span style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">Leave empty to keep existing photo.</span>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Full Name *</label>
          <input type="text" name="name" id="editStudentName" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Phone *</label>
          <input type="text" name="phone" id="editStudentPhone" class="form-control" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Email</label>
          <input type="email" name="email" id="editStudentEmail" class="form-control">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class / Target Program *</label>
          <select name="class_name" id="editStudentClass" class="form-select" required>
            <option value="">Select Class</option>
            @foreach($classes as $cls)
              <option value="{{ $cls->name }}" data-stream="{{ $cls->class_type ?? 'Academic' }}">{{ $cls->name }} ({{ $cls->class_type ?? 'Academic' }})</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Guardian Name</label>
          <input type="text" name="guardian_name" id="editStudentGuardian" class="form-control">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Guardian Phone</label>
          <input type="text" name="guardian_phone" id="editStudentGuardianPhone" class="form-control">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Batch</label>
          <select name="batch_id" id="editStudentBatch" class="form-select">
            <option value="">Select Batch (Optional)</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" data-stream="{{ $b->program_type ?? 'Academic' }}">{{ $b->name }} ({{ $b->batch_code }}) [{{ $b->program_type ?? 'Academic' }}]</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" id="editStudentStatus" class="form-select" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Completed">Completed</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Address</label>
        <textarea name="address" id="editStudentAddress" class="form-control" rows="2"></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editStudentModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Student</button>
      </div>
    </form>
  </div>
</div>

<script>
function openAddStudentModal() {
  document.getElementById('addStudentModal').style.display = 'flex';
  document.getElementById('addStreamAcademic').checked = true;
  filterStreamOptions('add');
}

function filterStreamOptions(prefix) {
  const isTarget = document.getElementById(prefix + 'StreamTarget').checked;
  const currentStream = isTarget ? 'Target' : 'Academic';
  const classSelect = document.getElementById(prefix + 'StudentClass');
  const batchSelect = document.getElementById(prefix + 'StudentBatch');

  let hasVisibleClassSelected = false;
  Array.from(classSelect.options).forEach(opt => {
    if (!opt.value) { opt.hidden = false; return; }
    const stream = opt.getAttribute('data-stream');
    if (!stream || stream === currentStream) {
      opt.hidden = false;
      if (opt.value === classSelect.value) hasVisibleClassSelected = true;
    } else {
      opt.hidden = true;
    }
  });

  if (!hasVisibleClassSelected) {
    const firstVis = Array.from(classSelect.options).find(o => o.value && !o.hidden);
    classSelect.value = firstVis ? firstVis.value : '';
  }

  let hasVisibleBatchSelected = false;
  Array.from(batchSelect.options).forEach(opt => {
    if (!opt.value) { opt.hidden = false; return; }
    const stream = opt.getAttribute('data-stream');
    if (!stream || stream === currentStream) {
      opt.hidden = false;
      if (opt.value == batchSelect.value) hasVisibleBatchSelected = true;
    } else {
      opt.hidden = true;
    }
  });

  if (!hasVisibleBatchSelected) {
    batchSelect.value = '';
  }
}

function previewAvatar(input, imgId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById(imgId).src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function openEditStudentModal(st) {
  document.getElementById('editStudentModal').style.display = 'flex';
  document.getElementById('editStudentForm').action = '/admin/students/' + st.id;
  document.getElementById('editStudentName').value = st.name || '';
  document.getElementById('editStudentPhone').value = st.phone || '';
  document.getElementById('editStudentEmail').value = st.email || '';
  document.getElementById('editStudentGuardian').value = st.guardian_name || '';
  document.getElementById('editStudentGuardianPhone').value = st.guardian_phone || '';
  document.getElementById('editStudentStatus').value = st.status || 'Active';
  document.getElementById('editStudentAddress').value = st.address || '';

  // Stream radio
  const pType = st.program_type || 'Academic';
  if (pType === 'Target') {
    document.getElementById('editStreamTarget').checked = true;
  } else {
    document.getElementById('editStreamAcademic').checked = true;
  }
  filterStreamOptions('edit');

  // Set class and batch after filtering
  document.getElementById('editStudentClass').value = st.class_name || '';
  document.getElementById('editStudentBatch').value = st.batch_id || '';

  // Avatar preview
  const defaultImg = "{{ asset('images/student_female_profile_1790497748277.jpg') }}";
  if (st.avatar) {
    document.getElementById('editAvatarPreview').src = st.avatar.startsWith('http') ? st.avatar : ('/' + st.avatar);
  } else {
    document.getElementById('editAvatarPreview').src = defaultImg;
  }
}

document.addEventListener('DOMContentLoaded', function() {
  filterStreamOptions('add');
});
</script>
@endsection
