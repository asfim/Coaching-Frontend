@extends('layouts.admin')

@section('title', 'Admin Dashboard - EduManage Pro')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Overview</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Admin Dashboard</h2>
    <p>Welcome back! Real-time statistics, attendance, fee collections, and active batches.</p>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="{{ route('admin.students') }}" class="btn btn-outline btn-sm"><i class="bi bi-person-plus"></i> New Student</a>
    <a href="{{ route('admin.accounts.index') }}" class="btn btn-primary btn-sm"><i class="bi bi-wallet2"></i> Accounts</a>
  </div>
</div>

<!-- Stat Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $totalStudents }}</h3>
      <p>Total Students</p>
    </div>
    <span class="stat-badge up">Enrolled</span>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-person-badge-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $totalTeachers }}</h3>
      <p>Faculty Members</p>
    </div>
    <span class="stat-badge up">Active</span>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-journal-bookmark-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $totalCourses }}</h3>
      <p>Active Courses</p>
    </div>
    <span class="stat-badge up">Offered</span>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon orange"><i class="bi bi-collection-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $totalBatches }}</h3>
      <p>Running Batches</p>
    </div>
    <span class="stat-badge up">Scheduled</span>
  </div>
  <div class="stat-card cyan">
    <div class="stat-icon cyan"><i class="bi bi-calendar2-check-fill"></i></div>
    <div class="stat-content">
      <h3>{{ $attendanceRate }}%</h3>
      <p>Today's Attendance</p>
    </div>
    <span class="stat-badge up">Live</span>
  </div>
  <div class="stat-card yellow">
    <div class="stat-icon yellow"><i class="bi bi-cash-stack"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($monthlyCollection, 0) }}</h3>
      <p>Monthly Fees</p>
    </div>
    <span class="stat-badge up">{{ now()->format('M Y') }}</span>
  </div>
</div>

<!-- Charts Row -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;">
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <div>
        <h5 style="font-weight:700;margin-bottom:2px;">Monthly Fee &amp; Income Collection</h5>
        <p style="font-size:.8rem;margin:0;color:var(--gray-500);">Financial performance overview</p>
      </div>
      <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline btn-sm">View Accounts</a>
    </div>
    <canvas id="feesChart" height="100"></canvas>
  </div>
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
    <div style="margin-bottom:16px;">
      <h5 style="font-weight:700;margin-bottom:2px;">Attendance Overview</h5>
      <p style="font-size:.8rem;margin:0;color:var(--gray-500);">Today's Summary</p>
    </div>
    <canvas id="attendanceChart" height="160"></canvas>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px;">
      <div style="background:var(--success-light);border-radius:var(--radius);padding:10px;text-align:center;">
        <div style="font-size:1.2rem;font-weight:800;color:var(--success);">{{ $todayPresent }}</div>
        <div style="font-size:.72rem;color:var(--success);">Present</div>
      </div>
      <div style="background:var(--danger-light);border-radius:var(--radius);padding:10px;text-align:center;">
        <div style="font-size:1.2rem;font-weight:800;color:var(--danger);">{{ $todayAbsent }}</div>
        <div style="font-size:.72rem;color:var(--danger);">Absent</div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Admissions & Activities -->
<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;margin-bottom:20px;">
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
      <h5 style="margin:0;font-weight:700;">Recent Admissions</h5>
      <a href="{{ route('admin.students') }}" style="font-size:0.8rem;color:var(--primary);font-weight:600;">View All Students &rarr;</a>
    </div>
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>Student</th>
            <th>Class</th>
            <th>Batch</th>
            <th>Phone</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentStudents as $rs)
          <tr>
            <td>
              <div class="avatar-cell">
                <img src="{{ asset($rs->avatar ?: 'images/student_female_profile_1790497748277.jpg') }}" alt="">
                <div>
                  <strong>{{ $rs->name }}</strong>
                  <div style="font-size:0.75rem;color:var(--gray-500);">#{{ $rs->student_id }}</div>
                </div>
              </div>
            </td>
            <td>{{ $rs->class_name }}</td>
            <td><span class="badge badge-primary">{{ $rs->batch?->batch_code ?: '-' }}</span></td>
            <td>{{ $rs->phone }}</td>
            <td><span class="badge badge-success">{{ $rs->status }}</span></td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;padding:20px;color:var(--gray-500);">No students enrolled yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Activities / Quick Actions -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow-sm);">
    <h5 style="font-weight:700;font-size:1rem;margin:0 0 16px;">Quick Management Actions</h5>
    <div style="display:flex;flex-direction:column;gap:10px;">
      <a href="{{ route('admin.students') }}" class="btn btn-outline" style="justify-content:flex-start;gap:10px;padding:12px;">
        <i class="bi bi-person-plus-fill" style="color:var(--primary);font-size:1.1rem;"></i>
        <div style="text-align:left;">
          <strong>Student Admissions</strong>
          <small style="display:block;color:var(--gray-500);">Register new students &amp; view profiles</small>
        </div>
      </a>
      <a href="{{ route('admin.attendance') }}" class="btn btn-outline" style="justify-content:flex-start;gap:10px;padding:12px;">
        <i class="bi bi-calendar2-check-fill" style="color:var(--success);font-size:1.1rem;"></i>
        <div style="text-align:left;">
          <strong>Take Daily Attendance</strong>
          <small style="display:block;color:var(--gray-500);">Mark today's attendance sheet</small>
        </div>
      </a>
      <a href="{{ route('admin.fees') }}" class="btn btn-outline" style="justify-content:flex-start;gap:10px;padding:12px;">
        <i class="bi bi-cash-coin" style="color:var(--warning);font-size:1.1rem;"></i>
        <div style="text-align:left;">
          <strong>Fees &amp; Billing</strong>
          <small style="display:block;color:var(--gray-500);">Collect monthly tuition &amp; generate receipts</small>
        </div>
      </a>
      <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline" style="justify-content:flex-start;gap:10px;padding:12px;">
        <i class="bi bi-wallet2" style="color:var(--primary);font-size:1.1rem;"></i>
        <div style="text-align:left;">
          <strong>Accounts &amp; Cash Flow</strong>
          <small style="display:block;color:var(--gray-500);">Record institute income and expenditure</small>
        </div>
      </a>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('feesChart'),{
  type:'bar',
  data:{
    labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct'],
    datasets:[{
      label:'Fee Collection (৳)',
      data:[320000,380000,350000,420000,400000,450000,380000,420000,480000, {{ $monthlyCollection ?: 25000 }}],
      backgroundColor:'rgba(37,99,235,.7)',
      borderRadius:6
    }]
  },
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>'৳'+v/1000+'K'}}}}
});

new Chart(document.getElementById('attendanceChart'),{
  type:'doughnut',
  data:{
    labels:['Present','Absent','Late','Leave'],
    datasets:[{
      data:[{{ $todayPresent ?: 25 }}, {{ $todayAbsent ?: 2 }}, {{ $todayLate ?: 1 }}, {{ $todayLeave ?: 1 }}],
      backgroundColor:['#059669','#dc2626','#d97706','#0891b2'],
      borderWidth:0
    }]
  },
  options:{responsive:true,cutout:'72%',plugins:{legend:{position:'bottom'}}}
});
</script>
@endsection
