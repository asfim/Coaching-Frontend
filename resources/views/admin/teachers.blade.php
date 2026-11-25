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
        <th>Login &amp; Permissions</th>
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
              @if($t->designation)
                <span class="badge badge-primary" style="font-size:0.7rem;padding:2px 6px;display:inline-block;margin-top:2px;">{{ $t->designation }}</span>
              @endif
            </div>
          </div>
        </td>
        <td><strong>{{ $t->subject }}</strong></td>
        <td>
          <div style="font-size:0.78rem;font-weight:600;color:var(--gray-700);margin-bottom:4px;">
            <i class="bi bi-envelope-at" style="color:var(--primary);"></i> {{ $t->email ?: 'No email set' }}
          </div>
          @php
            $perms = $t->permissions ?? ($t->user?->permissions ?? []);
            if (is_string($perms)) $perms = json_decode($perms, true) ?: [];
          @endphp
          <div style="display:flex;flex-wrap:wrap;gap:4px;">
            @if(!empty($perms) && count($perms) > 0)
              @foreach($perms as $p)
                <span class="badge badge-secondary" style="font-size:0.68rem;padding:2px 6px;text-transform:capitalize;">
                  {{ $p }}
                </span>
              @endforeach
            @else
              <span class="badge badge-secondary" style="font-size:0.68rem;padding:2px 6px;color:var(--gray-500);background:#f1f5f9;">
                Details Only
              </span>
            @endif
          </div>
        </td>
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
            <form action="{{ route('admin.teachers.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this teacher and login account?');" style="display:inline;">
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
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:620px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-person-plus-fill" style="color:var(--primary);"></i> Add Teacher &amp; Login</h4>
      <button onclick="document.getElementById('addTeacherModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div style="margin-bottom:14px;background:var(--gray-50);padding:12px;border-radius:var(--radius);border:1px dashed var(--gray-300);">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;"><i class="bi bi-camera-fill" style="color:var(--primary);"></i> Teacher Photo / Profile Picture</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <span style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">JPG, PNG, WEBP (Max 5MB). Optional.</span>
      </div>

      <!-- Login Credentials Box -->
      <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:var(--radius);padding:14px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
          <i class="bi bi-shield-lock-fill" style="color:var(--primary);font-size:1.1rem;"></i>
          <strong style="font-size:0.88rem;color:var(--gray-900);">Teacher Login Credentials (লগইন তথ্য)</strong>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Login Email *</label>
            <input type="email" name="email" class="form-control" placeholder="teacher@edumanage.com" required>
          </div>
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Login Password *</label>
            <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
          </div>
        </div>
      </div>

      <!-- Module Access & Role Permissions Box -->
      <div style="background:#f8fafc;border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
          <i class="bi bi-key-fill" style="color:#d97706;font-size:1rem;"></i>
          <strong style="font-size:0.88rem;color:var(--gray-900);">Role Permissions (রোল পারমিশন)</strong>
        </div>
        <p style="font-size:0.75rem;color:var(--gray-500);margin:0 0 10px 0;">
          Select which modules this teacher is allowed to access. Without permissions, they only see their personal profile details.
        </p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:0.82rem;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="attendance" checked>
            <span><i class="bi bi-check2-circle" style="color:var(--success);"></i> Take Attendance</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="routine" checked>
            <span><i class="bi bi-clock" style="color:var(--primary);"></i> Class Routine</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="results">
            <span><i class="bi bi-trophy" style="color:#b45309;"></i> Exam Results / Marks</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="exams">
            <span><i class="bi bi-file-earmark-text" style="color:#6366f1;"></i> View Exams</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="notices">
            <span><i class="bi bi-megaphone" style="color:#0284c7;"></i> Post Notices</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="students">
            <span><i class="bi bi-people" style="color:#475569;"></i> View Students</span>
          </label>
        </div>
      </div>

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
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Designation</label>
          <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Lecturer">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Qualification</label>
          <input type="text" name="qualification" class="form-control" placeholder="e.g. M.Sc. BUET, Ph.D. DU">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Monthly Salary (৳)</label>
          <input type="number" step="0.01" name="salary" class="form-control" placeholder="40000">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Join Date</label>
          <input type="date" name="join_date" class="form-control" value="{{ date('Y-m-d') }}">
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
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:620px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Teacher &amp; Permissions</h4>
      <button onclick="document.getElementById('editTeacherModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editTeacherForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;background:var(--gray-50);padding:12px;border-radius:var(--radius);border:1px dashed var(--gray-300);">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;"><i class="bi bi-camera-fill" style="color:var(--primary);"></i> Change Teacher Photo</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <span style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">Leave blank to keep existing photo.</span>
      </div>

      <!-- Login Credentials Box -->
      <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:var(--radius);padding:14px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
          <i class="bi bi-shield-lock-fill" style="color:var(--primary);font-size:1.1rem;"></i>
          <strong style="font-size:0.88rem;color:var(--gray-900);">Login Credentials (লগইন তথ্য)</strong>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Login Email *</label>
            <input type="email" name="email" id="editTeacherEmail" class="form-control" required>
          </div>
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Change Password</label>
            <input type="password" name="password" id="editTeacherPassword" class="form-control" placeholder="Leave blank to keep current">
          </div>
        </div>
      </div>

      <!-- Module Access & Role Permissions Box -->
      <div style="background:#f8fafc;border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
          <i class="bi bi-key-fill" style="color:#d97706;font-size:1rem;"></i>
          <strong style="font-size:0.88rem;color:var(--gray-900);">Role Permissions (রোল পারমিশন)</strong>
        </div>
        <p style="font-size:0.75rem;color:var(--gray-500);margin:0 0 10px 0;">
          Select which modules this teacher is allowed to access.
        </p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:0.82rem;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="attendance" id="edit_perm_attendance">
            <span><i class="bi bi-check2-circle" style="color:var(--success);"></i> Take Attendance</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="routine" id="edit_perm_routine">
            <span><i class="bi bi-clock" style="color:var(--primary);"></i> Class Routine</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="results" id="edit_perm_results">
            <span><i class="bi bi-trophy" style="color:#b45309;"></i> Exam Results / Marks</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="exams" id="edit_perm_exams">
            <span><i class="bi bi-file-earmark-text" style="color:#6366f1;"></i> View Exams</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="notices" id="edit_perm_notices">
            <span><i class="bi bi-megaphone" style="color:#0284c7;"></i> Post Notices</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="students" id="edit_perm_students">
            <span><i class="bi bi-people" style="color:#475569;"></i> View Students</span>
          </label>
        </div>
      </div>

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
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Designation</label>
          <input type="text" name="designation" id="editTeacherDesignation" class="form-control">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Qualification</label>
          <input type="text" name="qualification" id="editTeacherQual" class="form-control">
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Salary (৳)</label>
          <input type="number" step="0.01" name="salary" id="editTeacherSalary" class="form-control">
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" id="editTeacherStatus" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Short Bio / Remarks</label>
        <textarea name="bio" id="editTeacherBio" class="form-control" rows="2"></textarea>
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
  document.getElementById('editTeacherPassword').value = '';
  document.getElementById('editTeacherDesignation').value = t.designation || '';
  document.getElementById('editTeacherQual').value = t.qualification || '';
  document.getElementById('editTeacherSalary').value = t.salary || '';
  document.getElementById('editTeacherStatus').value = t.status || 'Active';
  document.getElementById('editTeacherBio').value = t.bio || '';

  // Parse permissions
  let perms = [];
  if (Array.isArray(t.permissions)) {
    perms = t.permissions;
  } else if (typeof t.permissions === 'string') {
    try { perms = JSON.parse(t.permissions); } catch(e) { perms = []; }
  } else if (t.user && Array.isArray(t.user.permissions)) {
    perms = t.user.permissions;
  }

  ['attendance', 'routine', 'results', 'exams', 'notices', 'students'].forEach(function(p) {
    const el = document.getElementById('edit_perm_' + p);
    if (el) {
      el.checked = perms.includes(p);
    }
  });
}
</script>
@endsection
