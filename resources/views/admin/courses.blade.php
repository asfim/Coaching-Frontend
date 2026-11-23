@extends('layouts.admin')

@section('title', 'Course Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Courses</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Course Management</h2>
    <p>Manage curriculum, fee structures, batch capacity, and course publications</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addCourseModal').style.display='flex'"><i class="bi bi-plus-circle"></i> Add New Course</button>
</div>

<!-- Courses Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-bottom:28px;">
  @forelse($courses as $c)
  <div class="course-card" style="box-shadow:var(--shadow-sm);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;background:#fff;display:flex;flex-direction:column;">
    <div class="course-img" style="position:relative;height:180px;overflow:hidden;">
      <img src="{{ asset($c->image ?: 'images/hero_classroom_1790497065510.jpg') }}" alt="{{ $c->title }}" style="width:100%;height:100%;object-fit:cover;">
      <span class="badge badge-primary" style="position:absolute;top:12px;left:12px;">{{ $c->category }}</span>
      <span class="badge {{ $c->status === 'Active' ? 'badge-success' : 'badge-danger' }}" style="position:absolute;top:12px;right:12px;">{{ $c->status }}</span>
    </div>
    <div class="course-body" style="padding:20px;flex:1;display:flex;flex-direction:column;">
      <div class="course-meta" style="display:flex;gap:16px;margin-bottom:8px;font-size:0.8rem;color:var(--gray-500);">
        <span><i class="bi bi-clock"></i> {{ $c->duration }}</span>
        <span><i class="bi bi-people"></i> {{ $c->students_count ?? 0 }} / {{ $c->seats }} Seats</span>
        <span><i class="bi bi-collection"></i> {{ $c->batches_count ?? 0 }} Batches</span>
      </div>
      <h5 style="margin-bottom:8px;font-weight:700;">{{ $c->title }}</h5>
      <p style="font-size:0.84rem;color:var(--gray-500);flex:1;">{{ Str::limit($c->description, 90) }}</p>
    </div>
    <div class="course-footer" style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:var(--gray-50);">
      <div class="course-price" style="font-size:1.25rem;font-weight:800;color:var(--primary);">
        ৳{{ number_format($c->fee, 0) }}<span style="font-size:0.75rem;font-weight:400;color:var(--gray-500);">/mo</span>
      </div>
      <div style="display:flex;gap:8px;">
        <button class="btn btn-outline btn-sm" onclick='openEditCourseModal(@json($c))' title="Edit"><i class="bi bi-pencil"></i> Edit</button>
        <form action="{{ route('admin.courses.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Delete this course?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--border);" title="Delete"><i class="bi bi-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div style="grid-column:1/-1;text-align:center;padding:40px;background:#fff;border-radius:var(--radius-lg);border:1px solid var(--border);">
    <p style="color:var(--gray-500);">No courses found. Click "Add New Course" to create one.</p>
  </div>
  @endforelse
</div>

@if($courses->hasPages())
  <div style="margin-bottom:24px;">
    {{ $courses->links() }}
  </div>
@endif

<!-- Add Course Modal -->
<div id="addCourseModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-plus-circle" style="color:var(--primary);"></i> Add Course</h4>
      <button onclick="document.getElementById('addCourseModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.courses.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Course Title *</label>
        <input type="text" name="title" class="form-control" placeholder="e.g. SSC Science Complete Batch 2026" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category *</label>
          <select name="category" class="form-select" required>
            <option value="Science">Science</option>
            <option value="HSC">HSC</option>
            <option value="SSC">SSC</option>
            <option value="Admission">Admission</option>
            <option value="Commerce">Commerce</option>
            <option value="Arts">Arts</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Duration *</label>
          <input type="text" name="duration" class="form-control" placeholder="e.g. 12 Months" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Monthly Fee (৳) *</label>
          <input type="number" step="0.01" name="fee" class="form-control" placeholder="2500" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Total Seats *</label>
          <input type="number" name="seats" class="form-control" placeholder="60" required>
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
        <textarea name="description" class="form-control" rows="2" placeholder="Syllabus, subjects, target examinees..."></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addCourseModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Course</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Course Modal -->
<div id="editCourseModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Course</h4>
      <button onclick="document.getElementById('editCourseModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editCourseForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Course Title *</label>
        <input type="text" name="title" id="editCourseTitle" class="form-control" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category *</label>
          <select name="category" id="editCourseCategory" class="form-select" required>
            <option value="Science">Science</option>
            <option value="HSC">HSC</option>
            <option value="SSC">SSC</option>
            <option value="Admission">Admission</option>
            <option value="Commerce">Commerce</option>
            <option value="Arts">Arts</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Duration *</label>
          <input type="text" name="duration" id="editCourseDuration" class="form-control" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Monthly Fee (৳) *</label>
          <input type="number" step="0.01" name="fee" id="editCourseFee" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Total Seats *</label>
          <input type="number" name="seats" id="editCourseSeats" class="form-control" required>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" id="editCourseStatus" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description</label>
        <textarea name="description" id="editCourseDesc" class="form-control" rows="2"></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('editCourseModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Course</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditCourseModal(c) {
  document.getElementById('editCourseModal').style.display = 'flex';
  document.getElementById('editCourseForm').action = '/admin/courses/' + c.id;
  document.getElementById('editCourseTitle').value = c.title || '';
  document.getElementById('editCourseCategory').value = c.category || 'Science';
  document.getElementById('editCourseDuration').value = c.duration || '';
  document.getElementById('editCourseFee').value = c.fee || '';
  document.getElementById('editCourseSeats').value = c.seats || '';
  document.getElementById('editCourseStatus').value = c.status || 'Active';
  document.getElementById('editCourseDesc').value = c.description || '';
}
</script>
@endsection
