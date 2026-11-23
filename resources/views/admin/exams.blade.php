@extends('layouts.admin')

@section('title', 'Exam Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Exams</span>
@endsection

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
  <div>
    <h2 style="margin:0 0 4px 0;font-weight:800;font-size:1.6rem;">Exam Management</h2>
    <p style="margin:0;color:var(--gray-500);font-size:0.9rem;">Schedule tests, manage dynamic exam types, syllabus marks, and hall allocation</p>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <button class="btn btn-outline" onclick="document.getElementById('manageExamTypesModal').style.display='flex'">
      <i class="bi bi-tags"></i> Manage Exam Types ({{ $examTypes->count() }})
    </button>
    <button class="btn btn-primary" onclick="document.getElementById('addExamModal').style.display='flex'">
      <i class="bi bi-plus-circle"></i> Schedule New Exam
    </button>
  </div>
</div>

<!-- Filter Toolbar -->
<div style="background:#fff;padding:16px 20px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:24px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;box-shadow:var(--shadow-sm);">
  <form method="GET" action="{{ route('admin.exams') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;flex:1;">
    <div style="display:flex;align-items:center;gap:8px;">
      <label style="font-size:0.85rem;font-weight:600;color:var(--gray-600);white-space:nowrap;"><i class="bi bi-funnel"></i> Exam Type:</label>
      <select name="exam_type" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;min-width:180px;">
        <option value="">All Types ({{ $examTypes->count() }})</option>
        @foreach($examTypes as $t)
          <option value="{{ $t->name }}" {{ request('exam_type') === $t->name ? 'selected' : '' }}>
            {{ $t->name }} ({{ $t->exams_count ?? 0 }})
          </option>
        @endforeach
      </select>
    </div>

    <div style="display:flex;align-items:center;gap:8px;">
      <label style="font-size:0.85rem;font-weight:600;color:var(--gray-600);white-space:nowrap;"><i class="bi bi-collection"></i> Batch:</label>
      <select name="batch_id" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;min-width:180px;">
        <option value="">All Batches</option>
        @foreach($batches as $b)
          <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>
            {{ $b->name }}
          </option>
        @endforeach
      </select>
    </div>

    @if(request('exam_type') || request('batch_id'))
      <a href="{{ route('admin.exams') }}" class="btn btn-outline btn-sm" style="color:var(--gray-600);padding:6px 12px;">
        <i class="bi bi-x-circle"></i> Clear Filters
      </a>
    @endif
  </form>

  <div style="display:flex;align-items:center;gap:12px;">
    <span style="font-size:0.85rem;color:var(--gray-500);">Showing <strong>{{ $exams->total() }}</strong> exam(s)</span>
    <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('quickAddTypeModal').style.display='flex'" title="Add a new exam type quickly">
      <i class="bi bi-plus-lg"></i> New Type
    </button>
  </div>
</div>

<!-- Exams Table -->
<div class="table-wrapper" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:24px;">
  <table class="data-table">
    <thead>
      <tr>
        <th>Exam Title</th>
        <th>Exam Type</th>
        <th>Batch</th>
        <th>Subject</th>
        <th>Date &amp; Time</th>
        <th>Total / Pass</th>
        <th>Room</th>
        <th>Status</th>
        <th style="text-align:right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($exams as $e)
      <tr>
        <td>
          <strong style="color:var(--gray-900);">{{ $e->title }}</strong>
        </td>
        <td>
          <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;padding:4px 8px;">
            <i class="bi bi-tag-fill" style="font-size:0.7rem;margin-right:2px;"></i> {{ $e->exam_type }}
          </span>
        </td>
        <td>{{ $e->batch ? $e->batch->name : 'All Batches' }}</td>
        <td><strong>{{ $e->subject }}</strong></td>
        <td>
          <div style="font-weight:600;">{{ \Carbon\Carbon::parse($e->date)->format('d M, Y') }}</div>
          <small style="color:var(--gray-500);"><i class="bi bi-clock"></i> {{ $e->time }}</small>
        </td>
        <td>
          <span style="font-weight:700;color:var(--gray-800);">{{ $e->total_marks }}</span>
          <span style="color:var(--gray-500);font-size:0.85rem;"> / Pass: {{ $e->pass_marks }}</span>
        </td>
        <td>{{ $e->room ?: '-' }}</td>
        <td>
          <span class="badge {{ $e->status === 'Completed' ? 'badge-success' : ($e->status === 'Upcoming' ? 'badge-warning' : 'badge-primary') }}">
            {{ $e->status }}
          </span>
        </td>
        <td style="text-align:right;">
          <div class="action-btns" style="justify-content:flex-end;">
            <button class="btn btn-outline btn-icon btn-sm" onclick='openEditExamModal(@json($e))' title="Edit Exam">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.exams.destroy', $e->id) }}" method="POST" onsubmit="return confirm('Delete this exam?');" style="display:inline;margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outline btn-icon btn-sm" style="color:var(--danger);border-color:var(--border);" title="Delete Exam">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" style="text-align:center;padding:40px 20px;color:var(--gray-500);">
          <i class="bi bi-file-earmark-x" style="font-size:2.5rem;color:var(--gray-400);display:block;margin-bottom:10px;"></i>
          <h5 style="color:var(--gray-700);margin-bottom:6px;">No exams found</h5>
          <p style="font-size:0.88rem;margin-bottom:16px;">Try clearing filters or schedule a new exam.</p>
          <button class="btn btn-primary btn-sm" onclick="document.getElementById('addExamModal').style.display='flex'">
            <i class="bi bi-plus-circle"></i> Schedule New Exam
          </button>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($exams->hasPages())
  <div style="margin-bottom:28px;">{{ $exams->links() }}</div>
@endif

<!-- Add Exam Modal -->
<div id="addExamModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:560px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
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
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Exam Type *</label>
            <a href="javascript:void(0)" onclick="openQuickAddType()" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ New Type</a>
          </div>
          <select name="exam_type" id="addExamTypeSelect" class="form-select" required>
            <option value="">-- Select Exam Type --</option>
            @foreach($examTypes as $t)
              <option value="{{ $t->name }}">{{ $t->name }}</option>
            @endforeach
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
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Subject *</label>
            <a href="{{ route('admin.subjects.index') }}" target="_blank" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ Manage</a>
          </div>
          <select name="subject" class="form-select" required>
            <option value="">-- Select Subject --</option>
            @foreach($subjects as $sub)
              <option value="{{ $sub->name }}">{{ $sub->name }} ({{ $sub->code }})</option>
            @endforeach
          </select>
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
<div id="editExamModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:560px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
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
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Exam Type *</label>
            <a href="javascript:void(0)" onclick="openQuickAddType()" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ New Type</a>
          </div>
          <select name="exam_type" id="editExamType" class="form-select" required>
            <option value="">-- Select Exam Type --</option>
            @foreach($examTypes as $t)
              <option value="{{ $t->name }}">{{ $t->name }}</option>
            @endforeach
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
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Subject *</label>
            <a href="{{ route('admin.subjects.index') }}" target="_blank" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ Manage</a>
          </div>
          <select name="subject" id="editExamSubject" class="form-select" required>
            <option value="">-- Select Subject --</option>
            @foreach($subjects as $sub)
              <option value="{{ $sub->name }}">{{ $sub->name }} ({{ $sub->code }})</option>
            @endforeach
          </select>
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
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Room / Hall</label>
          <input type="text" name="room" id="editExamRoom" class="form-control">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Total Marks *</label>
          <input type="number" name="total_marks" id="editExamTotal" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Pass Marks *</label>
          <input type="number" name="pass_marks" id="editExamPass" class="form-control" required>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" id="editExamStatus" class="form-select" required>
          <option value="Upcoming">Upcoming</option>
          <option value="Ongoing">Ongoing</option>
          <option value="Completed">Completed</option>
        </select>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editExamModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Exam</button>
      </div>
    </form>
  </div>
</div>

<!-- Manage Exam Types Modal -->
<div id="manageExamTypesModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:760px;padding:26px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;border-bottom:1px solid var(--border);padding-bottom:14px;">
      <div>
        <h4 style="margin:0 0 4px 0;font-weight:800;color:var(--gray-900);"><i class="bi bi-tags" style="color:var(--primary);"></i> Exam Types Management</h4>
        <p style="margin:0;font-size:0.84rem;color:var(--gray-500);">Add, edit, or remove exam types. Exams are dynamically categorized by these types.</p>
      </div>
      <button onclick="document.getElementById('manageExamTypesModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>

    <!-- Quick Add Exam Type Form -->
    <div style="background:var(--gray-50);border:1px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:20px;">
      <h6 style="margin:0 0 10px 0;font-weight:700;font-size:0.9rem;"><i class="bi bi-plus-circle" style="color:var(--primary);"></i> Add New Exam Type</h6>
      <form action="{{ route('admin.exam.types.store') }}" method="POST" style="display:grid;grid-template-columns:1fr 1.5fr auto;gap:10px;align-items:end;">
        @csrf
        <div>
          <label style="display:block;font-size:0.78rem;font-weight:600;margin-bottom:4px;">Type Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Surprise Quiz, Midterm" required style="padding:6px 10px;font-size:0.85rem;">
        </div>
        <div>
          <label style="display:block;font-size:0.78rem;font-weight:600;margin-bottom:4px;">Description (Optional)</label>
          <input type="text" name="description" class="form-control" placeholder="Brief note on assessment method..." style="padding:6px 10px;font-size:0.85rem;">
        </div>
        <div>
          <button type="submit" class="btn btn-primary" style="padding:7px 16px;font-size:0.85rem;white-space:nowrap;">
            <i class="bi bi-plus-lg"></i> Add Type
          </button>
        </div>
      </form>
    </div>

    <!-- Types List Table -->
    <div class="table-responsive" style="max-height:360px;overflow-y:auto;border:1px solid var(--border);border-radius:var(--radius);">
      <table class="table" style="margin:0;">
        <thead>
          <tr style="background:var(--gray-100);position:sticky;top:0;z-index:2;">
            <th>Type Name</th>
            <th>Slug</th>
            <th style="text-align:center;">Exams</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($examTypes as $t)
          <tr>
            <td>
              <strong style="color:var(--gray-900);font-size:0.9rem;">{{ $t->name }}</strong>
              @if($t->description)
                <div style="font-size:0.75rem;color:var(--gray-500);margin-top:2px;">{{ Str::limit($t->description, 50) }}</div>
              @endif
            </td>
            <td><code style="font-size:0.75rem;background:var(--gray-100);padding:2px 6px;border-radius:4px;">{{ $t->slug }}</code></td>
            <td style="text-align:center;">
              <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;">{{ $t->exams_count ?? 0 }}</span>
            </td>
            <td style="text-align:center;">
              <span class="badge {{ $t->status === 'Active' ? 'badge-success' : 'badge-danger' }}">{{ $t->status }}</span>
            </td>
            <td style="text-align:right;">
              <div style="display:inline-flex;gap:6px;">
                <button type="button" class="btn btn-outline btn-sm" onclick='openEditTypeModal(@json($t))' title="Edit Type" style="padding:4px 8px;font-size:0.78rem;">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="{{ route('admin.exam.types.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete exam type &quot;{{ $t->name }}&quot;? Linked exams will have unassigned type.');" style="margin:0;display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--border);padding:4px 8px;font-size:0.78rem;" title="Delete Type">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;padding:24px;color:var(--gray-500);">No exam types found. Create your first exam type above.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top:16px;display:flex;justify-content:flex-end;">
      <button type="button" class="btn btn-outline" onclick="document.getElementById('manageExamTypesModal').style.display='none'">Close</button>
    </div>
  </div>
</div>

<!-- Edit Exam Type Modal -->
<div id="editExamTypeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1150;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:440px;padding:22px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
      <h5 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Exam Type</h5>
      <button onclick="document.getElementById('editExamTypeModal').style.display='none'" style="background:none;border:none;font-size:1.2rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editExamTypeForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Type Name *</label>
        <input type="text" name="name" id="editTypeName" class="form-control" required>
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" id="editTypeStatus" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description</label>
        <textarea name="description" id="editTypeDescription" class="form-control" rows="2"></textarea>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('editExamTypeModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle"></i> Update Type</button>
      </div>
    </form>
  </div>
</div>

<!-- Quick Add Exam Type Modal (used from within Exam Modal) -->
<div id="quickAddTypeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1200;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:420px;padding:22px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
      <h5 style="margin:0;font-weight:700;"><i class="bi bi-tag" style="color:var(--primary);"></i> Quick Add Exam Type</h5>
      <button onclick="document.getElementById('quickAddTypeModal').style.display='none'" style="background:none;border:none;font-size:1.2rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.exam.types.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Type Name *</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Surprise Test" required>
      </div>
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description (Optional)</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Brief note..."></textarea>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('quickAddTypeModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle"></i> Save Type</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditExamModal(e) {
  document.getElementById('editExamModal').style.display = 'flex';
  document.getElementById('editExamForm').action = '/admin/exams/' + e.id;
  document.getElementById('editExamTitle').value = e.title || '';
  document.getElementById('editExamType').value = e.exam_type || '';
  document.getElementById('editExamBatch').value = e.batch_id || '';
  document.getElementById('editExamSubject').value = e.subject || '';
  document.getElementById('editExamDate').value = e.date ? e.date.substring(0, 10) : '';
  document.getElementById('editExamTime').value = e.time || '';
  document.getElementById('editExamRoom').value = e.room || '';
  document.getElementById('editExamTotal').value = e.total_marks || 100;
  document.getElementById('editExamPass').value = e.pass_marks || 40;
  document.getElementById('editExamStatus').value = e.status || 'Upcoming';
}

function openEditTypeModal(t) {
  document.getElementById('editExamTypeModal').style.display = 'flex';
  document.getElementById('editExamTypeForm').action = '/admin/exam-types/' + t.id;
  document.getElementById('editTypeName').value = t.name || '';
  document.getElementById('editTypeStatus').value = t.status || 'Active';
  document.getElementById('editTypeDescription').value = t.description || '';
}

function openQuickAddType() {
  document.getElementById('quickAddTypeModal').style.display = 'flex';
}
</script>
@endsection
