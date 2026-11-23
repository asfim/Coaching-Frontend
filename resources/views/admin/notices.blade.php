@extends('layouts.admin')

@section('title', 'Notice Board Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Notices</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Notice Board Management</h2>
    <p>Publish instant announcements, exam circulars, and holiday notifications</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addNoticeModal').style.display='flex'"><i class="bi bi-megaphone-fill"></i> Post New Notice</button>
</div>

<!-- Notices Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;margin-bottom:28px;">
  @forelse($notices as $n)
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
      <span class="badge {{ $n->category === 'urgent' ? 'badge-danger' : ($n->category === 'exam' ? 'badge-warning' : 'badge-primary') }}" style="text-transform:uppercase;font-size:0.75rem;">
        {{ $n->category }}
      </span>
      <span style="font-size:0.8rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($n->published_at)->format('d M, Y') }}</span>
    </div>

    <h5 style="margin-bottom:8px;font-weight:700;color:var(--gray-900);">{{ $n->title }}</h5>
    <p style="font-size:0.85rem;color:var(--gray-600);flex:1;line-height:1.5;">{{ $n->content }}</p>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:14px;border-top:1px solid var(--border);">
      <span style="font-size:0.75rem;color:var(--gray-500);"><i class="bi bi-people"></i> Target: {{ ucfirst($n->target_audience) }}</span>
      <div style="display:flex;gap:6px;">
        <button class="btn btn-outline btn-sm" onclick='openEditNoticeModal(@json($n))' title="Edit"><i class="bi bi-pencil"></i></button>
        <form action="{{ route('admin.notices.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Delete this notice?');" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div style="grid-column:1/-1;text-align:center;padding:40px;background:#fff;border-radius:var(--radius-lg);border:1px solid var(--border);">
    <p style="color:var(--gray-500);">No notices published yet.</p>
  </div>
  @endforelse
</div>

@if($notices->hasPages())
  <div>{{ $notices->links() }}</div>
@endif

<!-- Add Notice Modal -->
<div id="addNoticeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-megaphone-fill" style="color:var(--primary);"></i> Post Notice</h4>
      <button onclick="document.getElementById('addNoticeModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.notices.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Notice Title *</label>
        <input type="text" name="title" class="form-control" placeholder="e.g. SSC Model Test Routine Published" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category *</label>
          <select name="category" class="form-select" required>
            <option value="general">General</option>
            <option value="urgent">Urgent</option>
            <option value="exam">Exam</option>
            <option value="fee">Fee &amp; Payment</option>
            <option value="holiday">Holiday</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Target Audience *</label>
          <select name="target_audience" class="form-select" required>
            <option value="all">Everyone</option>
            <option value="students">Students Only</option>
            <option value="parents">Parents Only</option>
            <option value="teachers">Teachers Only</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Publish Date *</label>
          <input type="date" name="published_at" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" class="form-select" required>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Notice Body / Details *</label>
        <textarea name="content" class="form-control" rows="4" placeholder="Write notice content..." required></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addNoticeModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Publish Notice</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Notice Modal -->
<div id="editNoticeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Notice</h4>
      <button onclick="document.getElementById('editNoticeModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editNoticeForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Notice Title *</label>
        <input type="text" name="title" id="editNoticeTitle" class="form-control" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category *</label>
          <select name="category" id="editNoticeCategory" class="form-select" required>
            <option value="general">General</option>
            <option value="urgent">Urgent</option>
            <option value="exam">Exam</option>
            <option value="fee">Fee &amp; Payment</option>
            <option value="holiday">Holiday</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Target Audience *</label>
          <select name="target_audience" id="editNoticeAudience" class="form-select" required>
            <option value="all">Everyone</option>
            <option value="students">Students Only</option>
            <option value="parents">Parents Only</option>
            <option value="teachers">Teachers Only</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Publish Date *</label>
          <input type="date" name="published_at" id="editNoticeDate" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
          <select name="status" id="editNoticeStatus" class="form-select" required>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Notice Body *</label>
        <textarea name="content" id="editNoticeContent" class="form-control" rows="4" required></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editNoticeModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Notice</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditNoticeModal(n) {
  document.getElementById('editNoticeModal').style.display = 'flex';
  document.getElementById('editNoticeForm').action = '/admin/notices/' + n.id;
  document.getElementById('editNoticeTitle').value = n.title || '';
  document.getElementById('editNoticeCategory').value = n.category || 'general';
  document.getElementById('editNoticeAudience').value = n.target_audience || 'all';
  document.getElementById('editNoticeDate').value = n.published_at ? n.published_at.substring(0, 10) : '';
  document.getElementById('editNoticeStatus').value = n.status || 'published';
  document.getElementById('editNoticeContent').value = n.content || '';
}
</script>
@endsection
