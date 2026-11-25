@extends('layouts.admin')

@section('title', 'Role & Permission Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Role Permissions</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Role &amp; Permission Management</h2>
    <p>Configure system roles and control module permissions for instructors and staff members</p>
  </div>
</div>

@if(session('success'))
  <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif

@if(session('error'))
  <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span>{{ session('error') }}</span>
  </div>
@endif

<!-- 1. System Roles Overview Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:24px;">
  <!-- Admin Role Card -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);border-top:4px solid var(--primary);">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div style="width:42px;height:42px;background:#eff6ff;color:var(--primary);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
        <i class="bi bi-shield-lock-fill"></i>
      </div>
      <span class="badge badge-primary" style="font-size:0.72rem;">Full Access</span>
    </div>
    <h4 style="margin:0 0 2px 0;font-weight:800;">Admin</h4>
    <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-bottom:8px;">{{ $adminCount }} Accounts</span>
    <p style="margin:0;font-size:0.75rem;color:var(--gray-600);line-height:1.4;">
      Unrestricted access to every feature, accounting, admissions, settings and staff permissions.
    </p>
  </div>

  <!-- Teacher Role Card -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);border-top:4px solid #059669;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div style="width:42px;height:42px;background:#ecfdf5;color:#059669;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
        <i class="bi bi-person-badge-fill"></i>
      </div>
      <span class="badge badge-success" style="font-size:0.72rem;">Permission-Controlled</span>
    </div>
    <h4 style="margin:0 0 2px 0;font-weight:800;">Teacher / Faculty</h4>
    <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-bottom:8px;">{{ $teacherCount }} Accounts</span>
    <p style="margin:0;font-size:0.75rem;color:var(--gray-600);line-height:1.4;">
      Access granted per module. Without permissions, only their personal profile details are visible.
    </p>
  </div>

  <!-- Student Role Card -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);border-top:4px solid #d97706;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div style="width:42px;height:42px;background:#fffbeb;color:#d97706;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
        <i class="bi bi-mortarboard-fill"></i>
      </div>
      <span class="badge badge-warning" style="font-size:0.72rem;">Self-Service</span>
    </div>
    <h4 style="margin:0 0 2px 0;font-weight:800;">Student</h4>
    <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-bottom:8px;">{{ $studentCount }} Accounts</span>
    <p style="margin:0;font-size:0.75rem;color:var(--gray-600);line-height:1.4;">
      Access to personal academic marksheet, results, batch routine, attendance, and fee status.
    </p>
  </div>

  <!-- Parent Role Card -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);border-top:4px solid #7c3aed;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div style="width:42px;height:42px;background:#f5f3ff;color:#7c3aed;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
        <i class="bi bi-people-fill"></i>
      </div>
      <span class="badge badge-primary" style="background:#7c3aed;font-size:0.72rem;">Monitoring</span>
    </div>
    <h4 style="margin:0 0 2px 0;font-weight:800;">Parent / Guardian</h4>
    <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-bottom:8px;">{{ $parentCount }} Accounts</span>
    <p style="margin:0;font-size:0.75rem;color:var(--gray-600);line-height:1.4;">
      Tracks student attendance, exam scores, and fee payments from a dedicated parent portal.
    </p>
  </div>
</div>

<!-- 2. Available Permissions Overview Matrix -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:22px;margin-bottom:24px;box-shadow:var(--shadow-sm);">
  <div style="margin-bottom:16px;">
    <h4 style="margin:0 0 4px 0;font-weight:700;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-key-fill" style="color:#d97706;"></i> System Module Permissions
    </h4>
    <p style="margin:0;font-size:0.82rem;color:var(--gray-500);">
      Modules that can be selectively granted to teachers. Any module left unchecked will remain locked for that instructor.
    </p>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px;">
    @foreach($availablePermissions as $key => $perm)
      <div style="border:1px solid var(--border);border-radius:var(--radius);padding:14px;background:var(--gray-50);display:flex;gap:12px;align-items:flex-start;">
        <div style="width:36px;height:36px;border-radius:8px;background:#fff;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:{{ $perm['color'] }};font-size:1.1rem;flex-shrink:0;">
          <i class="bi {{ $perm['icon'] }}"></i>
        </div>
        <div>
          <strong style="font-size:0.88rem;color:var(--gray-900);display:block;margin-bottom:2px;">{{ $perm['name'] }}</strong>
          <span style="font-size:0.76rem;color:var(--gray-600);line-height:1.35;display:block;">{{ $perm['desc'] }}</span>
          <code style="font-size:0.7rem;color:var(--primary);margin-top:4px;display:inline-block;">code: {{ $key }}</code>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- 3. Teachers & Staff Permissions Table -->
<div class="table-wrapper">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
    <div>
      <h5 style="margin:0;font-weight:700;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-person-check-fill" style="color:var(--primary);"></i> Teacher &amp; Faculty Permission Matrix
      </h5>
      <span style="font-size:0.78rem;color:var(--gray-500);display:block;margin-top:2px;">
        Assign, edit or revoke operational permissions for coaching faculty members
      </span>
    </div>
    <span class="badge badge-primary">{{ $teachers->count() }} Teachers Enrolled</span>
  </div>

  <table class="data-table" style="margin:0;">
    <thead>
      <tr>
        <th>Faculty Member</th>
        <th>Subject &amp; Designation</th>
        <th>Login Account</th>
        <th>Active Module Permissions</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($teachers as $t)
      @php
        $perms = $t->permissions_list ?? ($t->permissions ?? []);
        if (is_string($perms)) $perms = json_decode($perms, true) ?: [];
      @endphp
      <tr>
        <td>
          <div class="avatar-cell">
            <img src="{{ asset($t->image ?: 'images/teacher_male_1_1790497096029.jpg') }}" alt="{{ $t->name }}">
            <div>
              <strong>{{ $t->name }}</strong>
              <div style="font-size:0.75rem;color:var(--gray-500);">{{ $t->qualification ?: 'Faculty' }}</div>
            </div>
          </div>
        </td>
        <td>
          <strong style="color:var(--gray-900);">{{ $t->subject }}</strong>
          @if($t->designation)
            <div style="font-size:0.75rem;color:var(--gray-500);">{{ $t->designation }}</div>
          @endif
        </td>
        <td>
          <div style="font-size:0.8rem;font-weight:600;color:var(--gray-800);">
            <i class="bi bi-envelope-at" style="color:var(--primary);"></i> {{ $t->email ?: 'No email' }}
          </div>
          <span class="badge {{ $t->status === 'Active' ? 'badge-success' : 'badge-danger' }}" style="font-size:0.68rem;padding:2px 6px;margin-top:2px;">
            {{ $t->status }}
          </span>
        </td>
        <td>
          <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:380px;">
            @if(!empty($perms) && count($perms) > 0)
              @foreach($perms as $p)
                <span class="badge badge-success" style="font-size:0.7rem;padding:3px 8px;text-transform:capitalize;">
                  <i class="bi bi-check-circle"></i> {{ $p }}
                </span>
              @endforeach
            @else
              <span class="badge badge-secondary" style="font-size:0.7rem;padding:3px 8px;color:var(--gray-600);background:#f1f5f9;">
                Details Only (No Permissions)
              </span>
            @endif
          </div>
        </td>
        <td>
          <button type="button" class="btn btn-outline btn-sm" onclick='openConfigurePermissionsModal(@json($t))'>
            <i class="bi bi-sliders"></i> Configure Permissions
          </button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" style="text-align:center;padding:36px;color:var(--gray-500);">
          No faculty members found.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- Modal: Configure Permissions -->
<div id="configurePermissionsModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:560px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:90vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-shield-check" style="color:var(--primary);"></i> Configure Role Permissions</h4>
      <button onclick="document.getElementById('configurePermissionsModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>

    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:var(--radius);padding:12px 16px;margin-bottom:18px;">
      <strong id="permModalTeacherName" style="color:var(--gray-900);font-size:0.95rem;display:block;"></strong>
      <span id="permModalTeacherEmail" style="font-size:0.8rem;color:var(--primary);"></span>
    </div>

    <form id="configurePermissionsForm" method="POST">
      @csrf
      @method('PUT')

      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <span style="font-size:0.82rem;font-weight:700;color:var(--gray-800);">Module Access Permissions:</span>
        <div style="display:flex;gap:8px;">
          <button type="button" class="btn btn-outline btn-sm" style="font-size:0.72rem;padding:2px 8px;" onclick="toggleAllPerms(true)">Select All</button>
          <button type="button" class="btn btn-outline btn-sm" style="font-size:0.72rem;padding:2px 8px;" onclick="toggleAllPerms(false)">Clear All</button>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
        @foreach($availablePermissions as $key => $perm)
          <label style="display:flex;align-items:flex-start;gap:10px;padding:10px 12px;border:1px solid var(--border);border-radius:var(--radius);background:var(--gray-50);cursor:pointer;">
            <input type="checkbox" name="permissions[]" value="{{ $key }}" id="modal_perm_{{ $key }}" style="margin-top:3px;">
            <div>
              <strong style="font-size:0.85rem;color:var(--gray-900);display:block;">
                <i class="bi {{ $perm['icon'] }}" style="color:{{ $perm['color'] }};"></i> {{ $perm['name'] }}
              </strong>
              <span style="font-size:0.75rem;color:var(--gray-600);display:block;line-height:1.3;">{{ $perm['desc'] }}</span>
            </div>
          </label>
        @endforeach
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('configurePermissionsModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Permissions</button>
      </div>
    </form>
  </div>
</div>

<script>
function openConfigurePermissionsModal(t) {
  document.getElementById('configurePermissionsModal').style.display = 'flex';
  document.getElementById('configurePermissionsForm').action = '/admin/roles-permissions/' + t.id;
  document.getElementById('permModalTeacherName').textContent = t.name || 'Faculty Member';
  document.getElementById('permModalTeacherEmail').textContent = t.email || 'No official email';

  let perms = [];
  if (Array.isArray(t.permissions_list)) {
    perms = t.permissions_list;
  } else if (Array.isArray(t.permissions)) {
    perms = t.permissions;
  } else if (typeof t.permissions === 'string') {
    try { perms = JSON.parse(t.permissions); } catch(e) { perms = []; }
  } else if (t.user && Array.isArray(t.user.permissions)) {
    perms = t.user.permissions;
  }

  ['attendance', 'routine', 'results', 'exams', 'notices', 'students'].forEach(function(p) {
    const el = document.getElementById('modal_perm_' + p);
    if (el) {
      el.checked = perms.includes(p);
    }
  });
}

function toggleAllPerms(check) {
  ['attendance', 'routine', 'results', 'exams', 'notices', 'students'].forEach(function(p) {
    const el = document.getElementById('modal_perm_' + p);
    if (el) {
      el.checked = check;
    }
  });
}
</script>
@endsection
