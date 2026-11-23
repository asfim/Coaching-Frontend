@extends('layouts.admin')

@section('title', 'Subject Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Subjects</span>
@endsection

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
  <div>
    <h2 style="margin:0 0 4px 0;font-weight:800;font-size:1.6rem;">Subject Management</h2>
    <p style="margin:0;color:var(--gray-500);font-size:0.9rem;">Configure academic curriculum, subject codes, and class associations</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addSubjectModal').style.display='flex'">
    <i class="bi bi-plus-circle"></i> Add New Subject
  </button>
</div>

<!-- Filter Toolbar -->
<div style="background:#fff;padding:16px 20px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:24px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;box-shadow:var(--shadow-sm);">
  <form method="GET" action="{{ route('admin.subjects.index') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;flex:1;">
    <div style="position:relative;flex:1;min-width:200px;max-width:300px;">
      <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:0.85rem;"></i>
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, code..." style="padding-left:32px;font-size:0.88rem;">
    </div>

    <div style="min-width:180px;">
      <select name="class_id" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;">
        <option value="">All Classes</option>
        @foreach($classes as $cls)
          <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
            {{ $cls->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div style="min-width:140px;">
      <select name="status" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;">
        <option value="">All Statuses</option>
        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>

    @if(request('search') || request('class_id') || request('status'))
      <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline btn-sm" style="color:var(--gray-600);padding:6px 12px;">
        <i class="bi bi-x-circle"></i> Clear Filters
      </a>
    @endif
  </form>

  <div>
    <span style="font-size:0.85rem;color:var(--gray-500);">Total Subjects: <strong>{{ $subjects->total() }}</strong></span>
  </div>
</div>

<!-- Subjects Table -->
<div class="table-wrapper" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:24px;">
  <table class="data-table">
    <thead>
      <tr>
        <th>Subject Name</th>
        <th>Subject Code</th>
        <th>Class / Level</th>
        <th style="text-align:center;">Linked Exams</th>
        <th style="text-align:center;">Status</th>
        <th style="text-align:right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($subjects as $sub)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;border-radius:var(--radius);background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;">
              <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div>
              <strong style="color:var(--gray-900);font-size:0.92rem;">{{ $sub->name }}</strong>
              @if($sub->description)
                <div style="font-size:0.75rem;color:var(--gray-500);margin-top:2px;">{{ Str::limit($sub->description, 60) }}</div>
              @endif
            </div>
          </div>
        </td>
        <td>
          <code style="font-size:0.8rem;background:var(--gray-100);padding:3px 8px;border-radius:4px;color:var(--primary);font-weight:600;">
            {{ $sub->code }}
          </code>
        </td>
        <td>
          @if($sub->academicClass)
            <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-weight:600;">{{ $sub->academicClass->name }}</span>
          @else
            <span style="color:var(--gray-500);font-size:0.85rem;">All Classes</span>
          @endif
        </td>
        <td style="text-align:center;">
          <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;">{{ $sub->exams_count }}</span>
        </td>
        <td style="text-align:center;">
          <span class="badge {{ $sub->status === 'Active' ? 'badge-success' : 'badge-danger' }}">{{ $sub->status }}</span>
        </td>
        <td style="text-align:right;">
          <div class="action-btns" style="justify-content:flex-end;">
            <button class="btn btn-outline btn-icon btn-sm" onclick='openEditSubjectModal(@json($sub))' title="Edit Subject">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.subjects.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Delete subject &quot;{{ $sub->name }}&quot;?');" style="display:inline;margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outline btn-icon btn-sm" style="color:var(--danger);border-color:var(--border);" title="Delete Subject">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" style="text-align:center;padding:40px 20px;color:var(--gray-500);">
          <i class="bi bi-journal-x" style="font-size:2.5rem;color:var(--gray-400);display:block;margin-bottom:10px;"></i>
          <h5 style="color:var(--gray-700);margin-bottom:6px;">No subjects found</h5>
          <p style="font-size:0.88rem;margin-bottom:16px;">Try clearing filters or add your first subject.</p>
          <button class="btn btn-primary btn-sm" onclick="document.getElementById('addSubjectModal').style.display='flex'">
            <i class="bi bi-plus-circle"></i> Add New Subject
          </button>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($subjects->hasPages())
  <div style="margin-bottom:28px;">{{ $subjects->links() }}</div>
@endif

<!-- Add Subject Modal -->
<div id="addSubjectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:500px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-plus-circle" style="color:var(--primary);"></i> Add Subject</h4>
      <button onclick="document.getElementById('addSubjectModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.subjects.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject Name *</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Physics, Higher Mathematics" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject Code *</label>
          <input type="text" name="code" class="form-control" placeholder="e.g. PHY-101" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Academic Class</label>
          <select name="class_id" class="form-select">
            <option value="">-- All Classes (Universal) --</option>
            @foreach($classes as $cls)
              <option value="{{ $cls->id }}">{{ $cls->name }}</option>
            @endforeach
          </select>
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
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description / Curriculum Note</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Brief outline of syllabus, textbooks, etc..."></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addSubjectModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Subject</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Subject Modal -->
<div id="editSubjectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:500px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Subject</h4>
      <button onclick="document.getElementById('editSubjectModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editSubjectForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject Name *</label>
        <input type="text" name="name" id="editSubName" class="form-control" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject Code *</label>
          <input type="text" name="code" id="editSubCode" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Academic Class</label>
          <select name="class_id" id="editSubClass" class="form-select">
            <option value="">-- All Classes (Universal) --</option>
            @foreach($classes as $cls)
              <option value="{{ $cls->id }}">{{ $cls->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" id="editSubStatus" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description / Curriculum Note</label>
        <textarea name="description" id="editSubDesc" class="form-control" rows="2"></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editSubjectModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Subject</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditSubjectModal(sub) {
  document.getElementById('editSubjectModal').style.display = 'flex';
  document.getElementById('editSubjectForm').action = '/admin/subjects/' + sub.id;
  document.getElementById('editSubName').value = sub.name || '';
  document.getElementById('editSubCode').value = sub.code || '';
  document.getElementById('editSubClass').value = sub.class_id || '';
  document.getElementById('editSubStatus').value = sub.status || 'Active';
  document.getElementById('editSubDesc').value = sub.description || '';
}
</script>
@endsection
