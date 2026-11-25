@extends('layouts.admin')

@section('title', 'Role & Permission Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Role Permissions</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Role &amp; Permission Management</h2>
    <p>Configure system roles and control menu, edit &amp; delete permissions for instructors and staff members</p>
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
      <span class="badge badge-primary" style="font-size:0.72rem;">Full Access (Superadmin)</span>
    </div>
    <h4 style="margin:0 0 2px 0;font-weight:800;">Admin</h4>
    <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-bottom:8px;">{{ $adminCount }} Accounts</span>
    <p style="margin:0;font-size:0.75rem;color:var(--gray-600);line-height:1.4;">
      Unrestricted access to every menu, view, edit, delete, accounting, settings and staff permissions.
    </p>
  </div>

  <!-- Teacher Role Card -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);border-top:4px solid #059669;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
      <div style="width:42px;height:42px;background:#ecfdf5;color:#059669;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
        <i class="bi bi-person-badge-fill"></i>
      </div>
      <span class="badge badge-success" style="font-size:0.72rem;">Granular CRUD Controlled</span>
    </div>
    <h4 style="margin:0 0 2px 0;font-weight:800;">Teacher / Faculty</h4>
    <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-bottom:8px;">{{ $teacherCount }} Accounts</span>
    <p style="margin:0;font-size:0.75rem;color:var(--gray-600);line-height:1.4;">
      Granular Menu View, Edit &amp; Delete permissions. Without permissions, only their profile and assigned classes are visible.
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
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
    <div>
      <h4 style="margin:0 0 4px 0;font-weight:700;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-shield-check" style="color:var(--primary);"></i> All System Menus &amp; Granular CRUD Permissions
      </h4>
      <p style="margin:0;font-size:0.82rem;color:var(--gray-500);">
        Configure Menu Access (View), Data Modification (Edit) and Record Removal (Delete) permissions independently.
      </p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;">
      <span class="badge badge-primary" style="font-size:0.78rem;padding:6px 12px;">{{ count($availablePermissions) }} Menus Registered</span>
      <span class="badge badge-success" style="font-size:0.78rem;padding:6px 12px;"><i class="bi bi-pencil"></i> Edit Supported</span>
      <span class="badge badge-danger" style="font-size:0.78rem;padding:6px 12px;"><i class="bi bi-trash"></i> Delete Supported</span>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:12px;">
    @foreach($availablePermissions as $key => $perm)
      <div style="border:1px solid var(--border);border-radius:var(--radius);padding:12px;background:var(--gray-50);display:flex;gap:12px;align-items:flex-start;">
        <div style="width:38px;height:38px;border-radius:8px;background:#fff;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:{{ $perm['color'] }};font-size:1.15rem;flex-shrink:0;">
          <i class="bi {{ $perm['icon'] }}"></i>
        </div>
        <div style="flex:1;min-width:0;">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:4px;margin-bottom:3px;">
            <strong style="font-size:0.85rem;color:var(--gray-900);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $perm['name'] }}</strong>
            <span class="badge badge-secondary" style="font-size:0.65rem;padding:2px 5px;">{{ $perm['category'] ?? 'Menu' }}</span>
          </div>
          <span style="font-size:0.74rem;color:var(--gray-600);line-height:1.3;display:block;margin-bottom:6px;">{{ $perm['desc'] }}</span>
          <div style="display:flex;gap:4px;flex-wrap:wrap;">
            <span class="badge badge-primary" style="font-size:0.65rem;padding:2px 6px;">View: {{ $key }}</span>
            @if(in_array('edit', $perm['actions']))
              <span class="badge badge-warning" style="font-size:0.65rem;padding:2px 6px;color:#92400e;background:#fef3c7;"><i class="bi bi-pencil"></i> Edit: {{ $key }}.edit</span>
            @endif
            @if(in_array('delete', $perm['actions']))
              <span class="badge badge-danger" style="font-size:0.65rem;padding:2px 6px;color:#991b1b;background:#fee2e2;"><i class="bi bi-trash"></i> Delete: {{ $key }}.delete</span>
            @endif
          </div>
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
        Assign, edit or revoke menu, edit &amp; delete permissions for coaching faculty members
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
        <th>Active Permissions (View / Edit / Delete)</th>
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
          <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:480px;">
            @if(!empty($perms) && count($perms) > 0)
              @foreach($perms as $p)
                @if(str_ends_with($p, '.delete'))
                  <span class="badge badge-danger" style="font-size:0.68rem;padding:3px 7px;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">
                    <i class="bi bi-trash"></i> {{ $p }}
                  </span>
                @elseif(str_ends_with($p, '.edit'))
                  <span class="badge badge-warning" style="font-size:0.68rem;padding:3px 7px;background:#fef3c7;color:#92400e;border:1px solid #fde68a;">
                    <i class="bi bi-pencil"></i> {{ $p }}
                  </span>
                @else
                  <span class="badge badge-success" style="font-size:0.68rem;padding:3px 7px;">
                    <i class="bi bi-check-circle"></i> {{ $p }}
                  </span>
                @endif
              @endforeach
            @else
              <span class="badge badge-secondary" style="font-size:0.7rem;padding:3px 8px;color:var(--gray-600);background:#f1f5f9;">
                Profile Only (No Permissions)
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

<!-- Modal: Configure Permissions with View, Edit & Delete Matrix -->
<div id="configurePermissionsModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:780px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:92vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-shield-check" style="color:var(--primary);"></i> Configure Menu, Edit &amp; Delete Permissions</h4>
      <button onclick="document.getElementById('configurePermissionsModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>

    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:var(--radius);padding:12px 16px;margin-bottom:16px;">
      <strong id="permModalTeacherName" style="color:var(--gray-900);font-size:0.95rem;display:block;"></strong>
      <span id="permModalTeacherEmail" style="font-size:0.8rem;color:var(--primary);"></span>
    </div>

    <form id="configurePermissionsForm" method="POST">
      @csrf
      @method('PUT')

      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:12px;background:#f8fafc;padding:10px 14px;border-radius:var(--radius);border:1px solid var(--border);">
        <span style="font-size:0.82rem;font-weight:700;color:var(--gray-800);">Quick Selection (দ্রুত বাছাই):</span>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <button type="button" class="btn btn-outline btn-sm" style="font-size:0.72rem;padding:3px 8px;" onclick="toggleAllMatrix(true)">Select All</button>
          <button type="button" class="btn btn-outline btn-sm" style="font-size:0.72rem;padding:3px 8px;" onclick="toggleAllMatrix(false)">Clear All</button>
          <button type="button" class="btn btn-outline btn-sm" style="font-size:0.72rem;padding:3px 8px;" onclick="toggleOnlyView()">View Only</button>
          <button type="button" class="btn btn-outline btn-sm" style="font-size:0.72rem;padding:3px 8px;" onclick="toggleOnlyAction('edit')">All Edit</button>
          <button type="button" class="btn btn-outline btn-sm" style="font-size:0.72rem;padding:3px 8px;" onclick="toggleOnlyAction('delete')">All Delete</button>
        </div>
      </div>

      <!-- Permission Matrix Table -->
      <div style="border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:20px;">
        <table style="width:100%;border-collapse:collapse;font-size:0.82rem;">
          <thead>
            <tr style="background:#f1f5f9;border-bottom:1px solid var(--border);text-align:left;">
              <th style="padding:10px 14px;font-weight:700;">System Module</th>
              <th style="padding:10px 12px;text-align:center;font-weight:700;width:110px;">
                <label style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                  <i class="bi bi-eye"></i> View
                </label>
              </th>
              <th style="padding:10px 12px;text-align:center;font-weight:700;width:110px;">
                <label style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;color:#92400e;">
                  <i class="bi bi-pencil"></i> Edit
                </label>
              </th>
              <th style="padding:10px 12px;text-align:center;font-weight:700;width:110px;">
                <label style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;color:#991b1b;">
                  <i class="bi bi-trash"></i> Delete
                </label>
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($availablePermissions as $key => $perm)
            <tr style="border-bottom:1px solid var(--border);background:#fff;">
              <td style="padding:10px 14px;">
                <div style="display:flex;align-items:center;gap:8px;">
                  <i class="bi {{ $perm['icon'] }}" style="color:{{ $perm['color'] }};font-size:1.05rem;"></i>
                  <div>
                    <strong style="color:var(--gray-900);display:block;font-size:0.84rem;">{{ $perm['name'] }}</strong>
                    <span style="color:var(--gray-500);font-size:0.72rem;">{{ $perm['category'] ?? 'Module' }}</span>
                  </div>
                </div>
              </td>
              <!-- View Checkbox -->
              <td style="padding:10px 12px;text-align:center;">
                <input type="checkbox" name="permissions[]" value="{{ $key }}" id="modal_perm_{{ $key }}" class="perm-chk perm-view">
              </td>
              <!-- Edit Checkbox -->
              <td style="padding:10px 12px;text-align:center;">
                @if(in_array('edit', $perm['actions']))
                  <input type="checkbox" name="permissions[]" value="{{ $key }}.edit" id="modal_perm_{{ $key }}_edit" class="perm-chk perm-edit">
                @else
                  <span style="color:var(--gray-400);font-size:0.75rem;">—</span>
                @endif
              </td>
              <!-- Delete Checkbox -->
              <td style="padding:10px 12px;text-align:center;">
                @if(in_array('delete', $perm['actions']))
                  <input type="checkbox" name="permissions[]" value="{{ $key }}.delete" id="modal_perm_{{ $key }}_delete" class="perm-chk perm-delete">
                @else
                  <span style="color:var(--gray-400);font-size:0.75rem;">—</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('configurePermissionsModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Permissions</button>
      </div>
    </form>
  </div>
</div>

<script>
const allPermMeta = @json($availablePermissions);

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

  // Clear all checkboxes first
  document.querySelectorAll('#configurePermissionsModal .perm-chk').forEach(function(el) {
    el.checked = false;
  });

  // Check matching permissions
  Object.keys(allPermMeta).forEach(function(k) {
    const vEl = document.getElementById('modal_perm_' + k);
    if (vEl) vEl.checked = perms.includes(k);

    const eEl = document.getElementById('modal_perm_' + k + '_edit');
    if (eEl) eEl.checked = perms.includes(k + '.edit');

    const dEl = document.getElementById('modal_perm_' + k + '_delete');
    if (dEl) dEl.checked = perms.includes(k + '.delete');
  });
}

function toggleAllMatrix(check) {
  document.querySelectorAll('#configurePermissionsModal .perm-chk').forEach(function(el) {
    el.checked = check;
  });
}

function toggleOnlyView() {
  document.querySelectorAll('#configurePermissionsModal .perm-view').forEach(function(el) {
    el.checked = true;
  });
  document.querySelectorAll('#configurePermissionsModal .perm-edit, #configurePermissionsModal .perm-delete').forEach(function(el) {
    el.checked = false;
  });
}

function toggleOnlyAction(action) {
  document.querySelectorAll('#configurePermissionsModal .perm-' + action).forEach(function(el) {
    el.checked = true;
  });
  // Also ensure their view permissions are active
  document.querySelectorAll('#configurePermissionsModal .perm-view').forEach(function(el) {
    el.checked = true;
  });
}
</script>
@endsection
