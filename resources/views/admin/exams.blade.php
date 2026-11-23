@extends('layouts.admin')

@section('title', 'Exam Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Exams</span>
@endsection

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
  <div>
    <h2 style="margin:0 0 4px 0;font-weight:800;font-size:1.6rem;">Exam Management</h2>
    <p style="margin:0;color:var(--gray-500);font-size:0.9rem;">Schedule tests by class and batch, manage dynamic subjects, and hall allocation</p>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <button class="btn btn-outline" onclick="document.getElementById('manageExamTypesModal').style.display='flex'">
      <i class="bi bi-tags"></i> Manage Exam Types ({{ $examTypes->count() }})
    </button>
    <button class="btn btn-primary" onclick="openAddExamModal()">
      <i class="bi bi-plus-circle"></i> Schedule New Exam
    </button>
  </div>
</div>

<!-- Program Stream Tabs -->
<div style="background:#fff;padding:12px 18px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:20px;box-shadow:var(--shadow-sm);display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
  <span style="font-size:0.85rem;font-weight:700;color:var(--gray-600);margin-right:4px;">
    <i class="bi bi-diagram-3-fill" style="color:var(--primary);"></i> Exam Stream:
  </span>
  <a href="{{ route('admin.exams') }}" 
     class="btn {{ !request('program_type') ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-grid-fill"></i> All Exams ({{ $totalExamsCount }})
  </a>
  <a href="{{ route('admin.exams', array_merge(request()->except('page'), ['program_type' => 'Academic'])) }}" 
     class="btn {{ request('program_type') === 'Academic' ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-mortarboard-fill"></i> Academic Class Exams ({{ $academicExamsCount }})
  </a>
  <a href="{{ route('admin.exams', array_merge(request()->except('page'), ['program_type' => 'Target'])) }}" 
     class="btn {{ request('program_type') === 'Target' ? 'btn-primary' : 'btn-outline' }} btn-sm" 
     style="border-radius:20px;padding:6px 16px;font-size:0.85rem;">
    <i class="bi bi-bullseye"></i> Target Batch Exams ({{ $targetExamsCount }})
  </a>
</div>

<!-- Filter Toolbar -->
<div style="background:#fff;padding:16px 20px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:24px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;box-shadow:var(--shadow-sm);">
  <form method="GET" action="{{ route('admin.exams') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;flex:1;">
    @if(request('program_type'))
      <input type="hidden" name="program_type" value="{{ request('program_type') }}">
    @endif
    <div style="display:flex;align-items:center;gap:6px;">
      <label style="font-size:0.85rem;font-weight:600;color:var(--gray-600);white-space:nowrap;"><i class="bi bi-book"></i> Class:</label>
      <select name="class_id" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;min-width:160px;">
        <option value="">All Classes</option>
        @foreach($classes as $cls)
          <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
            {{ $cls->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div style="display:flex;align-items:center;gap:6px;">
      <label style="font-size:0.85rem;font-weight:600;color:var(--gray-600);white-space:nowrap;"><i class="bi bi-collection"></i> Batch:</label>
      <select name="batch_id" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;min-width:170px;">
        <option value="">All Batches</option>
        @foreach($batches as $b)
          <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>
            {{ $b->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div style="display:flex;align-items:center;gap:6px;">
      <label style="font-size:0.85rem;font-weight:600;color:var(--gray-600);white-space:nowrap;"><i class="bi bi-funnel"></i> Type:</label>
      <select name="exam_type" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;min-width:160px;">
        <option value="">All Types ({{ $examTypes->count() }})</option>
        @foreach($examTypes as $t)
          <option value="{{ $t->name }}" {{ request('exam_type') === $t->name ? 'selected' : '' }}>
            {{ $t->name }} ({{ $t->exams_count ?? 0 }})
          </option>
        @endforeach
      </select>
    </div>

    @if(request('class_id') || request('batch_id') || request('exam_type'))
      <a href="{{ request('program_type') ? route('admin.exams', ['program_type' => request('program_type')]) : route('admin.exams') }}" class="btn btn-outline btn-sm" style="color:var(--gray-600);padding:6px 12px;">
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
        <th>Stream</th>
        <th>Exam Type</th>
        <th>Batch &amp; Class</th>
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
          @if($e->program_type === 'Target')
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
          <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;padding:4px 8px;">
            <i class="bi bi-tag-fill" style="font-size:0.7rem;margin-right:2px;"></i> {{ $e->exam_type }}
          </span>
        </td>
        <td>
          <strong>{{ $e->batch ? $e->batch->name : 'All Batches' }}</strong>
          <div style="margin-top:2px;">
            @php
              $clsName = $e->academicClass?->name ?: ($e->batch?->academicClass?->name ?: $e->batch?->class_name);
            @endphp
            @if($clsName)
              <span class="badge" style="background:#eff6ff;color:#1d4ed8;font-size:0.72rem;padding:2px 7px;border:1px solid #bfdbfe;">
                <i class="bi bi-book"></i> {{ $clsName }}
              </span>
            @endif
          </div>
        </td>
        <td>
          <strong style="color:var(--primary);">{{ $e->subject }}</strong>
          @if($e->subjectModel?->code)
            <div style="font-size:0.75rem;color:var(--gray-500);"><code>{{ $e->subjectModel->code }}</code></div>
          @endif
        </td>
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
          <button class="btn btn-primary btn-sm" onclick="openAddExamModal()">
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
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:580px;padding:26px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-file-earmark-plus" style="color:var(--primary);"></i> Schedule Exam</h4>
      <button onclick="document.getElementById('addExamModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.exams.store') }}" method="POST">
      @csrf

      <!-- Stream Selector -->
      <div style="margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e2e8f0;">
        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:6px;color:#1e3a8a;">
          <i class="bi bi-diagram-3-fill"></i> Exam Program Stream *
        </label>
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="addExamStreamAcademic" value="Academic" checked onchange="onExamStreamChange('add')">
            <span style="color:#0369a1;"><i class="bi bi-mortarboard"></i> Academic Class Exam (একাডেমিক পরীক্ষা)</span>
          </label>
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="addExamStreamTarget" value="Target" onchange="onExamStreamChange('add')">
            <span style="color:#b45309;"><i class="bi bi-bullseye"></i> Target Batch Exam (টার্গেট ব্যাচ পরীক্ষা)</span>
          </label>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Title *</label>
        <input type="text" name="title" class="form-control" placeholder="e.g. SSC Model Test 1 - Physics" required>
      </div>

      <div style="margin-bottom:14px;">
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

      <!-- Class & Target Batch linked selection -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:var(--radius);border:1px solid #e2e8f0;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;color:#1e3a8a;">
            <i class="bi bi-book"></i> Academic Class *
          </label>
          <select name="class_id" id="addExamClassSelect" class="form-select" onchange="onExamClassChange('add', this.value)">
            <option value="">-- Choose Class --</option>
            @foreach($classes as $cls)
              <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;color:#1e3a8a;">
            <i class="bi bi-collection"></i> Target Batch *
          </label>
          <select name="batch_id" id="addExamBatchSelect" class="form-select" required onchange="onExamBatchChange('add', this.value)">
            <option value="">-- Select Target Batch --</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" data-class-id="{{ $b->class_id }}" data-class-name="{{ $b->academicClass?->name ?: $b->class_name }}">
                {{ $b->name }} @if($b->academicClass) ({{ $b->academicClass->name }}) @endif
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Subject filtered by Class & Target Batch -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Subject *</label>
            <a href="{{ route('admin.subjects.index') }}" target="_blank" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ Manage</a>
          </div>
          <select name="subject" id="addExamSubjectSelect" class="form-select" required>
            <option value="">-- Select Subject --</option>
            <!-- Populated dynamically based on chosen class/batch -->
          </select>
          <small id="addSubjectClassHint" style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">
            Select Class or Target Batch to load curriculum subjects
          </small>
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
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:580px;padding:26px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Exam</h4>
      <button onclick="document.getElementById('editExamModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editExamForm" method="POST">
      @csrf
      @method('PUT')

      <!-- Stream Selector -->
      <div style="margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e2e8f0;">
        <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:6px;color:#1e3a8a;">
          <i class="bi bi-diagram-3-fill"></i> Exam Program Stream *
        </label>
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="editExamStreamAcademic" value="Academic" onchange="onExamStreamChange('edit')">
            <span style="color:#0369a1;"><i class="bi bi-mortarboard"></i> Academic Class Exam</span>
          </label>
          <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;font-size:0.88rem;">
            <input type="radio" name="program_type" id="editExamStreamTarget" value="Target" onchange="onExamStreamChange('edit')">
            <span style="color:#b45309;"><i class="bi bi-bullseye"></i> Target Batch Exam</span>
          </label>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam Title *</label>
        <input type="text" name="title" id="editExamTitle" class="form-control" required>
      </div>

      <div style="margin-bottom:14px;">
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

      <!-- Class & Target Batch linked selection (Edit) -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;background:#f8fafc;padding:12px;border-radius:var(--radius);border:1px solid #e2e8f0;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;color:#1e3a8a;">
            <i class="bi bi-book"></i> Academic Class *
          </label>
          <select name="class_id" id="editExamClassSelect" class="form-select" onchange="onExamClassChange('edit', this.value)">
            <option value="">-- Choose Class --</option>
            @foreach($classes as $cls)
              <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->code }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;color:#1e3a8a;">
            <i class="bi bi-collection"></i> Target Batch *
          </label>
          <select name="batch_id" id="editExamBatchSelect" class="form-select" required onchange="onExamBatchChange('edit', this.value)">
            <option value="">-- Select Target Batch --</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}" data-class-id="{{ $b->class_id }}" data-class-name="{{ $b->academicClass?->name ?: $b->class_name }}">
                {{ $b->name }} @if($b->academicClass) ({{ $b->academicClass->name }}) @endif
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Subject filtered by Class & Target Batch (Edit) -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Subject *</label>
            <a href="{{ route('admin.subjects.index') }}" target="_blank" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ Manage</a>
          </div>
          <select name="subject" id="editExamSubjectSelect" class="form-select" required>
            <option value="">-- Select Subject --</option>
          </select>
          <small id="editSubjectClassHint" style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">
            Curriculum subjects for selected class
          </small>
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
                <form action="{{ route('admin.exam.types.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Delete exam type &quot;{{ $t->name }}&quot;?');" style="margin:0;display:inline;">
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

<!-- Quick Add Exam Type Modal -->
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
const allClassesData = @json($classes);
const allBatchesData = @json($batches);
const allSubjectsData = @json($subjects);

function getExamStream(mode) {
  const targetRadio = document.getElementById(mode + 'ExamStreamTarget');
  return (targetRadio && targetRadio.checked) ? 'Target' : 'Academic';
}

function onExamStreamChange(mode) {
  const stream = getExamStream(mode);
  populateClasses(mode, stream);
  populateBatches(mode, null, null, stream);
  populateSubjects(mode, null, null, stream);
}

function populateClasses(mode, stream = null) {
  const select = document.getElementById(mode + 'ExamClassSelect');
  const currentVal = select.value;
  select.innerHTML = '<option value="">-- Choose Class / Program --</option>';

  const currentStream = stream || getExamStream(mode);
  let filtered = allClassesData;
  if (currentStream) {
    filtered = allClassesData.filter(c => (c.class_type || 'Academic') === currentStream);
  }

  filtered.forEach(c => {
    const isSel = (currentVal && c.id == currentVal) ? 'selected' : '';
    select.innerHTML += `<option value="${c.id}" ${isSel}>${escapeHtml(c.name)} (${escapeHtml(c.code || 'N/A')})</option>`;
  });
}

function openAddExamModal() {
  document.getElementById('addExamModal').style.display = 'flex';
  document.getElementById('addExamStreamAcademic').checked = true;
  document.getElementById('addExamClassSelect').value = '';
  document.getElementById('addExamBatchSelect').value = '';
  onExamStreamChange('add');
}

function openEditExamModal(e) {
  document.getElementById('editExamModal').style.display = 'flex';
  document.getElementById('editExamForm').action = '/admin/exams/' + e.id;
  document.getElementById('editExamTitle').value = e.title || '';
  document.getElementById('editExamType').value = e.exam_type || '';

  const pType = e.program_type || 'Academic';
  if (pType === 'Target') {
    document.getElementById('editExamStreamTarget').checked = true;
  } else {
    document.getElementById('editExamStreamAcademic').checked = true;
  }

  let classId = e.class_id;
  if (!classId && e.batch && e.batch.class_id) {
    classId = e.batch.class_id;
  }

  populateClasses('edit', pType);
  document.getElementById('editExamClassSelect').value = classId || '';
  populateBatches('edit', classId, e.batch_id, pType);
  populateSubjects('edit', classId, e.subject, pType);

  document.getElementById('editExamDate').value = e.date ? e.date.substring(0, 10) : '';
  document.getElementById('editExamTime').value = e.time || '';
  document.getElementById('editExamRoom').value = e.room || '';
  document.getElementById('editExamTotal').value = e.total_marks || 100;
  document.getElementById('editExamPass').value = e.pass_marks || 40;
  document.getElementById('editExamStatus').value = e.status || 'Upcoming';
}

function onExamClassChange(mode, classId) {
  const stream = getExamStream(mode);
  populateBatches(mode, classId, null, stream);
  populateSubjects(mode, classId, null, stream);

  const hintEl = document.getElementById(mode + 'SubjectClassHint');
  if (classId) {
    const cls = allClassesData.find(c => c.id == classId);
    if (hintEl && cls) {
      hintEl.innerHTML = `<span style="color:#1d4ed8;font-weight:600;"><i class="bi bi-check-circle"></i> Loaded subjects specifically for ${cls.name}</span>`;
    }
  } else {
    if (hintEl) {
      hintEl.innerText = 'Select Class or Target Batch to load curriculum subjects';
    }
  }
}

function onExamBatchChange(mode, batchId) {
  if (!batchId) return;
  const batch = allBatchesData.find(b => b.id == batchId);
  const stream = getExamStream(mode);
  if (batch && batch.class_id) {
    const classSelect = document.getElementById(mode + 'ExamClassSelect');
    if (classSelect.value != batch.class_id) {
      classSelect.value = batch.class_id;
    }
    populateSubjects(mode, batch.class_id, null, stream);

    const hintEl = document.getElementById(mode + 'SubjectClassHint');
    if (hintEl) {
      const clsName = batch.academic_class ? batch.academic_class.name : (batch.class_name || 'Class');
      hintEl.innerHTML = `<span style="color:#1d4ed8;font-weight:600;"><i class="bi bi-check-circle"></i> Loaded subjects for ${batch.name} (${clsName})</span>`;
    }
  }
}

function populateBatches(mode, classId = null, selectedBatchId = null, stream = null) {
  const select = document.getElementById(mode + 'ExamBatchSelect');
  const currentVal = selectedBatchId || select.value;
  select.innerHTML = '<option value="">-- Select Target Batch --</option>';

  const currentStream = stream || getExamStream(mode);
  let filtered = allBatchesData;
  if (currentStream) {
    filtered = filtered.filter(b => (b.program_type || 'Academic') === currentStream);
  }
  if (classId) {
    filtered = filtered.filter(b => b.class_id == classId);
  }

  filtered.forEach(b => {
    const isSel = (currentVal && b.id == currentVal) ? 'selected' : '';
    const clsName = b.academic_class ? ` (${b.academic_class.name})` : (b.class_name ? ` (${b.class_name})` : '');
    select.innerHTML += `<option value="${b.id}" data-class-id="${b.class_id || ''}" ${isSel}>${escapeHtml(b.name)}${clsName}</option>`;
  });
}

function populateSubjects(mode, classId = null, selectedSubjectName = null, stream = null) {
  const select = document.getElementById(mode + 'ExamSubjectSelect');
  const currentVal = selectedSubjectName || select.value;
  select.innerHTML = '<option value="">-- Select Subject --</option>';

  const currentStream = stream || getExamStream(mode);

  if (classId) {
    // Show only subjects belonging to this class
    let classSubs = allSubjectsData.filter(s => s.class_id == classId);
    if (currentStream) {
      classSubs = classSubs.filter(s => (s.program_type || 'Academic') === currentStream);
    }
    if (classSubs.length > 0) {
      classSubs.forEach(s => {
        const isSel = (currentVal && (s.name.toLowerCase() === currentVal.toLowerCase())) ? 'selected' : '';
        select.innerHTML += `<option value="${escapeHtml(s.name)}" ${isSel}>${escapeHtml(s.name)} (${escapeHtml(s.code)})</option>`;
      });
    } else {
      select.innerHTML += `<option value="" disabled>No subjects configured for this class yet</option>`;
    }
  } else {
    // Group subjects by class for current stream
    const filteredClasses = allClassesData.filter(c => !currentStream || (c.class_type || 'Academic') === currentStream);
    filteredClasses.forEach(cls => {
      let subs = allSubjectsData.filter(s => s.class_id == cls.id);
      if (currentStream) {
        subs = subs.filter(s => (s.program_type || 'Academic') === currentStream);
      }
      if (subs.length > 0) {
        let optGroup = `<optgroup label="${escapeHtml(cls.name)}">`;
        subs.forEach(s => {
          const isSel = (currentVal && (s.name.toLowerCase() === currentVal.toLowerCase())) ? 'selected' : '';
          optGroup += `<option value="${escapeHtml(s.name)}" ${isSel}>${escapeHtml(s.name)} (${escapeHtml(s.code)})</option>`;
        });
        optGroup += `</optgroup>`;
        select.innerHTML += optGroup;
      }
    });

    // Unassigned subjects if any
    const unassigned = allSubjectsData.filter(s => !s.class_id && (!currentStream || (s.program_type || 'Academic') === currentStream));
    if (unassigned.length > 0) {
      let optGroup = `<optgroup label="General / Other">`;
      unassigned.forEach(s => {
        const isSel = (currentVal && (s.name.toLowerCase() === currentVal.toLowerCase())) ? 'selected' : '';
        optGroup += `<option value="${escapeHtml(s.name)}" ${isSel}>${escapeHtml(s.name)} (${escapeHtml(s.code)})</option>`;
      });
      optGroup += `</optgroup>`;
      select.innerHTML += optGroup;
    }
  }
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

function escapeHtml(text) {
  if (!text) return '';
  return text.toString()
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
</script>
@endsection
