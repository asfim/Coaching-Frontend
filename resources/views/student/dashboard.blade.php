@extends('layouts.student')

@section('title', 'Student Dashboard - EduManage Pro')

@section('content')
<!-- Welcome Banner -->
<div style="background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:var(--radius-xl);padding:28px;color:#fff;margin-bottom:24px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-50px;right:-50px;width:200px;height:200px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
  <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
    <img src="{{ !empty($student?->avatar) ? asset($student->avatar) : asset('images/student_female_profile_1790497748277.jpg') }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);">
    <div>
      <p style="color:rgba(255,255,255,.8);font-size:.85rem;margin-bottom:4px;">Good Day 🌟</p>
      <h2 style="color:#fff;margin-bottom:6px;">Welcome back, {{ $student?->name ?? 'Student' }}!</h2>
      <p style="color:rgba(255,255,255,.75);font-size:.9rem;margin:0;">
        {{ $student?->class_name ?? 'Class X' }} — {{ $student?->batch?->name ?? 'Batch A' }} &nbsp;|&nbsp; ID: #{{ $student?->student_id ?? 'STD-1001' }}
      </p>
    </div>
    <div style="margin-left:auto;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:14px;text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:#fff;">{{ $attRate }}%</div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.8);">Attendance</div>
      </div>
      <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:14px;text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:#fff;">{{ $gpa }}</div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.8);">Average GPA</div>
      </div>
    </div>
  </div>
</div>

<!-- Stats Overview -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:20px;">
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-journal-bookmark-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $student?->course?->title ? '1' : '3' }}</h3>
      <p>Enrolled Course</p>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-calendar2-check-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $attRate }}%</h3>
      <p>Attendance Rate</p>
    </div>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon orange"><i class="bi bi-trophy-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $gpa }}</h3>
      <p>Current GPA</p>
    </div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-file-earmark-text-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $nextExam ? '1' : '0' }}</h3>
      <p>Upcoming Exams</p>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;">
  <div style="display:flex;flex-direction:column;gap:20px;">
    <!-- Class Routine -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <h5 style="font-weight:700;margin-bottom:16px;"><i class="bi bi-clock" style="color:var(--primary);"></i> Class Schedule ({{ $student?->batch?->name ?? 'Weekly Timetable' }})</h5>
      <div style="display:flex;flex-direction:column;gap:10px;">
        @forelse($routines as $r)
        <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--gray-50);border-radius:var(--radius-lg);">
          <div style="font-size:.78rem;font-weight:600;color:var(--gray-500);min-width:110px;">
            <i class="bi bi-calendar-event"></i> {{ $r->day }}<br>
            <span style="color:var(--primary);">{{ $r->start_time }} - {{ $r->end_time }}</span>
          </div>
          <div style="flex:1;">
            <strong style="font-size:.875rem;">{{ $r->subject }}</strong>
            <p style="font-size:.78rem;color:var(--gray-500);margin:0;">
              Teacher: {{ $r->teacher?->name ?? 'Faculty' }} &nbsp;|&nbsp; Room: {{ $r->room ?? 'Main Hall' }}
            </p>
          </div>
          <span class="badge badge-primary">{{ $student?->batch?->name ?? 'Active' }}</span>
        </div>
        @empty
        <div style="text-align:center;padding:18px;color:var(--gray-500);font-size:.85rem;">No class routine scheduled yet.</div>
        @endforelse
      </div>
    </div>

    <!-- Academic Results -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h5 style="font-weight:700;margin:0;"><i class="bi bi-award" style="color:var(--success);"></i> Academic Results &amp; Marks</h5>
        <span class="badge badge-primary">{{ count($student?->results ?? []) }} Exams</span>
      </div>
      <div class="table-responsive">
        <table class="table" style="font-size:.85rem;">
          <thead>
            <tr>
              <th>Exam Title</th>
              <th>Subject</th>
              <th>Marks</th>
              <th>GPA</th>
              <th>Grade</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($student?->results ?? [] as $res)
            <tr>
              <td><strong>{{ $res->exam?->title ?? 'Exam' }}</strong></td>
              <td>{{ $res->exam?->subjectModel?->name ?: ($res->exam?->subject ?: '-') }}</td>
              <td><strong>{{ $res->obtained_marks }}</strong> / {{ $res->total_marks }}</td>
              <td><strong style="color:var(--primary);">{{ number_format($res->gpa, 2) }}</strong></td>
              <td><span class="badge badge-success">{{ $res->grade }}</span></td>
              <td>
                <a href="{{ route('admin.results.marksheet', $res->id) }}" class="btn btn-outline btn-sm" target="_blank" style="padding:2px 8px;font-size:0.75rem;">
                  <i class="bi bi-file-earmark-pdf"></i> Marksheet
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" style="text-align:center;color:var(--gray-500);padding:20px;">No exam results published yet.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Sidebar widgets -->
  <div style="display:flex;flex-direction:column;gap:16px;">
    <!-- Student Profile Details Card -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <h5 style="font-weight:700;margin-bottom:14px;"><i class="bi bi-person-vcard" style="color:var(--primary);"></i> Student Details</h5>
      <div style="display:flex;flex-direction:column;gap:8px;font-size:0.83rem;">
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--gray-100);">
          <span style="color:var(--gray-500);">Student ID:</span>
          <strong>{{ $student?->student_id ?? '-' }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--gray-100);">
          <span style="color:var(--gray-500);">Class:</span>
          <strong>{{ $student?->class_name ?? '-' }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--gray-100);">
          <span style="color:var(--gray-500);">Program:</span>
          <span class="badge {{ ($student?->program_type ?? 'Academic') === 'Target' ? 'badge-warning' : 'badge-primary' }}">
            {{ $student?->program_type ?? 'Academic' }}
          </span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--gray-100);">
          <span style="color:var(--gray-500);">Batch:</span>
          <strong>{{ $student?->batch?->name ?? 'Default Batch' }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--gray-100);">
          <span style="color:var(--gray-500);">Phone:</span>
          <span>{{ $student?->phone ?: '-' }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;">
          <span style="color:var(--gray-500);">Guardian Phone:</span>
          <span>{{ $student?->guardian_phone ?: '-' }}</span>
        </div>
      </div>
    </div>

    <!-- Attendance Doughnut -->
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <h5 style="font-weight:700;margin-bottom:14px;">Attendance Summary</h5>
      <canvas id="studentAttChart" height="180"></canvas>
      <div style="display:flex;justify-content:space-around;margin-top:14px;font-size:.8rem;text-align:center;">
        <div><span style="color:var(--success);font-weight:700;">{{ $presentCount }}</span> Days<br><span style="color:var(--gray-500);">Present</span></div>
        <div><span style="color:var(--danger);font-weight:700;">{{ $absentCount }}</span> Days<br><span style="color:var(--gray-500);">Absent</span></div>
        <div><span style="color:var(--warning);font-weight:700;">{{ $lateCount }}</span> Days<br><span style="color:var(--gray-500);">Late</span></div>
      </div>
    </div>

    <!-- Fee Summary -->
    @php
      $totFee = $student?->fees->sum('amount') ?: 0;
      $totPaid = $student?->fees->sum('paid_amount') ?: 0;
      $totDue = $student?->fees->sum('due_amount') ?: 0;
    @endphp
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <h5 style="font-weight:700;margin-bottom:12px;"><i class="bi bi-wallet2" style="color:#059669;"></i> Fee &amp; Due Status</h5>
      <div style="display:flex;flex-direction:column;gap:8px;font-size:0.83rem;">
        <div style="display:flex;justify-content:space-between;padding:6px 8px;background:#f8fafc;border-radius:6px;">
          <span style="color:var(--gray-600);">Total Billed:</span>
          <strong>৳{{ number_format($totFee, 2) }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:6px 8px;background:#ecfdf5;border-radius:6px;">
          <span style="color:#047857;">Total Paid:</span>
          <strong style="color:#047857;">৳{{ number_format($totPaid, 2) }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:6px 8px;background:#fef2f2;border-radius:6px;">
          <span style="color:#b91c1c;">Outstanding Due:</span>
          <strong style="color:#b91c1c;">৳{{ number_format($totDue, 2) }}</strong>
        </div>
      </div>
    </div>

    <!-- Next Exam -->
    <div style="background:linear-gradient(135deg,var(--warning),#b45309);border-radius:var(--radius-lg);padding:20px;color:#fff;">
      <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.8);margin-bottom:8px;">
        <i class="bi bi-alarm"></i> Next Exam
      </div>
      <h4 style="color:#fff;margin-bottom:4px;">{{ $nextExam?->title ?? 'Model Test' }}</h4>
      <p style="font-size:.85rem;color:rgba(255,255,255,.8);margin-bottom:16px;">
        {{ $nextExam ? \Carbon\Carbon::parse($nextExam->date)->format('F d, Y') : 'Date to be announced' }}
      </p>
      <div style="background:rgba(255,255,255,.2);border-radius:var(--radius);padding:10px;text-align:center;font-size:.82rem;color:#fff;">
        <i class="bi bi-clock"></i> <strong>Subject: {{ $nextExam?->subject ?? 'All Subjects' }}</strong>
      </div>
    </div>

    <!-- Recent Result Highlight -->
    @if($recentResult)
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
      <h5 style="font-weight:700;margin-bottom:14px;">Latest Exam Score</h5>
      <div style="text-align:center;">
        <div style="font-size:3rem;font-weight:800;color:var(--success);">{{ $recentResult->gpa }}</div>
        <div style="font-size:.85rem;color:var(--gray-600);margin-bottom:12px;">{{ $recentResult->exam?->name ?? 'Assessment' }}</div>
        <span class="badge badge-success" style="font-size:.85rem;padding:6px 16px;">Grade {{ $recentResult->grade }}</span>
      </div>
    </div>
    @endif
  </div>
</div>

<!-- Recent Notices -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
  <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
    <h5 style="font-weight:700;margin:0;">Recent Notices</h5>
    <a href="{{ route('notice') }}" style="font-size:.85rem;color:var(--primary);">View All</a>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:12px;">
    @forelse($recentNotices as $not)
    <div class="notice-card {{ strtolower($not->category) == 'exam' ? 'urgent' : (strtolower($not->category) == 'fee' ? 'exam' : 'general') }}">
      <div class="notice-title">{{ $not->title }}</div>
      <p style="font-size:.82rem;color:var(--gray-600);margin:6px 0 0;">{{ Str::limit($not->description, 80) }}</p>
      <div style="margin-top:6px;font-size:.75rem;color:var(--gray-400);">{{ \Carbon\Carbon::parse($not->published_at)->format('d M Y') }}</div>
    </div>
    @empty
    <div style="text-align:center;padding:12px;color:var(--gray-500);">No notices published yet.</div>
    @endforelse
  </div>
</div>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('studentAttChart'),{
  type:'doughnut',
  data:{
    labels:['Present','Absent','Late'],
    datasets:[{
      data:[{{ $presentCount ?: 24 }},{{ $absentCount ?: 2 }},{{ $lateCount ?: 1 }}],
      backgroundColor:['#059669','#dc2626','#d97706'],
      borderWidth:0
    }]
  },
  options:{cutout:'70%',plugins:{legend:{position:'bottom'}}}
});
</script>
@endsection
