@extends('layouts.admin')

@section('title', 'Subject Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Subjects</span>
@endsection

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:20px;">
  <div>
    <h2 style="margin:0 0 4px 0;font-weight:800;font-size:1.6rem;">
      @if($selectedClass)
        <i class="bi bi-book-half" style="color:var(--primary);"></i> {{ $selectedClass->name }} - Subjects
      @else
        Subject Management
      @endif
    </h2>
    <p style="margin:0;color:var(--gray-500);font-size:0.9rem;">
      @if($selectedClass)
        Managing curriculum subjects and examination papers specifically for <strong>{{ $selectedClass->name }}</strong>
      @else
        Manage curriculum subjects separated and organized class by class
      @endif
    </p>
  </div>
  <button class="btn btn-primary" onclick="openAddSubjectModal({{ $selectedClass ? $selectedClass->id : 'null' }})">
    <i class="bi bi-plus-circle"></i> Add Subject @if($selectedClass) for {{ $selectedClass->name }} @endif
  </button>
</div>

<!-- Program Stream Tabs -->
<div style="background:#fff;padding:12px 18px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:20px;box-shadow:var(--shadow-sm);display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
  <span style="font-size:0.85rem;font-weight:700;color:var(--gray-600);margin-right:4px;">
    <i class="bi bi-diagram-3-fill" style="color:var(--primary);"></i> Subject Stream:
  </span>
  <a href="{{ route('admin.subjects.index') }}" 
     class="btn {{ !request('program_type') ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-grid-fill"></i> All Subjects ({{ $totalSubjectsCount }})
  </a>
  <a href="{{ route('admin.subjects.index', array_merge(request()->except('page'), ['program_type' => 'Academic'])) }}" 
     class="btn {{ request('program_type') === 'Academic' ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-mortarboard-fill"></i> Academic Class Subjects ({{ $academicSubjectsCount }})
  </a>
  <a href="{{ route('admin.subjects.index', array_merge(request()->except('page'), ['program_type' => 'Target'])) }}" 
     class="btn {{ request('program_type') === 'Target' ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-bullseye"></i> Target Batch Subjects ({{ $targetSubjectsCount }})
  </a>
</div>

<!-- Class Navigation Tabs -->
<div style="background:#fff;padding:12px 16px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:20px;box-shadow:var(--shadow-sm);">
  <div style="font-size:0.75rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">
    <i class="bi bi-funnel-fill"></i> Browse Subjects by Class:
  </div>
  <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;scrollbar-width:thin;">
    <a href="{{ route('admin.subjects.index') }}" 
       class="btn {{ !request('class_id') ? 'btn-primary' : 'btn-outline' }} btn-sm" 
       style="border-radius:20px;padding:6px 16px;white-space:nowrap;font-size:0.85rem;display:inline-flex;align-items:center;gap:6px;">
      <i class="bi bi-grid-fill"></i> All Classes 
      <span class="badge" style="background:rgba(0,0,0,0.12);color:inherit;font-weight:700;padding:2px 7px;">
        {{ $totalSubjectsCount }}
      </span>
    </a>

    @foreach($classes as $c)
    <a href="{{ route('admin.subjects.index', ['class_id' => $c->id]) }}" 
       class="btn {{ request('class_id') == $c->id ? 'btn-primary' : 'btn-outline' }} btn-sm" 
       style="border-radius:20px;padding:6px 16px;white-space:nowrap;font-size:0.85rem;display:inline-flex;align-items:center;gap:6px;">
      <i class="bi bi-book"></i> {{ $c->name }}
      <span class="badge" style="background:rgba(0,0,0,0.12);color:inherit;font-weight:700;padding:2px 7px;">
        {{ $c->subjects_count }}
      </span>
    </a>
    @endforeach
  </div>
</div>

<!-- Filter & Search Toolbar -->
<div style="background:#fff;padding:14px 18px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:24px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;box-shadow:var(--shadow-sm);">
  <form method="GET" action="{{ route('admin.subjects.index') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;flex:1;">
    @if(request('class_id'))
      <input type="hidden" name="class_id" value="{{ request('class_id') }}">
    @endif

    <div style="position:relative;flex:1;min-width:200px;max-width:320px;">
      <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:0.85rem;"></i>
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, code..." style="padding-left:32px;font-size:0.88rem;">
    </div>

    @if(!request('class_id'))
    <div style="min-width:180px;">
      <select name="class_id" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;">
        <option value="">All Classes</option>
        @foreach($classes as $cls)
          <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
            {{ $cls->name }} ({{ $cls->subjects_count }})
          </option>
        @endforeach
      </select>
    </div>
    @endif

    <div style="min-width:130px;">
      <select name="status" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;">
        <option value="">All Statuses</option>
        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>

    @if(request('search') || request('status') || (!request('class_id') && request('class_id') !== null))
      <a href="{{ request('class_id') ? route('admin.subjects.index', ['class_id' => request('class_id')]) : route('admin.subjects.index') }}" class="btn btn-outline btn-sm" style="color:var(--gray-600);padding:6px 12px;">
        <i class="bi bi-x-circle"></i> Clear Filters
      </a>
    @endif
  </form>

  <div>
    <span style="font-size:0.85rem;color:var(--gray-500);">
      Showing <strong>{{ $subjects->total() }}</strong> subject(s)
      @if($selectedClass) in <strong>{{ $selectedClass->name }}</strong> @endif
    </span>
  </div>
</div>

<!-- Subjects Table -->
<div class="table-wrapper" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:24px;">
  <table class="data-table">
    <thead>
      <tr>
        <th>Subject Name</th>
        <th>Stream</th>
        <th>Subject Code</th>
        <th>Assigned Class</th>
        <th style="text-align:center;">Linked Exams</th>
        <th style="text-align:center;">Status</th>
        <th style="text-align:right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($subjects as $sub)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;border-radius:8px;background:{{ $sub->program_type === 'Target' ? '#fef3c7;color:#b45309' : 'var(--primary-light);color:var(--primary)' }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.95rem;flex-shrink:0;">
              <i class="bi {{ $sub->program_type === 'Target' ? 'bi-bullseye' : 'bi-journal-bookmark-fill' }}"></i>
            </div>
            <div>
              <strong style="color:var(--gray-900);font-size:0.92rem;">{{ $sub->name }}</strong>
              @if($sub->description)
                <div style="font-size:0.75rem;color:var(--gray-500);margin-top:2px;">{{ Str::limit($sub->description, 65) }}</div>
              @endif
            </div>
          </div>
        </td>
        <td>
          @if($sub->program_type === 'Target')
            <span class="badge" style="background:#fef3c7;color:#b45309;border:1px solid #fde68a;font-size:0.75rem;">
              <i class="bi bi-bullseye"></i> Target Batch
            </span>
          @else
            <span class="badge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;font-size:0.75rem;">
              <i class="bi bi-mortarboard"></i> Academic Class
            </span>
          @endif
        </td>
        <td>
          <code style="font-size:0.82rem;background:#f1f5f9;padding:3px 8px;border-radius:4px;color:#1e3a8a;font-weight:700;border:1px solid #e2e8f0;">
            {{ $sub->code }}
          </code>
        </td>
        <td>
          @if($sub->academicClass)
            <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-weight:600;padding:4px 9px;border:1px solid #bfdbfe;">
              <i class="bi bi-book"></i> {{ $sub->academicClass->name }}
            </span>
          @else
            <span class="badge badge-warning">Unassigned</span>
          @endif
        </td>
        <td style="text-align:center;">
          <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;padding:3px 8px;">
            {{ $sub->exams_count }}
          </span>
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
        <td colspan="6" style="text-align:center;padding:44px 20px;color:var(--gray-500);">
          <i class="bi bi-journal-x" style="font-size:2.8rem;color:var(--gray-300);display:block;margin-bottom:12px;"></i>
          <h5 style="color:var(--gray-700);margin-bottom:6px;">
            No subjects found @if($selectedClass) for {{ $selectedClass->name }} @endif
          </h5>
          <p style="font-size:0.88rem;margin-bottom:16px;">
            Add curriculum subjects specifically for this class.
          </p>
          <button class="btn btn-primary btn-sm" onclick="openAddSubjectModal({{ $selectedClass ? $selectedClass->id : 'null' }})">
            <i class="bi bi-plus-circle"></i> Add Subject @if($selectedClass) for {{ $selectedClass->name }} @endif
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
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:520px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-plus-circle" style="color:var(--primary);"></i> Add Class Subject</h4>
      <button onclick="document.getElementById('addSubjectModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.subjects.store') }}" method="POST">
      @csrf
      
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Program Stream *</label>
        <select name="program_type" id="addSubjectProgramType" class="form-select" required>
          <option value="Academic">Academic Class Subject (একাডেমিক বিষয়)</option>
          <option value="Target">Target Batch Subject (টার্গেট ব্যাচ বিষয়)</option>
        </select>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Assigned Class / Program *</label>
        <select name="class_id" id="addClassSelect" class="form-select" required>
          <option value="">-- Select Class --</option>
          @foreach($classes as $cls)
            <option value="{{ $cls->id }}" {{ (request('class_id') == $cls->id) ? 'selected' : '' }}>
              {{ $cls->name }} ({{ $cls->code ?: 'N/A' }}) [{{ $cls->class_type ?? 'Academic' }}]
            </option>
          @endforeach
        </select>
        <small style="color:var(--gray-500);font-size:0.75rem;">Subject will be specifically assigned to this academic class or target program.</small>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject Name *</label>
        <input type="text" name="name" id="addSubjectName" class="form-control" placeholder="e.g. Higher Mathematics, Physics 1st Paper" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Subject Code *</label>
          <input type="text" name="code" id="addSubjectCode" class="form-control" placeholder="e.g. C10-PHY, HMATH-101" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" class="form-select" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description / Curriculum Note</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Brief outline of syllabus chapters, textbooks..."></textarea>
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
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:520px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Class Subject</h4>
      <button onclick="document.getElementById('editSubjectModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editSubjectForm" method="POST">
      @csrf
      @method('PUT')

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Program Stream *</label>
        <select name="program_type" id="editSubProgramType" class="form-select" required>
          <option value="Academic">Academic Class Subject (একাডেমিক বিষয়)</option>
          <option value="Target">Target Batch Subject (টার্গেট ব্যাচ বিষয়)</option>
        </select>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Assigned Class / Program *</label>
        <select name="class_id" id="editSubClass" class="form-select" required>
          <option value="">-- Select Class --</option>
          @foreach($classes as $cls)
            <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->code ?: 'N/A' }}) [{{ $cls->class_type ?? 'Academic' }}]</option>
          @endforeach
        </select>
      </div>

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
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" id="editSubStatus" class="form-select" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
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
function openAddSubjectModal(preselectedClassId = null) {
  document.getElementById('addSubjectModal').style.display = 'flex';
  if (preselectedClassId) {
    document.getElementById('addClassSelect').value = preselectedClassId;
  }
}

function openEditSubjectModal(sub) {
  document.getElementById('editSubjectModal').style.display = 'flex';
  document.getElementById('editSubjectForm').action = '/admin/subjects/' + sub.id;
  document.getElementById('editSubProgramType').value = sub.program_type || 'Academic';
  document.getElementById('editSubName').value = sub.name || '';
  document.getElementById('editSubCode').value = sub.code || '';
  document.getElementById('editSubClass').value = sub.class_id || '';
  document.getElementById('editSubStatus').value = sub.status || 'Active';
  document.getElementById('editSubDesc').value = sub.description || '';
}
</script>
@endsection
