@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>fees management</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Fees Management</h2><p>Track payments, dues and financial records</p></div>
<div style="display:flex;gap:10px;"><button class="btn btn-success btn-sm"><i class="bi bi-cash-coin"></i> Collect Payment</button><a href="{{ route('admin.invoice') }}" class="btn btn-outline btn-sm"><i class="bi bi-receipt"></i> View Invoices</a></div></div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-cash-stack"></i></div><div class="stat-content"><h3>৳42.6L</h3><p>Total Collected</p></div><span class="stat-badge up">This Year</span></div>
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-currency-exchange"></i></div><div class="stat-content"><h3>৳1.24L</h3><p>Today's Collection</p></div><span class="stat-badge up">+18%</span></div>
  <div class="stat-card red"><div class="stat-icon red"><i class="bi bi-exclamation-circle-fill"></i></div><div class="stat-content"><h3>৳3.8L</h3><p>Total Due</p></div><span class="stat-badge down">128 students</span></div>
  <div class="stat-card purple"><div class="stat-icon purple"><i class="bi bi-graph-up-arrow"></i></div><div class="stat-content"><h3>৳4.2L</h3><p>Monthly Revenue</p></div><span class="stat-badge up">+8% ↑</span></div>
</div>

<!-- Chart -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:20px;display:grid;grid-template-columns:2fr 1fr;gap:20px;">
  <div><h5 style="font-weight:700;margin-bottom:16px;">Monthly Fee Collection Trend</h5><canvas id="feeTrendChart" height="100"></canvas></div>
  <div><h5 style="font-weight:700;margin-bottom:16px;">Payment Methods</h5><canvas id="methodChart" height="180"></canvas></div>
</div>

<!-- Alert -->
<div class="alert alert-warning" style="margin-bottom:16px;"><i class="bi bi-exclamation-triangle-fill"></i> 128 students have overdue fees totaling ৳3.8 Lakh. <a href="#" style="color:var(--warning);font-weight:600;">Send SMS Reminder</a></div>

<!-- Search -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px;margin-bottom:16px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
  <div class="input-icon-wrap" style="flex:1;min-width:200px;"><i class="bi bi-search input-icon"></i><input type="text" class="form-control" placeholder="Search student name or ID..."></div>
  <select class="form-select" style="width:140px;"><option>All Status</option><option>Paid</option><option>Partial</option><option>Due</option></select>
  <select class="form-select" style="width:140px;"><option>All Months</option><option>October 2026</option><option>September 2026</option></select>
  <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
  <button class="btn btn-outline"><i class="bi bi-download"></i> Export</button>
</div>

<div class="table-wrapper">
  <table class="data-table">
    <thead><tr><th>Student</th><th>Batch</th><th>Total Fee</th><th>Paid</th><th>Due</th><th>Last Payment</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" alt=""><div><strong>Fatima Begum</strong><small style="color:var(--gray-500);">#STD-2401</small></div></div></td><td>SSC-A</td><td>৳2,500</td><td>৳2,500</td><td>৳0</td><td>01 Sep 2026</td><td><span class="badge badge-success">Paid</span></td><td><div class="action-btns"><a href="{{ route('admin.invoice') }}" class="btn btn-primary btn-sm">Invoice</a><button class="btn btn-outline btn-sm">History</button></div></td></tr>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" alt=""><div><strong>Rakibul Hasan</strong><small style="color:var(--gray-500);">#STD-2402</small></div></div></td><td>HSC-B</td><td>৳3,500</td><td>৳2,000</td><td>৳1,500</td><td>10 Aug 2026</td><td><span class="badge badge-warning">Partial</span></td><td><div class="action-btns"><button class="btn btn-success btn-sm">Collect</button><a href="{{ route('admin.invoice') }}" class="btn btn-outline btn-sm">Invoice</a></div></td></tr>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" alt=""><div><strong>Sumaiya Islam</strong><small style="color:var(--gray-500);">#STD-2403</small></div></div></td><td>SSC-C</td><td>৳2,000</td><td>৳0</td><td>৳2,000</td><td>—</td><td><span class="badge badge-danger">Due</span></td><td><div class="action-btns"><button class="btn btn-success btn-sm">Collect</button><button class="btn btn-outline btn-sm">SMS</button></div></td></tr>
      <tr><td><div class="avatar-cell"><img src="{{ asset('images/student_male_profile_1790497728356.jpg') }}" alt=""><div><strong>Mahbub Rahman</strong><small style="color:var(--gray-500);">#STD-2404</small></div></div></td><td>HSC-A</td><td>৳3,500</td><td>৳3,500</td><td>৳0</td><td>02 Sep 2026</td><td><span class="badge badge-success">Paid</span></td><td><div class="action-btns"><a href="{{ route('admin.invoice') }}" class="btn btn-primary btn-sm">Invoice</a><button class="btn btn-outline btn-sm">History</button></div></td></tr>
    </tbody>
  </table>
</div>

</div>
</div>
<script>
new Chart(document.getElementById('feeTrendChart'),{type:'line',data:{labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep'],datasets:[{label:'Collection (৳)',data:[320000,380000,350000,420000,400000,450000,380000,420000,480000],borderColor:'#059669',backgroundColor:'rgba(5,150,105,.1)',tension:.4,fill:true}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>'৳'+v/1000+'K'}}}}});
new Chart(document.getElementById('methodChart'),{type:'doughnut',data:{labels:['bKash','Cash','Nagad','Bank'],datasets:[{data:[45,30,15,10],backgroundColor:['#2563eb','#059669','#d97706','#7c3aed'],borderWidth:0}]},options:{cutout:'65%',plugins:{legend:{position:'bottom'}}}});
</script>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('feeTrendChart'),{type:'line',data:{labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep'],datasets:[{label:'Collection (৳)',data:[320000,380000,350000,420000,400000,450000,380000,420000,480000],borderColor:'#059669',backgroundColor:'rgba(5,150,105,.1)',tension:.4,fill:true}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>'৳'+v/1000+'K'}}}}});
new Chart(document.getElementById('methodChart'),{type:'doughnut',data:{labels:['bKash','Cash','Nagad','Bank'],datasets:[{data:[45,30,15,10],backgroundColor:['#2563eb','#059669','#d97706','#7c3aed'],borderWidth:0}]},options:{cutout:'65%',plugins:{legend:{position:'bottom'}}}});
</script>
@endsection
