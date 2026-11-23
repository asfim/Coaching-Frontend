@extends('layouts.admin')

@section('title', 'Exam Results Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Results</span>
@endsection

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
  <div>
    <h2 style="margin:0 0 4px 0;font-weight:800;font-size:1.6rem;">Result Management</h2>
    <p style="margin:0;color:var(--gray-500);font-size:0.9rem;">Record subject-wise exam marks, auto-calculate average GPA, and generate official marksheets</p>
  </div>
  <button class="btn btn-primary" onclick="openAddResultModal()"><i class="bi bi-plus-circle"></i> Add / Enter Marks</button>
</div>

<!-- Filter Toolbar -->
<div style="background:#fff;padding:16px 20px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:24px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;box-shadow:var(--shadow-sm);">
  <form method="GET" action="{{ route('admin.results') }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;flex:1;">
    <div style="display:flex;align-items:center;gap:8px;">
      <label style="font-size:0.85rem;font-weight:600;color:var(--gray-600);white-space:nowrap;"><i class="bi bi-file-earmark-text"></i> Exam:</label>
      <select name="exam_id" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;min-width:220px;">
        <option value="">All Exams ({{ $exams->count() }})</option>
        @foreach($exams as $e)
          <option value="{{ $e->id }}" {{ request('exam_id') == $e->id ? 'selected' : '' }}>
            {{ $e->title }} ({{ $e->subject }})
          </option>
        @endforeach
      </select>
    </div>

    <div style="display:flex;align-items:center;gap:8px;flex:1;max-width:320px;">
      <div style="position:relative;width:100%;">
        <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:0.85rem;"></i>
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search student by name, ID or class..." style="padding-left:32px;padding-right:10px;font-size:0.88rem;">
      </div>
      <button type="submit" class="btn btn-primary btn-sm" style="padding:7px 12px;"><i class="bi bi-arrow-right"></i></button>
    </div>

    @if(request('exam_id') || request('search'))
      <a href="{{ route('admin.results') }}" class="btn btn-outline btn-sm" style="color:var(--gray-600);padding:6px 12px;">
        <i class="bi bi-x-circle"></i> Clear Filters
      </a>
    @endif
  </form>

  <div>
    <span style="font-size:0.85rem;color:var(--gray-500);">Total Results: <strong>{{ $results->total() }}</strong></span>
  </div>
</div>

<!-- Results Table -->
<div class="table-wrapper" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:24px;">
  <table class="data-table">
    <thead>
      <tr>
        <th>Student</th>
        <th>Exam</th>
        <th>Subjects &amp; Marks</th>
        <th>Total Marks</th>
        <th>Percentage</th>
        <th>Avg. GPA</th>
        <th>Grade</th>
        <th>Remarks</th>
        <th style="text-align:right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($results as $r)
      @php
        $pct = $r->total_marks > 0 ? round(($r->obtained_marks / $r->total_marks) * 100) : 0;
        $itemCount = $r->items->count();
      @endphp
      <tr>
        <td>
          <div class="avatar-cell">
            <img src="{{ asset($r->student?->avatar ?: 'images/student_female_profile_1790497748277.jpg') }}" alt="" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:1px solid var(--border);">
            <div>
              <strong style="color:var(--gray-900);">{{ $r->student?->name ?: 'N/A' }}</strong>
              <div style="display:flex;gap:6px;align-items:center;margin-top:2px;">
                <span class="badge" style="background:var(--gray-100);color:var(--gray-700);font-size:0.7rem;padding:2px 6px;">#{{ $r->student?->student_id }}</span>
                @if($r->student?->class_name)
                  <span style="font-size:0.75rem;color:var(--gray-500);">{{ $r->student->class_name }}</span>
                @endif
              </div>
            </div>
          </div>
        </td>
        <td>
          <strong style="color:var(--gray-800);">{{ $r->exam?->title ?: 'General Exam' }}</strong>
          <div style="font-size:0.75rem;color:var(--gray-500);">
            {{ $r->exam?->date ? \Carbon\Carbon::parse($r->exam->date)->format('d M, Y') : '-' }}
          </div>
        </td>
        <td>
          @if($itemCount > 1)
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
              <span class="badge" style="background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;font-size:0.75rem;">
                <i class="bi bi-journals"></i> {{ $itemCount }} Subjects
              </span>
              <span style="font-size:0.75rem;color:var(--gray-500);">
                ({{ $r->items->pluck('subject_name')->take(2)->implode(', ') }}@if($itemCount > 2)...@endif)
              </span>
            </div>
          @elseif($itemCount === 1)
            <span style="font-weight:600;color:var(--gray-700);font-size:0.85rem;">{{ $r->items->first()->subject_name }}</span>
          @else
            <span style="font-weight:600;color:var(--gray-700);font-size:0.85rem;">{{ $r->exam?->subject ?: '-' }}</span>
          @endif
        </td>
        <td>
          <span style="font-weight:700;color:var(--gray-900);font-size:0.95rem;">{{ number_format($r->obtained_marks, 1) }}</span>
          <span style="color:var(--gray-500);font-size:0.82rem;"> / {{ number_format($r->total_marks, 0) }}</span>
        </td>
        <td>
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="flex:1;background:var(--gray-200);height:6px;border-radius:3px;overflow:hidden;min-width:45px;">
              <div style="width:{{ min(100, $pct) }}%;background:{{ $pct >= 80 ? '#10b981' : ($pct >= 50 ? '#3b82f6' : '#ef4444') }};height:100%;"></div>
            </div>
            <span style="font-size:0.8rem;font-weight:700;color:var(--gray-700);">{{ $pct }}%</span>
          </div>
        </td>
        <td><strong style="color:var(--primary);font-size:1rem;">{{ number_format($r->gpa, 2) }}</strong></td>
        <td>
          <span class="badge {{ $r->grade === 'A+' ? 'badge-success' : ($r->grade === 'F' ? 'badge-danger' : 'badge-primary') }}" style="font-size:0.82rem;font-weight:700;">
            {{ $r->grade }}
          </span>
        </td>
        <td><small style="color:var(--gray-600);">{{ Str::limit($r->remarks ?: '-', 20) }}</small></td>
        <td style="text-align:right;">
          <div class="action-btns" style="justify-content:flex-end;gap:6px;">
            <a href="{{ route('admin.results.marksheet', $r->id) }}" target="_blank" class="btn btn-outline btn-sm" title="View & Download Marksheet" style="padding:4px 9px;font-size:0.78rem;color:#0369a1;border-color:#7dd3fc;background:#f0f9ff;display:inline-flex;align-items:center;gap:4px;">
              <i class="bi bi-file-earmark-pdf-fill"></i> Marksheet
            </a>
            <button class="btn btn-outline btn-icon btn-sm" onclick='openEditResultModal(@json($r))' title="Edit Result">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.results.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Delete this result record?');" style="display:inline;margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-outline btn-icon btn-sm" style="color:var(--danger);border-color:var(--border);" title="Delete">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" style="text-align:center;padding:40px 20px;color:var(--gray-500);">
          <i class="bi bi-trophy" style="font-size:2.5rem;color:var(--gray-300);display:block;margin-bottom:10px;"></i>
          <h5 style="color:var(--gray-700);margin-bottom:6px;">No exam results found</h5>
          <p style="font-size:0.88rem;margin-bottom:16px;">Click "Add / Enter Marks" to record marks with subject-wise evaluation.</p>
          <button class="btn btn-primary btn-sm" onclick="openAddResultModal()">
            <i class="bi bi-plus-circle"></i> Add / Enter Marks
          </button>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($results->hasPages())
  <div style="margin-bottom:28px;">{{ $results->links() }}</div>
@endif

<!-- Add Result Modal (With Live Student Search & Subject-wise Marks) -->
<div id="addResultModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:680px;padding:26px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;border-bottom:1px solid var(--border);padding-bottom:12px;">
      <div>
        <h4 style="margin:0 0 4px 0;font-weight:700;"><i class="bi bi-trophy" style="color:var(--primary);"></i> Enter Subject-wise Marks</h4>
        <small style="color:var(--gray-500);">Add marks per subject. Average GPA and Total Marks calculate automatically.</small>
      </div>
      <button onclick="document.getElementById('addResultModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>

    <form action="{{ route('admin.results.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Select Exam *</label>
        <select name="exam_id" id="addExamSelect" class="form-select" required onchange="onExamSelected(this.value, 'add')">
          <option value="">-- Choose Exam --</option>
          @foreach($exams as $e)
            <option value="{{ $e->id }}" data-subject="{{ $e->subject }}" data-marks="{{ $e->total_marks }}">{{ $e->title }} ({{ $e->subject }})</option>
          @endforeach
        </select>
      </div>

      <!-- Live Searchable Student Selector Component -->
      <div style="margin-bottom:16px;position:relative;" id="addStudentSearchContainer">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">
          Select Student * <span style="font-size:0.75rem;color:var(--gray-500);font-weight:400;">(Live Search by name, ID or class)</span>
        </label>
        
        <input type="hidden" name="student_id" id="addSelectedStudentId" required>

        <div id="addSelectedStudentCard" style="display:none;background:#ecfdf5;border:1px solid #10b981;border-radius:var(--radius);padding:10px 14px;align-items:center;justify-content:space-between;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <div style="display:flex;align-items:center;gap:12px;">
            <img id="addSelectedStudentAvatar" src="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #10b981;">
            <div>
              <div id="addSelectedStudentName" style="font-weight:700;font-size:0.92rem;color:#065f46;"></div>
              <div style="display:flex;gap:6px;align-items:center;margin-top:2px;">
                <span id="addSelectedStudentCode" class="badge" style="background:#059669;color:#fff;font-size:0.72rem;padding:2px 6px;"></span>
                <span id="addSelectedStudentClass" style="font-size:0.78rem;color:#047857;font-weight:500;"></span>
              </div>
            </div>
          </div>
          <button type="button" onclick="clearStudentSelection('add')" class="btn btn-outline btn-sm" style="border-color:#10b981;color:#065f46;padding:4px 10px;font-size:0.78rem;background:#fff;" title="Change Student">
            <i class="bi bi-arrow-repeat"></i> Change
          </button>
        </div>

        <div id="addStudentSearchBoxWrapper" style="position:relative;">
          <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);pointer-events:none;font-size:0.9rem;"></i>
          <input type="text" id="addStudentSearchInput" class="form-control" placeholder="Search student by name, student ID, or class..." autocomplete="off" style="padding-left:36px;padding-right:32px;" onfocus="onStudentInputFocus('add')" oninput="onStudentSearchInput(this.value, 'add')">
          <button type="button" id="addClearSearchBtn" onclick="clearSearchText('add')" style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:1.1rem;padding:0;">
            <i class="bi bi-x-circle-fill"></i>
          </button>
        </div>

        <div id="addStudentDropdown" style="display:none;position:absolute;top:100%;left:0;right:0;max-height:220px;overflow-y:auto;background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-xl);z-index:1050;margin-top:4px;">
          <!-- dynamically populated -->
        </div>
      </div>

      <!-- Subject-wise Marks Table Section -->
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:var(--radius);padding:14px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
          <label style="font-weight:700;font-size:0.85rem;color:#1e3a8a;margin:0;">
            <i class="bi bi-journal-check"></i> Subject-wise Marks Breakdown
          </label>
          <button type="button" onclick="addSubjectRow('add')" class="btn btn-outline btn-sm" style="padding:3px 10px;font-size:0.78rem;">
            <i class="bi bi-plus-circle"></i> Add Subject
          </button>
        </div>

        <div id="addSubjectsContainer">
          <!-- Dynamically populated subject rows -->
        </div>

        <!-- Real-time Summary Bar -->
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:10px 14px;margin-top:12px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
          <div>
            <span style="font-size:0.8rem;color:#1e40af;font-weight:600;">Total Marks:</span>
            <strong id="addSummaryTotalMarks" style="font-size:0.95rem;color:#1e3a8a;">0 / 0</strong>
            <span id="addSummaryPercentage" style="font-size:0.8rem;color:#3b82f6;margin-left:4px;">(0%)</span>
          </div>
          <div style="display:flex;gap:12px;align-items:center;">
            <div>
              <span style="font-size:0.8rem;color:#1e40af;font-weight:600;">Average GPA:</span>
              <strong id="addSummaryGpa" style="font-size:1.05rem;color:#059669;margin-left:4px;">0.00</strong>
            </div>
            <div>
              <span style="font-size:0.8rem;color:#1e40af;font-weight:600;">Grade:</span>
              <span id="addSummaryGrade" class="badge badge-success" style="font-size:0.85rem;margin-left:4px;">F</span>
            </div>
          </div>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Remarks / Feedback (Optional)</label>
        <input type="text" name="remarks" id="addRemarks" class="form-control" placeholder="e.g. Excellent analytical performance">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addResultModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Result &amp; Marksheet</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Result Modal -->
<div id="editResultModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:680px;padding:26px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;border-bottom:1px solid var(--border);padding-bottom:12px;">
      <div>
        <h4 style="margin:0 0 4px 0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Result Entry</h4>
        <small style="color:var(--gray-500);">Modify subject-wise marks. GPA and Grade recalculate automatically.</small>
      </div>
      <button onclick="document.getElementById('editResultModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>

    <form id="editResultForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Exam *</label>
        <select name="exam_id" id="editExamSelect" class="form-select" required onchange="onExamSelected(this.value, 'edit')">
          @foreach($exams as $e)
            <option value="{{ $e->id }}" data-subject="{{ $e->subject }}" data-marks="{{ $e->total_marks }}">{{ $e->title }} ({{ $e->subject }})</option>
          @endforeach
        </select>
      </div>

      <!-- Live Searchable Student Selector Component (Edit Mode) -->
      <div style="margin-bottom:16px;position:relative;" id="editStudentSearchContainer">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">
          Student * <span style="font-size:0.75rem;color:var(--gray-500);font-weight:400;">(Live Search by name, ID or class)</span>
        </label>
        
        <input type="hidden" name="student_id" id="editSelectedStudentId" required>

        <div id="editSelectedStudentCard" style="display:none;background:#ecfdf5;border:1px solid #10b981;border-radius:var(--radius);padding:10px 14px;align-items:center;justify-content:space-between;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <div style="display:flex;align-items:center;gap:12px;">
            <img id="editSelectedStudentAvatar" src="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #10b981;">
            <div>
              <div id="editSelectedStudentName" style="font-weight:700;font-size:0.92rem;color:#065f46;"></div>
              <div style="display:flex;gap:6px;align-items:center;margin-top:2px;">
                <span id="editSelectedStudentCode" class="badge" style="background:#059669;color:#fff;font-size:0.72rem;padding:2px 6px;"></span>
                <span id="editSelectedStudentClass" style="font-size:0.78rem;color:#047857;font-weight:500;"></span>
              </div>
            </div>
          </div>
          <button type="button" onclick="clearStudentSelection('edit')" class="btn btn-outline btn-sm" style="border-color:#10b981;color:#065f46;padding:4px 10px;font-size:0.78rem;background:#fff;" title="Change Student">
            <i class="bi bi-arrow-repeat"></i> Change
          </button>
        </div>

        <div id="editStudentSearchBoxWrapper" style="position:relative;">
          <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);pointer-events:none;font-size:0.9rem;"></i>
          <input type="text" id="editStudentSearchInput" class="form-control" placeholder="Search student by name, student ID, or class..." autocomplete="off" style="padding-left:36px;padding-right:32px;" onfocus="onStudentInputFocus('edit')" oninput="onStudentSearchInput(this.value, 'edit')">
          <button type="button" id="editClearSearchBtn" onclick="clearSearchText('edit')" style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:1.1rem;padding:0;">
            <i class="bi bi-x-circle-fill"></i>
          </button>
        </div>

        <div id="editStudentDropdown" style="display:none;position:absolute;top:100%;left:0;right:0;max-height:220px;overflow-y:auto;background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-xl);z-index:1050;margin-top:4px;">
          <!-- dynamically populated -->
        </div>
      </div>

      <!-- Subject-wise Marks Table Section (Edit) -->
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:var(--radius);padding:14px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
          <label style="font-weight:700;font-size:0.85rem;color:#1e3a8a;margin:0;">
            <i class="bi bi-journal-check"></i> Subject-wise Marks Breakdown
          </label>
          <button type="button" onclick="addSubjectRow('edit')" class="btn btn-outline btn-sm" style="padding:3px 10px;font-size:0.78rem;">
            <i class="bi bi-plus-circle"></i> Add Subject
          </button>
        </div>

        <div id="editSubjectsContainer">
          <!-- Dynamically populated subject rows -->
        </div>

        <!-- Real-time Summary Bar -->
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:10px 14px;margin-top:12px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
          <div>
            <span style="font-size:0.8rem;color:#1e40af;font-weight:600;">Total Marks:</span>
            <strong id="editSummaryTotalMarks" style="font-size:0.95rem;color:#1e3a8a;">0 / 0</strong>
            <span id="editSummaryPercentage" style="font-size:0.8rem;color:#3b82f6;margin-left:4px;">(0%)</span>
          </div>
          <div style="display:flex;gap:12px;align-items:center;">
            <div>
              <span style="font-size:0.8rem;color:#1e40af;font-weight:600;">Average GPA:</span>
              <strong id="editSummaryGpa" style="font-size:1.05rem;color:#059669;margin-left:4px;">0.00</strong>
            </div>
            <div>
              <span style="font-size:0.8rem;color:#1e40af;font-weight:600;">Grade:</span>
              <span id="editSummaryGrade" class="badge badge-success" style="font-size:0.85rem;margin-left:4px;">F</span>
            </div>
          </div>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Remarks / Feedback</label>
        <input type="text" name="remarks" id="editRemarks" class="form-control">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editResultModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Result &amp; Marksheet</button>
      </div>
    </form>
  </div>
</div>

<script>
const allStudents = @json($students);
const allSubjects = @json($subjects);
const defaultAvatar = "{{ asset('images/student_female_profile_1790497748277.jpg') }}";

let addSubjectCount = 0;
let editSubjectCount = 0;

function openAddResultModal() {
  document.getElementById('addResultModal').style.display = 'flex';
  clearStudentSelection('add');
  document.getElementById('addExamSelect').selectedIndex = 0;
  document.getElementById('addRemarks').value = '';
  document.getElementById('addSubjectsContainer').innerHTML = '';
  addSubjectCount = 0;

  // Add initial row
  addSubjectRow('add');
}

function openEditResultModal(r) {
  document.getElementById('editResultModal').style.display = 'flex';
  document.getElementById('editResultForm').action = '/admin/results/' + r.id;
  document.getElementById('editExamSelect').value = r.exam_id;
  document.getElementById('editRemarks').value = r.remarks || '';

  if (r.student) {
    selectStudent(r.student, 'edit');
  } else {
    clearStudentSelection('edit');
  }

  // Populate Subject items
  const container = document.getElementById('editSubjectsContainer');
  container.innerHTML = '';
  editSubjectCount = 0;

  if (r.items && r.items.length > 0) {
    r.items.forEach(item => {
      addSubjectRow('edit', item.subject_name, item.obtained_marks, item.total_marks);
    });
  } else {
    const examSub = r.exam ? r.exam.subject : 'General Subject';
    addSubjectRow('edit', examSub, r.obtained_marks, r.total_marks);
  }

  recalculateSummary('edit');
}

function onExamSelected(examId, mode) {
  const select = document.getElementById(mode + 'ExamSelect');
  const option = select.options[select.selectedIndex];
  if (option && option.dataset.subject) {
    const container = document.getElementById(mode + 'SubjectsContainer');
    // If container only has 1 empty row, set subject name
    const firstRowSub = container.querySelector('.sub-name-input');
    if (firstRowSub && !firstRowSub.value) {
      firstRowSub.value = option.dataset.subject;
    }
  }
}

function addSubjectRow(mode, subjectName = '', obtained = '', total = 100) {
  const container = document.getElementById(mode + 'SubjectsContainer');
  const index = mode === 'add' ? addSubjectCount++ : editSubjectCount++;

  const row = document.createElement('div');
  row.className = 'subject-row';
  row.id = `${mode}_sub_row_${index}`;
  row.style.cssText = 'display:grid;grid-template-columns:2fr 1.2fr 1.2fr 1fr auto;gap:10px;align-items:center;margin-bottom:8px;background:#fff;padding:8px 10px;border-radius:6px;border:1px solid #e2e8f0;';

  let subjectOptions = `<option value="">-- Choose or type subject --</option>`;
  allSubjects.forEach(s => {
    const sel = (s.name.toLowerCase() === (subjectName || '').toLowerCase()) ? 'selected' : '';
    subjectOptions += `<option value="${escapeHtml(s.name)}" ${sel}>${escapeHtml(s.name)} (${escapeHtml(s.code)})</option>`;
  });

  row.innerHTML = `
    <div>
      <input type="text" name="subjects[${index}][subject_name]" value="${escapeHtml(subjectName)}" list="${mode}_subjects_list_${index}" class="form-control form-control-sm sub-name-input" placeholder="Subject Name..." required style="font-size:0.85rem;" oninput="recalculateSummary('${mode}')">
      <datalist id="${mode}_subjects_list_${index}">
        ${allSubjects.map(s => `<option value="${escapeHtml(s.name)}">${escapeHtml(s.name)} (${escapeHtml(s.code)})</option>`).join('')}
      </datalist>
    </div>
    <div>
      <input type="number" step="0.01" name="subjects[${index}][obtained_marks]" value="${obtained}" class="form-control form-control-sm sub-obtained-input" placeholder="Obtained" required min="0" style="font-size:0.85rem;" oninput="recalculateSummary('${mode}')">
    </div>
    <div>
      <input type="number" step="0.01" name="subjects[${index}][total_marks]" value="${total || 100}" class="form-control form-control-sm sub-total-input" placeholder="Total" required min="1" style="font-size:0.85rem;" oninput="recalculateSummary('${mode}')">
    </div>
    <div style="text-align:center;">
      <span class="sub-grade-badge badge" style="background:#f1f5f9;color:#334155;font-size:0.75rem;padding:3px 6px;">-</span>
    </div>
    <div>
      <button type="button" onclick="removeSubjectRow('${row.id}', '${mode}')" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:1.1rem;padding:2px 4px;" title="Remove Subject">
        <i class="bi bi-trash"></i>
      </button>
    </div>
  `;

  container.appendChild(row);
  recalculateSummary(mode);
}

function removeSubjectRow(rowId, mode) {
  const container = document.getElementById(mode + 'SubjectsContainer');
  if (container.children.length <= 1) {
    alert('At least one subject is required.');
    return;
  }
  const el = document.getElementById(rowId);
  if (el) el.remove();
  recalculateSummary(mode);
}

function recalculateSummary(mode) {
  const container = document.getElementById(mode + 'SubjectsContainer');
  const rows = container.querySelectorAll('.subject-row');

  let totalObtained = 0;
  let totalMarks = 0;
  let gpaList = [];
  let hasFailed = false;

  rows.forEach(row => {
    const obtainedInput = row.querySelector('.sub-obtained-input');
    const totalInput = row.querySelector('.sub-total-input');
    const gradeBadge = row.querySelector('.sub-grade-badge');

    const obt = parseFloat(obtainedInput ? obtainedInput.value : 0) || 0;
    const tot = parseFloat(totalInput ? totalInput.value : 100) || 100;

    totalObtained += obt;
    totalMarks += tot;

    const pct = tot > 0 ? (obt / tot) * 100 : 0;
    const { gpa, grade } = getGpaGrade(pct);

    gpaList.push(gpa);
    if (gpa === 0) hasFailed = true;

    if (gradeBadge) {
      gradeBadge.innerText = `${grade} (${gpa.toFixed(1)})`;
      gradeBadge.style.background = grade === 'F' ? '#fee2e2' : '#dcfce7';
      gradeBadge.style.color = grade === 'F' ? '#991b1b' : '#15803d';
    }
  });

  const overallPct = totalMarks > 0 ? ((totalObtained / totalMarks) * 100).toFixed(1) : 0;
  let overallGpa = 0.00;
  let overallGrade = 'F';

  if (hasFailed || gpaList.length === 0) {
    overallGpa = 0.00;
    overallGrade = 'F';
  } else {
    overallGpa = (gpaList.reduce((a, b) => a + b, 0) / gpaList.length).toFixed(2);
    overallGrade = getOverallGrade(parseFloat(overallGpa));
  }

  document.getElementById(mode + 'SummaryTotalMarks').innerText = `${totalObtained.toFixed(1)} / ${totalMarks.toFixed(0)}`;
  document.getElementById(mode + 'SummaryPercentage').innerText = `(${overallPct}%)`;
  
  const gpaEl = document.getElementById(mode + 'SummaryGpa');
  gpaEl.innerText = overallGpa;
  gpaEl.style.color = overallGrade === 'F' ? '#dc2626' : '#059669';

  const gradeEl = document.getElementById(mode + 'SummaryGrade');
  gradeEl.innerText = overallGrade;
  gradeEl.className = 'badge ' + (overallGrade === 'A+' || overallGrade === 'A' ? 'badge-success' : (overallGrade === 'F' ? 'badge-danger' : 'badge-primary'));
}

function getGpaGrade(pct) {
  if (pct >= 80) return { gpa: 5.00, grade: 'A+' };
  if (pct >= 70) return { gpa: 4.00, grade: 'A' };
  if (pct >= 60) return { gpa: 3.50, grade: 'A-' };
  if (pct >= 50) return { gpa: 3.00, grade: 'B' };
  if (pct >= 40) return { gpa: 2.00, grade: 'C' };
  if (pct >= 33) return { gpa: 1.00, grade: 'D' };
  return { gpa: 0.00, grade: 'F' };
}

function getOverallGrade(gpa) {
  if (gpa >= 5.00) return 'A+';
  if (gpa >= 4.00) return 'A';
  if (gpa >= 3.50) return 'A-';
  if (gpa >= 3.00) return 'B';
  if (gpa >= 2.00) return 'C';
  if (gpa >= 1.00) return 'D';
  return 'F';
}

// Live Search logic
function onStudentInputFocus(mode) {
  const query = document.getElementById(mode + 'StudentSearchInput').value.trim();
  renderStudentDropdown(query, mode);
}

function onStudentSearchInput(query, mode) {
  const clearBtn = document.getElementById(mode + 'ClearSearchBtn');
  clearBtn.style.display = query.trim().length > 0 ? 'block' : 'none';
  renderStudentDropdown(query, mode);
}

function clearSearchText(mode) {
  const input = document.getElementById(mode + 'StudentSearchInput');
  input.value = '';
  document.getElementById(mode + 'ClearSearchBtn').style.display = 'none';
  input.focus();
  renderStudentDropdown('', mode);
}

function renderStudentDropdown(query, mode) {
  const dropdown = document.getElementById(mode + 'StudentDropdown');
  const q = query.toLowerCase().trim();

  let matches = [];
  if (!q) {
    matches = allStudents.slice(0, 15);
  } else {
    matches = allStudents.filter(st => {
      const name = (st.name || '').toLowerCase();
      const code = (st.student_id || '').toLowerCase();
      const cls = (st.class_name || '').toLowerCase();
      const phone = (st.phone || '').toLowerCase();
      return name.includes(q) || code.includes(q) || cls.includes(q) || phone.includes(q);
    }).slice(0, 20);
  }

  if (matches.length === 0) {
    dropdown.innerHTML = `
      <div style="padding:14px;text-align:center;color:var(--gray-500);font-size:0.85rem;">
        <i class="bi bi-person-x" style="font-size:1.4rem;display:block;margin-bottom:4px;color:var(--gray-400);"></i>
        No students found matching "<strong>${escapeHtml(query)}</strong>"
      </div>
    `;
    dropdown.style.display = 'block';
    return;
  }

  let html = `<div style="padding:6px 0;">`;
  matches.forEach(st => {
    const avatar = st.avatar ? '/' + st.avatar.replace(/^\//, '') : defaultAvatar;
    const name = escapeHtml(st.name || 'Unnamed');
    const code = escapeHtml(st.student_id || '');
    const cls = escapeHtml(st.class_name || 'No Class');
    const phone = escapeHtml(st.phone || '');

    html += `
      <div class="student-search-item" onclick='onSelectStudentClicked(${JSON.stringify(st).replace(/'/g, "&#39;")}, "${mode}")' style="display:flex;align-items:center;gap:10px;padding:8px 12px;cursor:pointer;transition:background 0.15s;border-bottom:1px solid #f3f4f6;">
        <img src="${avatar}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:1px solid var(--border);" onerror="this.src='${defaultAvatar}'">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <strong style="color:var(--gray-900);font-size:0.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</strong>
            <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:0.7rem;">#${code}</span>
          </div>
          <div style="display:flex;gap:8px;font-size:0.75rem;color:var(--gray-500);margin-top:2px;">
            <span><i class="bi bi-book"></i> ${cls}</span>
            ${phone ? `<span><i class="bi bi-telephone"></i> ${phone}</span>` : ''}
          </div>
        </div>
      </div>
    `;
  });
  html += `</div>`;

  dropdown.innerHTML = html;
  dropdown.style.display = 'block';
}

function onSelectStudentClicked(student, mode) {
  selectStudent(student, mode);
  closeStudentDropdown(mode);
}

function selectStudent(student, mode) {
  document.getElementById(mode + 'SelectedStudentId').value = student.id;
  document.getElementById(mode + 'SelectedStudentName').innerText = student.name;
  document.getElementById(mode + 'SelectedStudentCode').innerText = '#' + (student.student_id || student.id);
  document.getElementById(mode + 'SelectedStudentClass').innerText = student.class_name ? '• ' + student.class_name : '';
  
  const avatar = student.avatar ? '/' + student.avatar.replace(/^\//, '') : defaultAvatar;
  document.getElementById(mode + 'SelectedStudentAvatar').src = avatar;

  document.getElementById(mode + 'SelectedStudentCard').style.display = 'flex';
  document.getElementById(mode + 'StudentSearchBoxWrapper').style.display = 'none';
  document.getElementById(mode + 'StudentDropdown').style.display = 'none';
}

function clearStudentSelection(mode) {
  document.getElementById(mode + 'SelectedStudentId').value = '';
  document.getElementById(mode + 'SelectedStudentCard').style.display = 'none';
  
  const box = document.getElementById(mode + 'StudentSearchBoxWrapper');
  box.style.display = 'block';
  
  const input = document.getElementById(mode + 'StudentSearchInput');
  input.value = '';
  document.getElementById(mode + 'ClearSearchBtn').style.display = 'none';
  document.getElementById(mode + 'StudentDropdown').style.display = 'none';
}

function closeStudentDropdown(mode) {
  const dropdown = document.getElementById(mode + 'StudentDropdown');
  if (dropdown) dropdown.style.display = 'none';
}

document.addEventListener('click', function(e) {
  const addContainer = document.getElementById('addStudentSearchContainer');
  if (addContainer && !addContainer.contains(e.target)) {
    closeStudentDropdown('add');
  }

  const editContainer = document.getElementById('editStudentSearchContainer');
  if (editContainer && !editContainer.contains(e.target)) {
    closeStudentDropdown('edit');
  }
});

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

<style>
.student-search-item:hover {
  background: #f0f9ff !important;
}
.student-search-item:last-child {
  border-bottom: none !important;
}
</style>
@endsection
