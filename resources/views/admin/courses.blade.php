@extends('layouts.admin')

@section('title', 'Course Management - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Courses</span>
@endsection

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
  <div>
    <h2 style="margin:0 0 4px 0;font-weight:800;font-size:1.6rem;">Course Management</h2>
    <p style="margin:0;color:var(--gray-500);font-size:0.9rem;">Manage courses, syllabus, pricing, and dynamic course categories</p>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <button class="btn btn-outline" onclick="document.getElementById('manageCategoriesModal').style.display='flex'">
      <i class="bi bi-tags"></i> Manage Categories ({{ $categories->count() }})
    </button>
    <button class="btn btn-primary" onclick="document.getElementById('addCourseModal').style.display='flex'">
      <i class="bi bi-plus-circle"></i> Add New Course
    </button>
  </div>
</div>

<!-- Filter Toolbar -->
<div style="background:#fff;padding:16px 20px;border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:24px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;box-shadow:var(--shadow-sm);">
  <form method="GET" action="{{ route('admin.courses') }}" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;flex:1;">
    <label style="font-size:0.85rem;font-weight:600;color:var(--gray-600);white-space:nowrap;"><i class="bi bi-funnel"></i> Category:</label>
    <div style="min-width:220px;">
      <select name="category" class="form-select" onchange="this.form.submit()" style="padding:6px 12px;font-size:0.88rem;">
        <option value="">All Categories ({{ $categories->count() }})</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->name }}" {{ request('category') === $cat->name ? 'selected' : '' }}>
            {{ $cat->name }} ({{ $cat->courses_count ?? 0 }})
          </option>
        @endforeach
      </select>
    </div>
    @if(request('category'))
      <a href="{{ route('admin.courses') }}" class="btn btn-outline btn-sm" style="color:var(--gray-600);padding:6px 12px;">
        <i class="bi bi-x-circle"></i> Clear Filter
      </a>
    @endif
  </form>

  <div style="display:flex;align-items:center;gap:12px;">
    <span style="font-size:0.85rem;color:var(--gray-500);">Showing <strong>{{ $courses->total() }}</strong> course(s)</span>
    <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('quickAddCatModal').style.display='flex'" title="Add a new category quickly">
      <i class="bi bi-plus-lg"></i> New Category
    </button>
  </div>
</div>

<!-- Courses Grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;margin-bottom:28px;">
  @forelse($courses as $c)
  <div class="course-card" style="box-shadow:var(--shadow-sm);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;background:#fff;display:flex;flex-direction:column;transition:transform 0.2s, box-shadow 0.2s;">
    <div class="course-img" style="position:relative;height:180px;overflow:hidden;background:var(--gray-100);">
      <img src="{{ asset($c->image ?: 'images/hero_classroom_1790497065510.jpg') }}" alt="{{ $c->title }}" style="width:100%;height:100%;object-fit:cover;">
      <span class="badge badge-primary" style="position:absolute;top:12px;left:12px;box-shadow:0 2px 4px rgba(0,0,0,0.15);">
        <i class="bi bi-tag-fill" style="font-size:0.7rem;margin-right:3px;"></i> {{ $c->category }}
      </span>
      <span class="badge {{ $c->status === 'Active' ? 'badge-success' : 'badge-danger' }}" style="position:absolute;top:12px;right:12px;box-shadow:0 2px 4px rgba(0,0,0,0.15);">
        {{ $c->status }}
      </span>
    </div>
    <div class="course-body" style="padding:20px;flex:1;display:flex;flex-direction:column;">
      <div class="course-meta" style="display:flex;gap:14px;margin-bottom:10px;font-size:0.8rem;color:var(--gray-500);flex-wrap:wrap;">
        <span><i class="bi bi-clock"></i> {{ $c->duration }}</span>
        <span><i class="bi bi-people"></i> {{ $c->students_count ?? 0 }} / {{ $c->seats }} Seats</span>
        <span><i class="bi bi-collection"></i> {{ $c->batches_count ?? 0 }} Batches</span>
      </div>
      <h5 style="margin-bottom:8px;font-weight:700;font-size:1.05rem;line-height:1.4;">{{ $c->title }}</h5>
      <p style="font-size:0.84rem;color:var(--gray-500);flex:1;line-height:1.5;">{{ Str::limit($c->description, 90) }}</p>
    </div>
    <div class="course-footer" style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:var(--gray-50);">
      <div class="course-price" style="font-size:1.25rem;font-weight:800;color:var(--primary);">
        ৳{{ number_format($c->fee, 0) }}<span style="font-size:0.75rem;font-weight:400;color:var(--gray-500);">/mo</span>
      </div>
      <div style="display:flex;gap:8px;">
        <button class="btn btn-outline btn-sm" onclick='openEditCourseModal(@json($c))' title="Edit Course"><i class="bi bi-pencil"></i> Edit</button>
        <form action="{{ route('admin.courses.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" style="margin:0;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--border);" title="Delete Course"><i class="bi bi-trash"></i></button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div style="grid-column:1/-1;text-align:center;padding:50px 20px;background:#fff;border-radius:var(--radius-lg);border:1px dashed var(--gray-300);">
    <i class="bi bi-journal-x" style="font-size:2.5rem;color:var(--gray-400);display:block;margin-bottom:12px;"></i>
    <h5 style="color:var(--gray-700);margin-bottom:6px;">No courses found</h5>
    <p style="color:var(--gray-500);font-size:0.88rem;margin-bottom:16px;">Try clearing category filters or add a new course.</p>
    <button class="btn btn-primary btn-sm" onclick="document.getElementById('addCourseModal').style.display='flex'"><i class="bi bi-plus-circle"></i> Add New Course</button>
  </div>
  @endforelse
</div>

@if($courses->hasPages())
  <div style="margin-bottom:28px;">
    {{ $courses->links() }}
  </div>
@endif

<!-- Add Course Modal -->
<div id="addCourseModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-plus-circle" style="color:var(--primary);"></i> Add Course</h4>
      <button onclick="document.getElementById('addCourseModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div style="margin-bottom:14px;background:var(--gray-50);padding:12px;border-radius:var(--radius);border:1px dashed var(--gray-300);">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;"><i class="bi bi-image" style="color:var(--primary);"></i> Course Cover Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <span style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">PNG, JPG, WEBP (Max 5MB). Optional.</span>
      </div>
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Course Title *</label>
        <input type="text" name="title" class="form-control" placeholder="e.g. SSC Science Complete Batch 2026" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Category *</label>
            <a href="javascript:void(0)" onclick="openQuickAddCat()" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ New Category</a>
          </div>
          <select name="category" id="addCourseCategorySelect" class="form-select" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->name }}">{{ $cat->name }}</option>
            @endforeach
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
<div id="editCourseModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Course</h4>
      <button onclick="document.getElementById('editCourseModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editCourseForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div style="margin-bottom:14px;background:var(--gray-50);padding:12px;border-radius:var(--radius);border:1px dashed var(--gray-300);">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;"><i class="bi bi-image" style="color:var(--primary);"></i> Change Cover Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <span style="font-size:0.72rem;color:var(--gray-500);display:block;margin-top:2px;">Leave blank to keep existing image.</span>
      </div>
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Course Title *</label>
        <input type="text" name="title" id="editCourseTitle" class="form-control" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <label style="font-size:0.82rem;font-weight:600;margin:0;">Category *</label>
            <a href="javascript:void(0)" onclick="openQuickAddCat()" style="font-size:0.75rem;color:var(--primary);text-decoration:none;font-weight:600;">+ New Category</a>
          </div>
          <select name="category" id="editCourseCategory" class="form-select" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->name }}">{{ $cat->name }}</option>
            @endforeach
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

<!-- Manage Categories Modal -->
<div id="manageCategoriesModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;overflow-y:auto;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:760px;padding:26px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;border-bottom:1px solid var(--border);padding-bottom:14px;">
      <div>
        <h4 style="margin:0 0 4px 0;font-weight:800;color:var(--gray-900);"><i class="bi bi-tags" style="color:var(--primary);"></i> Course Categories</h4>
        <p style="margin:0;font-size:0.84rem;color:var(--gray-500);">Add, edit, or remove course categories. Courses are dynamically categorized by these tags.</p>
      </div>
      <button onclick="document.getElementById('manageCategoriesModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>

    <!-- Quick Add Category Form -->
    <div style="background:var(--gray-50);border:1px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:20px;">
      <h6 style="margin:0 0 10px 0;font-weight:700;font-size:0.9rem;"><i class="bi bi-plus-circle" style="color:var(--primary);"></i> Add New Category</h6>
      <form action="{{ route('admin.course.categories.store') }}" method="POST" style="display:grid;grid-template-columns:1fr 1.5fr auto;gap:10px;align-items:end;">
        @csrf
        <div>
          <label style="display:block;font-size:0.78rem;font-weight:600;margin-bottom:4px;">Category Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. English Medium, Olympiad" required style="padding:6px 10px;font-size:0.85rem;">
        </div>
        <div>
          <label style="display:block;font-size:0.78rem;font-weight:600;margin-bottom:4px;">Description (Optional)</label>
          <input type="text" name="description" class="form-control" placeholder="Brief details about curriculum..." style="padding:6px 10px;font-size:0.85rem;">
        </div>
        <div>
          <button type="submit" class="btn btn-primary" style="padding:7px 16px;font-size:0.85rem;white-space:nowrap;">
            <i class="bi bi-plus-lg"></i> Add Category
          </button>
        </div>
      </form>
    </div>

    <!-- Categories List Table -->
    <div class="table-responsive" style="max-height:360px;overflow-y:auto;border:1px solid var(--border);border-radius:var(--radius);">
      <table class="table" style="margin:0;">
        <thead>
          <tr style="background:var(--gray-100);position:sticky;top:0;z-index:2;">
            <th>Category Name</th>
            <th>Slug</th>
            <th style="text-align:center;">Courses</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $cat)
          <tr>
            <td>
              <strong style="color:var(--gray-900);font-size:0.9rem;">{{ $cat->name }}</strong>
              @if($cat->description)
                <div style="font-size:0.75rem;color:var(--gray-500);margin-top:2px;">{{ Str::limit($cat->description, 50) }}</div>
              @endif
            </td>
            <td><code style="font-size:0.75rem;background:var(--gray-100);padding:2px 6px;border-radius:4px;">{{ $cat->slug }}</code></td>
            <td style="text-align:center;">
              <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;">{{ $cat->courses_count ?? 0 }}</span>
            </td>
            <td style="text-align:center;">
              <span class="badge {{ $cat->status === 'Active' ? 'badge-success' : 'badge-danger' }}">{{ $cat->status }}</span>
            </td>
            <td style="text-align:right;">
              <div style="display:inline-flex;gap:6px;">
                <button type="button" class="btn btn-outline btn-sm" onclick='openEditCategoryModal(@json($cat))' title="Edit Category" style="padding:4px 8px;font-size:0.78rem;">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="{{ route('admin.course.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete category &quot;{{ $cat->name }}&quot;? Linked courses will have unassigned category.');" style="margin:0;display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--border);padding:4px 8px;font-size:0.78rem;" title="Delete Category">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;padding:24px;color:var(--gray-500);">No categories found. Create your first category above.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top:16px;display:flex;justify-content:flex-end;">
      <button type="button" class="btn btn-outline" onclick="document.getElementById('manageCategoriesModal').style.display='none'">Close</button>
    </div>
  </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1150;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:440px;padding:22px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
      <h5 style="margin:0;font-weight:700;"><i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Course Category</h5>
      <button onclick="document.getElementById('editCategoryModal').style.display='none'" style="background:none;border:none;font-size:1.2rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editCategoryForm" method="POST">
      @csrf
      @method('PUT')
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category Name *</label>
        <input type="text" name="name" id="editCatName" class="form-control" required>
      </div>
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Status *</label>
        <select name="status" id="editCatStatus" class="form-select" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>
      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description</label>
        <textarea name="description" id="editCatDescription" class="form-control" rows="2"></textarea>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('editCategoryModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle"></i> Update Category</button>
      </div>
    </form>
  </div>
</div>

<!-- Quick Add Category Modal (used from within Course Modal) -->
<div id="quickAddCatModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1200;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:420px;padding:22px;box-shadow:var(--shadow-xl);position:relative;margin:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
      <h5 style="margin:0;font-weight:700;"><i class="bi bi-tag" style="color:var(--primary);"></i> Quick Add Category</h5>
      <button onclick="document.getElementById('quickAddCatModal').style.display='none'" style="background:none;border:none;font-size:1.2rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.course.categories.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:12px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category Name *</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. BCS & Job Preparation" required>
      </div>
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description (Optional)</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Brief note..."></textarea>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:8px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('quickAddCatModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle"></i> Save Category</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditCourseModal(c) {
  document.getElementById('editCourseModal').style.display = 'flex';
  document.getElementById('editCourseForm').action = '/admin/courses/' + c.id;
  document.getElementById('editCourseTitle').value = c.title || '';
  document.getElementById('editCourseCategory').value = c.category || '';
  document.getElementById('editCourseDuration').value = c.duration || '';
  document.getElementById('editCourseFee').value = c.fee || '';
  document.getElementById('editCourseSeats').value = c.seats || '';
  document.getElementById('editCourseStatus').value = c.status || 'Active';
  document.getElementById('editCourseDesc').value = c.description || '';
}

function openEditCategoryModal(cat) {
  document.getElementById('editCategoryModal').style.display = 'flex';
  document.getElementById('editCategoryForm').action = '/admin/course-categories/' + cat.id;
  document.getElementById('editCatName').value = cat.name || '';
  document.getElementById('editCatStatus').value = cat.status || 'Active';
  document.getElementById('editCatDescription').value = cat.description || '';
}

function openQuickAddCat() {
  document.getElementById('quickAddCatModal').style.display = 'flex';
}
</script>
@endsection
