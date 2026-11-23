@extends('layouts.parent')

@section('title', 'Parent Dashboard - EduManage Pro')

@section('content')
<!-- Child Profile Card -->
<div style="background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:var(--radius-xl);padding:28px;color:#fff;margin-bottom:24px;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
  <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
    <img src="{{ !empty($child?->avatar) ? asset($child->avatar) : asset('images/student_female_profile_1790497748277.jpg') }}" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,.4);">
    <div style="flex:1;">
      <h3 style="color:#fff;margin-bottom:6px;">{{ $child?->name ?? 'Child Name' }}</h3>
      <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:.84rem;color:rgba(255,255,255,.8);">
        <span><i class="bi bi-card-text"></i> #{{ $child?->student_id ?? 'STD-1001' }}</span>
        <span><i class="bi bi-journal-bookmark"></i> {{ $child?->class_name ?? 'Class X' }} — {{ $child?->batch?->name ?? 'Batch' }}</span>
        <span><i class="bi bi-calendar3"></i> Admitted: {{ $child?->admission_date ? \Carbon\Carbon::parse($child->admission_date)->format('M Y') : 'Jan 2024' }}</span>
        <span><i class="bi bi-person-heart"></i> Guardian: {{ $child?->guardian_name ?? 'Guardian' }}</span>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:14px;text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:#fff;">{{ $attRate }}%</div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.8);">Attendance</div>
      </div>
      <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:14px;text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:#fff;">{{ $gpa }}</div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.8);">Current GPA</div>
      </div>
      <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:14px;text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:#fff;">৳{{ number_format($feeDue) }}</div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.8);">Fee Due</div>
      </div>
      <div style="background:rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:14px;text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:#fff;">{{ $nextExam ? \Carbon\Carbon::parse($nextExam->date)->format('M d') : 'N/A' }}</div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.8);">Next Exam</div>
      </div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;">
  <!-- Attendance Chart -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
    <h5 style="font-weight:700;margin-bottom:16px;">Attendance Breakdown</h5>
    <canvas id="parentAttChart" height="120"></canvas>
    <div style="display:flex;justify-content:space-around;margin-top:16px;font-size:.85rem;border-top:1px solid var(--gray-100);padding-top:12px;">
      <div><span style="color:var(--success);font-weight:700;">{{ $presentCount }}</span> Days Present</div>
      <div><span style="color:var(--danger);font-weight:700;">{{ $absentCount }}</span> Days Absent</div>
      <div><span style="color:var(--warning);font-weight:700;">{{ $lateCount }}</span> Days Late</div>
      <div><strong>{{ $attRate }}%</strong> Attendance Rate</div>
    </div>
  </div>
  <!-- Quick Links -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
    <h5 style="font-weight:700;margin-bottom:16px;">Quick Access</h5>
    <div style="display:flex;flex-direction:column;gap:10px;">
      <a href="{{ route('admin.students.profile') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:var(--primary-light);border-radius:var(--radius);color:var(--primary);font-weight:600;font-size:.875rem;"><i class="bi bi-person-badge"></i> View Full Profile</a>
      <a href="{{ route('admin.attendance') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:var(--success-light);border-radius:var(--radius);color:var(--success);font-weight:600;font-size:.875rem;"><i class="bi bi-calendar2-check"></i> Attendance Record</a>
      <a href="{{ route('admin.results') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:var(--orange-light);border-radius:var(--radius);color:var(--orange);font-weight:600;font-size:.875rem;"><i class="bi bi-trophy"></i> View Academic Results</a>
      <a href="{{ route('admin.fees') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:var(--secondary-light);border-radius:var(--radius);color:var(--secondary);font-weight:600;font-size:.875rem;"><i class="bi bi-cash-coin"></i> Fee Details &amp; Receipts</a>
      <a href="{{ route('admin.routine') }}" style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:var(--info-light);border-radius:var(--radius);color:var(--info);font-weight:600;font-size:.875rem;"><i class="bi bi-clock-history"></i> Class Routine</a>
    </div>
  </div>
</div>

<!-- Notices & Exams -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
    <h5 style="font-weight:700;margin-bottom:16px;">Recent Center Notices</h5>
    <div style="display:flex;flex-direction:column;gap:12px;">
      @forelse($recentNotices as $not)
      <div class="notice-card {{ strtolower($not->category) == 'exam' ? 'urgent' : (strtolower($not->category) == 'fee' ? 'exam' : 'general') }}">
        <div class="notice-title">{{ $not->title }}</div>
        <p style="font-size:.82rem;color:var(--gray-600);margin:6px 0 0;">{{ Str::limit($not->description, 80) }}</p>
        <div style="font-size:.72rem;color:var(--gray-400);margin-top:6px;">{{ \Carbon\Carbon::parse($not->published_at)->format('d M Y') }}</div>
      </div>
      @empty
      <div style="text-align:center;color:var(--gray-500);padding:14px;">No notices published yet.</div>
      @endforelse
    </div>
  </div>

  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
    <h5 style="font-weight:700;margin-bottom:16px;">Upcoming Exams</h5>
    <div style="display:flex;flex-direction:column;gap:12px;">
      @forelse($upcomingExams as $ex)
      <div style="display:flex;gap:12px;padding:14px;background:var(--primary-light);border-radius:var(--radius-lg);">
        <div style="width:48px;height:48px;background:var(--primary);border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#fff;font-size:.6rem;font-weight:700;">
          {{ strtoupper(\Carbon\Carbon::parse($ex->date)->format('M')) }}
          <div style="font-size:1rem;">{{ \Carbon\Carbon::parse($ex->date)->format('d') }}</div>
        </div>
        <div>
          <strong style="display:block;font-size:.875rem;color:var(--gray-900);">{{ $ex->name }}</strong>
          <span style="font-size:.78rem;color:var(--gray-600);">Subject: {{ $ex->subject }} &nbsp;|&nbsp; Marks: {{ $ex->total_marks }}</span>
        </div>
      </div>
      @empty
      <div style="text-align:center;color:var(--gray-500);padding:14px;">No upcoming exams scheduled.</div>
      @endforelse
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('parentAttChart'),{
  type:'bar',
  data:{
    labels:['Present','Absent','Late'],
    datasets:[{
      label:'Days Count',
      data:[{{ $presentCount ?: 24 }}, {{ $absentCount ?: 2 }}, {{ $lateCount ?: 1 }}],
      backgroundColor:['rgba(5,150,105,.8)','rgba(220,38,38,.8)','rgba(217,119,6,.8)'],
      borderRadius:6
    }]
  },
  options:{
    responsive:true,
    scales:{y:{beginAtZero:true}}
  }
});
</script>
@endsection
