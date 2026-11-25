@extends('layouts.teacher')

@section('title', 'Teacher Dashboard - EduManage Pro')

@section('content')

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

<!-- Welcome Banner -->
<div style="background:linear-gradient(135deg,#065f46,#059669);border-radius:var(--radius-xl);padding:28px;color:#fff;margin-bottom:24px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
  <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
    <img src="{{ !empty($teacher?->image) ? asset($teacher->image) : asset('images/teacher_male_1_1790497096029.jpg') }}" style="width:84px;height:84px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);">
    <div style="flex:1;">
      <p style="color:rgba(255,255,255,.8);font-size:.85rem;margin-bottom:4px;">Welcome back, Instructor!</p>
      <h2 style="color:#fff;margin-bottom:6px;">{{ $teacher?->name ?? auth()->user()?->name ?? 'Faculty Member' }}</h2>
      <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:.85rem;color:rgba(255,255,255,.8);">
        <span><i class="bi bi-journal-check"></i> Subject: <strong>{{ $teacher?->subject ?? 'Faculty' }}</strong></span>
        <span><i class="bi bi-award"></i> Qualification: <strong>{{ $teacher?->qualification ?? '-' }}</strong></span>
        <span><i class="bi bi-person-badge"></i> Designation: <strong>{{ $teacher?->designation ?? 'Lecturer' }}</strong></span>
      </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      @if(auth()->user()?->hasPermission('attendance'))
        <a href="{{ route('teacher.attendance') }}" class="btn btn-white"><i class="bi bi-check2-circle"></i> Take Attendance</a>
      @endif
      @if(auth()->user()?->hasPermission('routine'))
        <a href="{{ route('teacher.routine') }}" class="btn btn-outline-white"><i class="bi bi-calendar-week"></i> My Schedule</a>
      @endif
    </div>
  </div>
</div>

@php
  $userPerms = auth()->user()?->permissions ?? ($teacher?->permissions ?? []);
  if (is_string($userPerms)) $userPerms = json_decode($userPerms, true) ?: [];
  $hasElevatedPerms = is_array($userPerms) && count($userPerms) > 0;
@endphp

@if(!$hasElevatedPerms && !auth()->user()?->isAdmin())
  <!-- Alert when teacher has no elevated administrative permissions -->
  <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:var(--radius-lg);padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
    <div style="width:40px;height:40px;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#d97706;font-size:1.3rem;flex-shrink:0;">
      <i class="bi bi-info-circle-fill"></i>
    </div>
    <div>
      <h5 style="margin:0 0 3px 0;font-size:0.95rem;font-weight:700;color:#92400e;">Teacher Profile View (প্রোফাইল মোড)</h5>
      <p style="margin:0;font-size:0.83rem;color:#b45309;line-height:1.4;">
        আপনার শিক্ষক প্রোফাইল সক্রিয় রয়েছে। উপস্থিতি নেওয়া, ক্লাস রুটিন বা রেজাল্ট মডিউলে কাজ করার জন্য অ্যাডমিন থেকে রোল পারমিশন প্রয়োজন। অ্যাডমিন অনুমোদন দিলে নির্দিষ্ট মেনুগুলোতে সরাসরি কাজ করতে পারবেন।
      </p>
    </div>
  </div>
@endif

<!-- Teacher Details / Profile Card -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:24px;box-shadow:var(--shadow-sm);">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;border-bottom:1px solid var(--border);padding-bottom:12px;">
    <h5 style="margin:0;font-weight:700;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-person-lines-fill" style="color:var(--primary);"></i> Teacher Details &amp; Profile Information
    </h5>
    <span class="badge {{ ($teacher?->status ?? 'Active') === 'Active' ? 'badge-success' : 'badge-danger' }}" style="font-size:0.8rem;padding:6px 14px;">
      {{ $teacher?->status ?? 'Active' }}
    </span>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:18px;margin-bottom:20px;">
    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Full Name</span>
      <strong style="color:var(--gray-900);font-size:0.92rem;">{{ $teacher?->name ?? auth()->user()?->name }}</strong>
    </div>

    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Assigned Subject</span>
      <strong style="color:var(--primary);font-size:0.92rem;">{{ $teacher?->subject ?: 'Not Assigned' }}</strong>
    </div>

    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Designation</span>
      <strong style="color:var(--gray-800);font-size:0.92rem;">{{ $teacher?->designation ?: 'Lecturer' }}</strong>
    </div>

    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Educational Qualification</span>
      <strong style="color:var(--gray-800);font-size:0.92rem;">{{ $teacher?->qualification ?: 'B.Sc / M.Sc' }}</strong>
    </div>

    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Official Login Email</span>
      <strong style="color:var(--gray-800);font-size:0.92rem;">{{ $teacher?->email ?? auth()->user()?->email }}</strong>
    </div>

    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Contact Phone</span>
      <strong style="color:var(--gray-800);font-size:0.92rem;">{{ $teacher?->phone ?: 'N/A' }}</strong>
    </div>

    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Monthly Remuneration</span>
      <strong style="color:#047857;font-size:0.92rem;">৳{{ number_format($teacher?->salary ?: 0, 2) }}</strong>
    </div>

    <div style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius);">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;margin-bottom:2px;">Joining Date</span>
      <strong style="color:var(--gray-800);font-size:0.92rem;">{{ $teacher?->join_date ? \Carbon\Carbon::parse($teacher->join_date)->format('d M Y') : 'N/A' }}</strong>
    </div>
  </div>

  @if(!empty($teacher?->bio))
    <div style="background:#f8fafc;border-left:4px solid var(--primary);padding:12px 16px;border-radius:0 var(--radius) var(--radius) 0;margin-bottom:16px;">
      <span style="font-size:0.75rem;color:var(--gray-500);display:block;font-weight:600;margin-bottom:2px;">Faculty Bio / Experience:</span>
      <p style="margin:0;font-size:0.85rem;color:var(--gray-700);line-height:1.5;">{{ $teacher->bio }}</p>
    </div>
  @endif

  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <span style="font-size:0.82rem;font-weight:700;color:var(--gray-700);"><i class="bi bi-shield-check" style="color:var(--primary);"></i> Assigned Permissions:</span>
    @if($hasElevatedPerms)
      @foreach($userPerms as $perm)
        <span class="badge badge-success" style="font-size:0.75rem;padding:4px 10px;text-transform:capitalize;">
          <i class="bi bi-check-circle"></i> {{ $perm }}
        </span>
      @endforeach
    @else
      <span class="badge badge-secondary" style="font-size:0.75rem;padding:4px 10px;">
        Profile Details Only (No administrative permissions)
      </span>
    @endif
  </div>
</div>

@if($hasElevatedPerms || auth()->user()?->isAdmin() || $routines->count() > 0)
<!-- Stats Bar (for teachers with permissions or assigned classes) -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-calendar-week-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $routines->count() }}</h3>
      <p>Assigned Classes</p>
    </div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-collection-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $batches->count() }}</h3>
      <p>Active Batches</p>
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
      @if(auth()->user()?->hasPermission('routine'))
        <a href="{{ route('teacher.routine') }}" style="font-size:.85rem;color:var(--success);font-weight:600;">View All</a>
      @endif
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
        @if(auth()->user()?->hasPermission('attendance'))
          <a href="{{ route('teacher.attendance', ['batch_id' => $r->batch_id]) }}" class="btn btn-outline btn-sm" style="font-size:.75rem;">
            <i class="bi bi-check2-circle"></i> Attendance
          </a>
        @endif
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
            <strong style="display:block;font-size:.85rem;">{{ $ex->title }}</strong>
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
@endif

@endsection
