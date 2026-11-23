@extends('layouts.teacher')

@section('title', 'Teacher Dashboard - EduManage Pro')

@section('content')
<!-- Welcome Banner -->
<div style="background:linear-gradient(135deg,#065f46,#059669);border-radius:var(--radius-xl);padding:28px;color:#fff;margin-bottom:24px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
  <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
    <img src="{{ !empty($teacher?->image) ? asset($teacher->image) : asset('images/teacher_male_1_1790497096029.jpg') }}" style="width:84px;height:84px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);">
    <div style="flex:1;">
      <p style="color:rgba(255,255,255,.8);font-size:.85rem;margin-bottom:4px;">Welcome back, Instructor!</p>
      <h2 style="color:#fff;margin-bottom:6px;">{{ $teacher?->name ?? auth()->user()?->name ?? 'Faculty Member' }}</h2>
      <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:.85rem;color:rgba(255,255,255,.8);">
        <span><i class="bi bi-journal-check"></i> Subject: <strong>{{ $teacher?->subject ?? 'Physics & Math' }}</strong></span>
        <span><i class="bi bi-award"></i> Qualification: <strong>{{ $teacher?->qualification ?? 'M.Sc. BUET' }}</strong></span>
        <span><i class="bi bi-calendar-check"></i> Designation: <strong>{{ $teacher?->designation ?? 'Senior Lecturer' }}</strong></span>
      </div>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="{{ route('teacher.attendance') }}" class="btn btn-white"><i class="bi bi-check2-circle"></i> Take Attendance</a>
      <a href="{{ route('teacher.routine') }}" class="btn btn-outline-white"><i class="bi bi-calendar-week"></i> My Schedule</a>
    </div>
  </div>
</div>

<!-- Stats Bar -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-calendar-week-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $routines->count() }}</h3>
      <p>Weekly Classes</p>
    </div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-collection-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $batches->count() }}</h3>
      <p>Assigned Batches</p>
    </div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-people-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $totalStudents }}</h3>
      <p>Total Enrolled</p>
    </div>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon orange"><i class="bi bi-file-earmark-text-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $upcomingExams->count() }}</h3>
      <p>Upcoming Exams</p>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px;">
  <!-- Routine Overview -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <h5 style="font-weight:700;margin:0;"><i class="bi bi-clock" style="color:var(--success);"></i> Class Schedule</h5>
      <a href="{{ route('teacher.routine') }}" style="font-size:.85rem;color:var(--success);font-weight:600;">View All</a>
    </div>
    <div style="display:flex;flex-direction:column;gap:12px;">
      @forelse($routines as $r)
      <div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--gray-50);border-radius:var(--radius);border-left:4px solid var(--success);">
        <div style="min-width:110px;">
          <strong style="display:block;font-size:.85rem;color:var(--gray-900);">{{ $r->day }}</strong>
          <span style="font-size:.78rem;color:var(--gray-500);">{{ $r->start_time }} - {{ $r->end_time }}</span>
        </div>
        <div style="flex:1;">
          <h6 style="margin:0 0 4px;font-weight:700;">{{ $r->subject }}</h6>
          <span style="font-size:.8rem;color:var(--gray-600);">Batch: <strong>{{ $r->batch?->name ?? 'Batch' }}</strong> &nbsp;|&nbsp; Room: {{ $r->room ?? 'Hall 1' }}</span>
        </div>
        <a href="{{ route('teacher.attendance', ['batch_id' => $r->batch_id]) }}" class="btn btn-outline btn-sm" style="font-size:.75rem;">
          <i class="bi bi-check2-circle"></i> Attendance
        </a>
      </div>
      @empty
      <div style="text-align:center;padding:24px;color:var(--gray-500);">No classes assigned in the routine yet.</div>
      @endforelse
    </div>
  </div>

  <!-- Batches & Exams Sidebar -->
  <div style="display:flex;flex-direction:column;gap:20px;">
    <!-- Assigned Batches -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <h5 style="font-weight:700;margin-bottom:14px;"><i class="bi bi-collection" style="color:var(--primary);"></i> Active Batches</h5>
      <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach($batches as $b)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;background:var(--gray-50);border-radius:var(--radius);">
          <div>
            <strong style="display:block;font-size:.85rem;">{{ $b->name }}</strong>
            <span style="font-size:.75rem;color:var(--gray-500);">{{ $b->days }} &nbsp;|&nbsp; {{ $b->time_slot }}</span>
          </div>
          <span class="badge badge-primary">{{ $b->students_count }} Students</span>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Upcoming Exams -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <h5 style="font-weight:700;margin-bottom:14px;"><i class="bi bi-file-earmark-check" style="color:var(--warning);"></i> Upcoming Exams</h5>
      <div style="display:flex;flex-direction:column;gap:10px;">
        @forelse($upcomingExams as $ex)
        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--gray-100);">
          <div style="width:36px;height:36px;background:var(--warning-light);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--warning);font-weight:700;font-size:.8rem;">
            {{ \Carbon\Carbon::parse($ex->date)->format('d') }}
          </div>
          <div style="flex:1;">
            <strong style="display:block;font-size:.85rem;">{{ $ex->name }}</strong>
            <span style="font-size:.75rem;color:var(--gray-500);">{{ $ex->subject }} &nbsp;|&nbsp; {{ $ex->total_marks }} Marks</span>
          </div>
        </div>
        @empty
        <div style="text-align:center;color:var(--gray-500);font-size:.82rem;">No upcoming exams.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
