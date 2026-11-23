@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>reports</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Reports &amp; Analytics</h2><p>Generate comprehensive reports with filters and export options</p></div></div>

<!-- Report Types -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px;">
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--blue)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--blue-light,var(--primary-light));color:var(--blue,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-person-vcard"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Student Report</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">Individual student data</p>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--green)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--green-light,var(--primary-light));color:var(--green,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-calendar2-check"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Attendance Report</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">Daily/monthly attendance</p>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--orange)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--orange-light,var(--primary-light));color:var(--orange,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-cash-coin"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Fee Collection</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">Payment history & dues</p>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--red)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--red-light,var(--primary-light));color:var(--red,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-exclamation-circle"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Due Report</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">Outstanding payments</p>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--purple)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--purple-light,var(--primary-light));color:var(--purple,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-file-earmark-text"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Exam Report</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">Exam performance data</p>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--yellow)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--yellow-light,var(--primary-light));color:var(--yellow,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-trophy"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Result Report</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">GPA & grade analysis</p>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--cyan)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--cyan-light,var(--primary-light));color:var(--cyan,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-person-badge"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Teacher Report</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">Teacher performance</p>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;text-align:center;cursor:pointer;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';this.style.borderColor='var(--secondary)';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
    <div style="width:52px;height:52px;border-radius:14px;background:var(--secondary-light,var(--primary-light));color:var(--secondary,var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 12px;"><i class="bi bi-journal-bookmark"></i></div>
    <h5 style="font-size:.875rem;font-weight:700;margin-bottom:4px;">Course Report</h5>
    <p style="font-size:.75rem;color:var(--gray-500);margin:0;">Enrollment & progress</p>
  </div>
</div>

<!-- Filters -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:20px;">
  <h5 style="font-weight:700;margin-bottom:16px;"><i class="bi bi-funnel" style="color:var(--primary);"></i> Report Filters</h5>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;">
    <div><label class="form-label">Report Type</label><select class="form-select"><option>Fee Collection Report</option><option>Student Report</option><option>Attendance Report</option></select></div>
    <div><label class="form-label">Date From</label><input type="date" class="form-control" value="2026-09-01"></div>
    <div><label class="form-label">Date To</label><input type="date" class="form-control" value="2026-09-30"></div>
    <div><label class="form-label">Class</label><select class="form-select"><option>All Classes</option><option>Class X</option><option>Class XI</option></select></div>
    <div><label class="form-label">Batch</label><select class="form-select"><option>All Batches</option><option>SSC-A</option><option>HSC-A</option></select></div>
    <div style="display:flex;align-items:flex-end;gap:8px;">
      <button class="btn btn-primary" style="flex:1;"><i class="bi bi-search"></i> Generate</button>
    </div>
  </div>
  <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;">
    <button class="btn btn-outline btn-sm"><i class="bi bi-file-earmark-excel"></i> Export Excel</button>
    <button class="btn btn-outline btn-sm"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
    <button class="btn btn-outline btn-sm"><i class="bi bi-printer"></i> Print</button>
  </div>
</div>

<!-- Summary Chart -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:20px;display:grid;grid-template-columns:2fr 1fr;gap:20px;">
  <div><h5 style="font-weight:700;margin-bottom:16px;">Monthly Fee Collection — September 2026</h5><canvas id="reportChart" height="120"></canvas></div>
  <div>
    <h5 style="font-weight:700;margin-bottom:16px;">Summary</h5>
    <div style="display:flex;flex-direction:column;gap:10px;">
      <div style="display:flex;justify-content:space-between;padding:10px;background:var(--success-light);border-radius:var(--radius);"><span style="font-size:.85rem;color:var(--success);font-weight:600;">Total Collected</span><strong style="color:var(--success);">৳4,80,000</strong></div>
      <div style="display:flex;justify-content:space-between;padding:10px;background:var(--danger-light);border-radius:var(--radius);"><span style="font-size:.85rem;color:var(--danger);font-weight:600;">Total Due</span><strong style="color:var(--danger);">৳38,000</strong></div>
      <div style="display:flex;justify-content:space-between;padding:10px;background:var(--primary-light);border-radius:var(--radius);"><span style="font-size:.85rem;color:var(--primary);font-weight:600;">Paid Students</span><strong style="color:var(--primary);">5,115 / 5,243</strong></div>
      <div style="display:flex;justify-content:space-between;padding:10px;background:var(--warning-light);border-radius:var(--radius);"><span style="font-size:.85rem;color:var(--warning);font-weight:600;">Collection Rate</span><strong style="color:var(--warning);">97.6%</strong></div>
    </div>
  </div>
</div>

<!-- Report Table -->
<div class="table-wrapper">
  <table class="data-table">
    <thead><tr><th>Batch</th><th>Total Students</th><th>Collected</th><th>Due</th><th>Paid Count</th><th>Due Count</th><th>Collection %</th></tr></thead>
    <tbody>
      <tr><td><strong>SSC Batch A</strong></td><td>85</td><td>৳2,12,500</td><td>৳0</td><td>85</td><td>0</td><td><div class="progress" style="width:120px;"><div class="progress-bar green" style="width:100%;"></div></div> 100%</td></tr>
      <tr><td><strong>SSC Batch B</strong></td><td>72</td><td>৳1,62,000</td><td>৳10,000</td><td>67</td><td>5</td><td><div class="progress" style="width:120px;"><div class="progress-bar blue" style="width:93%;"></div></div> 93%</td></tr>
      <tr><td><strong>HSC Batch A</strong></td><td>120</td><td>৳3,78,000</td><td>৳14,000</td><td>108</td><td>12</td><td><div class="progress" style="width:120px;"><div class="progress-bar orange" style="width:90%;"></div></div> 90%</td></tr>
      <tr><td><strong>Admission Batch</strong></td><td>200</td><td>৳9,60,000</td><td>৳14,000</td><td>197</td><td>3</td><td><div class="progress" style="width:120px;"><div class="progress-bar purple" style="width:98.5%;"></div></div> 98.5%</td></tr>
    </tbody>
  </table>
</div>

</div>
</div>
<script>
new Chart(document.getElementById('reportChart'),{type:'bar',data:{labels:['SSC-A','SSC-B','HSC-A','HSC-B','Admission','IELTS'],datasets:[{label:'Collected',data:[212500,162000,378000,185000,960000,48000],backgroundColor:'rgba(5,150,105,.7)',borderRadius:6},{label:'Due',data:[0,10000,14000,8000,14000,0],backgroundColor:'rgba(220,38,38,.7)',borderRadius:6}]},options:{responsive:true,scales:{y:{ticks:{callback:v=>'৳'+v/1000+'K'}}}}});
</script>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('reportChart'),{type:'bar',data:{labels:['SSC-A','SSC-B','HSC-A','HSC-B','Admission','IELTS'],datasets:[{label:'Collected',data:[212500,162000,378000,185000,960000,48000],backgroundColor:'rgba(5,150,105,.7)',borderRadius:6},{label:'Due',data:[0,10000,14000,8000,14000,0],backgroundColor:'rgba(220,38,38,.7)',borderRadius:6}]},options:{responsive:true,scales:{y:{ticks:{callback:v=>'৳'+v/1000+'K'}}}}});
</script>
@endsection
