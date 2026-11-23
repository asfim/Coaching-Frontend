@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>attendance</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Attendance Management</h2><p>Mark and track daily student attendance</p></div>
<button class="btn btn-success btn-sm"><i class="bi bi-check-all"></i> Save Attendance</button></div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div><div class="stat-content"><h3>4,823</h3><p>Present Today</p></div></div>
  <div class="stat-card red"><div class="stat-icon red"><i class="bi bi-person-x-fill"></i></div><div class="stat-content"><h3>420</h3><p>Absent Today</p></div></div>
  <div class="stat-card yellow"><div class="stat-icon yellow"><i class="bi bi-clock-fill"></i></div><div class="stat-content"><h3>156</h3><p>Late Today</p></div></div>
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-calendar2-check"></i></div><div class="stat-content"><h3>92%</h3><p>Attendance Rate</p></div></div>
</div>

<!-- Chart -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:20px;">
  <h5 style="font-weight:700;margin-bottom:16px;">Monthly Attendance Chart</h5>
  <canvas id="monthlyAttChart" height="80"></canvas>
</div>

<!-- Filters -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px;margin-bottom:18px;display:flex;gap:14px;flex-wrap:wrap;align-items:flex-end;">
  <div><label class="form-label">Date</label><input type="date" class="form-control" value="2026-09-27" style="width:160px;"></div>
  <div><label class="form-label">Class</label><select class="form-select" style="width:140px;"><option>Class X</option><option>Class IX</option><option>Class XI</option></select></div>
  <div><label class="form-label">Batch</label><select class="form-select" style="width:140px;"><option>SSC Batch A</option><option>SSC Batch B</option><option>HSC Batch A</option></select></div>
  <button class="btn btn-primary"><i class="bi bi-search"></i> Load</button>
  <button class="btn btn-success" style="margin-left:auto;"><i class="bi bi-person-check"></i> Mark All Present</button>
</div>

<!-- Attendance List -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;">
  <div style="padding:16px 20px;background:var(--gray-50);border-bottom:1px solid var(--border);display:flex;gap:16px;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--gray-500);">
    <span style="min-width:50px;">SL</span><span style="flex:1;">Student</span><span style="min-width:80px;">ID</span><span style="min-width:280px;margin-left:auto;">Attendance</span>
  </div>
  
  <div class="attendance-row">
    <span style="min-width:50px;font-size:.85rem;color:var(--gray-500);">1</span>
    <div style="display:flex;align-items:center;gap:10px;flex:1;"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:38px;height:38px;border-radius:10px;object-fit:cover;"><div><strong style="display:block;font-size:.875rem;">Fatima Begum</strong><small style="color:var(--gray-500);">#STD-2401</small></div></div>
    <div class="attendance-toggle-group" style="margin-left:auto;">
      <button class="att-toggle present selected" onclick="setAtt(this,'present')">Present</button>
      <button class="att-toggle absent" onclick="setAtt(this,'absent')">Absent</button>
      <button class="att-toggle late" onclick="setAtt(this,'late')">Late</button>
      <button class="att-toggle leave" onclick="setAtt(this,'leave')">Leave</button>
    </div>
  </div>
  <div class="attendance-row">
    <span style="min-width:50px;font-size:.85rem;color:var(--gray-500);">2</span>
    <div style="display:flex;align-items:center;gap:10px;flex:1;"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" style="width:38px;height:38px;border-radius:10px;object-fit:cover;"><div><strong style="display:block;font-size:.875rem;">Rakibul Hasan</strong><small style="color:var(--gray-500);">#STD-2402</small></div></div>
    <div class="attendance-toggle-group" style="margin-left:auto;">
      <button class="att-toggle present selected" onclick="setAtt(this,'present')">Present</button>
      <button class="att-toggle absent" onclick="setAtt(this,'absent')">Absent</button>
      <button class="att-toggle late" onclick="setAtt(this,'late')">Late</button>
      <button class="att-toggle leave" onclick="setAtt(this,'leave')">Leave</button>
    </div>
  </div>
  <div class="attendance-row">
    <span style="min-width:50px;font-size:.85rem;color:var(--gray-500);">3</span>
    <div style="display:flex;align-items:center;gap:10px;flex:1;"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:38px;height:38px;border-radius:10px;object-fit:cover;"><div><strong style="display:block;font-size:.875rem;">Sumaiya Islam</strong><small style="color:var(--gray-500);">#STD-2403</small></div></div>
    <div class="attendance-toggle-group" style="margin-left:auto;">
      <button class="att-toggle present selected" onclick="setAtt(this,'present')">Present</button>
      <button class="att-toggle absent" onclick="setAtt(this,'absent')">Absent</button>
      <button class="att-toggle late" onclick="setAtt(this,'late')">Late</button>
      <button class="att-toggle leave" onclick="setAtt(this,'leave')">Leave</button>
    </div>
  </div>
  <div class="attendance-row">
    <span style="min-width:50px;font-size:.85rem;color:var(--gray-500);">4</span>
    <div style="display:flex;align-items:center;gap:10px;flex:1;"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" style="width:38px;height:38px;border-radius:10px;object-fit:cover;"><div><strong style="display:block;font-size:.875rem;">Mahbub Rahman</strong><small style="color:var(--gray-500);">#STD-2404</small></div></div>
    <div class="attendance-toggle-group" style="margin-left:auto;">
      <button class="att-toggle present selected" onclick="setAtt(this,'present')">Present</button>
      <button class="att-toggle absent" onclick="setAtt(this,'absent')">Absent</button>
      <button class="att-toggle late" onclick="setAtt(this,'late')">Late</button>
      <button class="att-toggle leave" onclick="setAtt(this,'leave')">Leave</button>
    </div>
  </div>
  <div class="attendance-row">
    <span style="min-width:50px;font-size:.85rem;color:var(--gray-500);">5</span>
    <div style="display:flex;align-items:center;gap:10px;flex:1;"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:38px;height:38px;border-radius:10px;object-fit:cover;"><div><strong style="display:block;font-size:.875rem;">Nadia Akter</strong><small style="color:var(--gray-500);">#STD-2405</small></div></div>
    <div class="attendance-toggle-group" style="margin-left:auto;">
      <button class="att-toggle present selected" onclick="setAtt(this,'present')">Present</button>
      <button class="att-toggle absent" onclick="setAtt(this,'absent')">Absent</button>
      <button class="att-toggle late" onclick="setAtt(this,'late')">Late</button>
      <button class="att-toggle leave" onclick="setAtt(this,'leave')">Leave</button>
    </div>
  </div>
</div>

</div>
</div>
<script>
function setAtt(btn, type) {
  const group = btn.closest('.attendance-toggle-group');
  group.querySelectorAll('.att-toggle').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
}
new Chart(document.getElementById('monthlyAttChart'),{type:'line',data:{labels:['Week 1','Week 2','Week 3','Week 4'],datasets:[{label:'Present %',data:[94,91,96,92],borderColor:'#059669',backgroundColor:'rgba(5,150,105,.1)',tension:.4,fill:true},{label:'Absent %',data:[6,9,4,8],borderColor:'#dc2626',backgroundColor:'rgba(220,38,38,.1)',tension:.4,fill:true}]},options:{responsive:true,scales:{y:{max:100,ticks:{callback:v=>v+'%'}}}}});
</script>
@endsection

@section('scripts')
<script>
function setAtt(btn, type) {
  const group = btn.closest('.attendance-toggle-group');
  group.querySelectorAll('.att-toggle').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
}
new Chart(document.getElementById('monthlyAttChart'),{type:'line',data:{labels:['Week 1','Week 2','Week 3','Week 4'],datasets:[{label:'Present %',data:[94,91,96,92],borderColor:'#059669',backgroundColor:'rgba(5,150,105,.1)',tension:.4,fill:true},{label:'Absent %',data:[6,9,4,8],borderColor:'#dc2626',backgroundColor:'rgba(220,38,38,.1)',tension:.4,fill:true}]},options:{responsive:true,scales:{y:{max:100,ticks:{callback:v=>v+'%'}}}}});
</script>
@endsection
