@extends('layouts.admin')

@section('title', 'Academic Classes - Admin Panel')

@section('breadcrumb')
<div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
<div class="breadcrumb-item">Classes</div>
@endsection

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
  <div>
    <h3 class="page-title" style="margin:0 0 4px;font-weight:800;">Academic Classes</h3>
    <p style="margin:0;color:var(--gray-500);font-size:.875rem;">Manage classes, levels, and standards for students and batches</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addClassModal').style.display='flex'">
    <i class="bi bi-plus-circle-fill"></i> Add New Class
  </button>
</div>

<!-- Stats Bar -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-building"></i></div>
    <div class="stat-content">
      <h3>{{ $totalClasses }}</h3>
      <p>Total Classes</p>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-check2-circle"></i></div>
    <div class="stat-content">
      <h3>{{ $activeClasses }}</h3>
      <p>Active Classes</p>
    </div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-people-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $totalStudents }}</h3>
      <p>Total Students Enrolled</p>
    </div>
  </div>
</div>

<!-- Filter Bar -->
<div class="card" style="margin-bottom:20px;padding:16px;">
  <form method="GET" action="{{ route('admin.classes.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
    <div style="flex:1;min-width:200px;">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search class name, code, description...">
    </div>
    <div style="width:160px;">
      <select name="status" class="form-select" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
    @if(request()->hasAny(['search', 'status']))
      <a href="{{ route('admin.classes.index') }}" class="btn btn-outline"><i class="bi bi-x-circle"></i> Reset</a>
    @endif
  </form>
</div>

<!-- Classes Table -->
<div class="table-wrapper">
  <table class="data-table">
    <thead>
      <tr>
        <th>Class Name</th>
        <th>Code</th>
        <th>Numeric Level</th>
        <th>Total Batches</th>
        <th>Enrolled Students</th>
        <th>Description</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($classes as $cls)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;border-radius:8px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;">
              <i class="bi bi-book"></i>
            </div>
            <strong>{{ $cls->name }}</strong>
          </div>
        </td>
        <td><span class="badge badge-primary">{{ $cls->code ?: 'N/A' }}</span></td>
        <td><strong>{{ $cls->numeric_level ?: '-' }}</strong></td>
        <td>
          <a href="{{ route('admin.batches') }}" style="color:var(--primary);font-weight:600;text-decoration:none;">
            {{ $cls->batches_count }} Batches
          </a>
        </td>
        <td>
          <a href="{{ route('admin.students', ['class_name' => $cls->name]) }}" style="color:var(--success);font-weight:600;text-decoration:none;">
            {{ $cls->students_count }} Students
          </a>
        </td>
        <td style="color:var(--gray-600);font-size:0.84rem;">
          {{ Str::limit($cls->description, 50) ?: '-' }}
        </td>
        <td>
          <span class="badge {{ $cls->status === 'Active' ? 'badge-success' : 'badge-warning' }}">
            {{ $cls->status }}
          </span>
        </td>
        <td>
          <div class="action-btns">
            <button class="btn btn-outline btn-icon btn-sm" onclick='openEditClassModal(@json($cls))' title="Edit Class">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.classes.destroy', $cls->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this class?');" style="display:inline;">
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
          No academic classes found. Click "Add New Class" to create one.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($classes->hasPages())
  <div style="margin-top:16px;">
    {{ $classes->links() }}
  </div>
@endif

<!-- Add Class Modal -->
<div id="addClassModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-plus-circle-fill" style="color:var(--primary);"></i> Add Academic Class</h4>
      <button onclick="document.getElementById('addClassModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.classes.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class Name *</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Class IX, Class X, HSC 1st Year" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class Code</label>
          <input type="text" name="code" class="form-control" placeholder="e.g. C09, C10, C11, ADM">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Numeric Level</label>
          <input type="number" name="numeric_level" class="form-control" placeholder="e.g. 9, 10, 11, 12">
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
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Curriculum focus, board standard..."></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addClassModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Class</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Class Modal -->
<div id="editClassModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Academic Class</h4>
      <button onclick="document.getElementById('editClassModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editClassForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class Name *</label>
        <input type="text" name="name" id="editClassName" class="form-control" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Class Code</label>
          <input type="text" name="code" id="editClassCode" class="form-control">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Numeric Level</label>
          <input type="number" name="numeric_level" id="editClassLevel" class="form-control">
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" id="editClassStatus" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description</label>
        <textarea name="description" id="editClassDesc" class="form-control" rows="3"></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editClassModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Class</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditClassModal(cls) {
  document.getElementById('editClassModal').style.display = 'flex';
  document.getElementById('editClassForm').action = '/admin/classes/' + cls.id;
  document.getElementById('editClassName').value = cls.name || '';
  document.getElementById('editClassCode').value = cls.code || '';
  document.getElementById('editClassLevel').value = cls.numeric_level || '';
  document.getElementById('editClassStatus').value = cls.status || 'Active';
  document.getElementById('editClassDesc').value = cls.description || '';
}
</script>
@endsection
