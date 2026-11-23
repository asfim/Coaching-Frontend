@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>batch management</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Batch Management</h2><p>Manage all batches and class schedules</p></div>
<button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Batch</button></div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-collection-fill"></i></div><div class="stat-content"><h3>36</h3><p>Total Batches</p></div></div>
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div><div class="stat-content"><h3>30</h3><p>Active Batches</p></div></div>
  <div class="stat-card orange"><div class="stat-icon orange"><i class="bi bi-people-fill"></i></div><div class="stat-content"><h3>5,243</h3><p>Total Students</p></div></div>
  <div class="stat-card purple"><div class="stat-icon purple"><i class="bi bi-person-badge-fill"></i></div><div class="stat-content"><h3>48</h3><p>Teachers Assigned</p></div></div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;margin-bottom:24px;">
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
    <div style="background:linear-gradient(135deg,var(--primary),var(--primary-dark,var(--primary)));padding:16px 20px;color:#fff;position:relative;">
      <div style="position:absolute;top:12px;right:12px;"><span class="badge" style="background:rgba(255,255,255,.2);color:#fff;">Sat-Thu 8AM</span></div>
      <h5 style="color:#fff;font-size:1rem;margin-bottom:4px;">SSC Batch A</h5>
      <p style="font-size:.82rem;color:rgba(255,255,255,.8);margin:0;">Class X &nbsp;|&nbsp; SSC Science</p>
    </div>
    <div style="padding:16px 20px;">
      <div style="display:flex;gap:12px;margin-bottom:12px;font-size:.82rem;color:var(--gray-600);flex-wrap:wrap;">
        <span><i class="bi bi-person-badge" style="color:var(--primary);"></i> Md. Rafiqul</span>
        <span><i class="bi bi-geo-alt" style="color:var(--success);"></i> Room 101</span>
      </div>
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:.82rem;"><span>Students Enrolled</span><strong>85/100</strong></div>
        <div class="progress"><div class="progress-bar primary" style="width:85%;"></div></div>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.attendance') }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="bi bi-calendar2-check"></i> Attendance</a>
        <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></button>
      </div>
    </div>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
    <div style="background:linear-gradient(135deg,var(--success),var(--success-dark,var(--success)));padding:16px 20px;color:#fff;position:relative;">
      <div style="position:absolute;top:12px;right:12px;"><span class="badge" style="background:rgba(255,255,255,.2);color:#fff;">Sat-Thu 10AM</span></div>
      <h5 style="color:#fff;font-size:1rem;margin-bottom:4px;">SSC Batch B</h5>
      <p style="font-size:.82rem;color:rgba(255,255,255,.8);margin:0;">Class X &nbsp;|&nbsp; SSC Science</p>
    </div>
    <div style="padding:16px 20px;">
      <div style="display:flex;gap:12px;margin-bottom:12px;font-size:.82rem;color:var(--gray-600);flex-wrap:wrap;">
        <span><i class="bi bi-person-badge" style="color:var(--primary);"></i> Nasrin Sultana</span>
        <span><i class="bi bi-geo-alt" style="color:var(--success);"></i> Room 102</span>
      </div>
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:.82rem;"><span>Students Enrolled</span><strong>72/100</strong></div>
        <div class="progress"><div class="progress-bar success" style="width:72%;"></div></div>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.attendance') }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="bi bi-calendar2-check"></i> Attendance</a>
        <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></button>
      </div>
    </div>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
    <div style="background:linear-gradient(135deg,var(--secondary),var(--secondary-dark,var(--secondary)));padding:16px 20px;color:#fff;position:relative;">
      <div style="position:absolute;top:12px;right:12px;"><span class="badge" style="background:rgba(255,255,255,.2);color:#fff;">Sat-Thu 2PM</span></div>
      <h5 style="color:#fff;font-size:1rem;margin-bottom:4px;">HSC Batch A</h5>
      <p style="font-size:.82rem;color:rgba(255,255,255,.8);margin:0;">Class XI &nbsp;|&nbsp; HSC Science</p>
    </div>
    <div style="padding:16px 20px;">
      <div style="display:flex;gap:12px;margin-bottom:12px;font-size:.82rem;color:var(--gray-600);flex-wrap:wrap;">
        <span><i class="bi bi-person-badge" style="color:var(--primary);"></i> Prof. Karim</span>
        <span><i class="bi bi-geo-alt" style="color:var(--success);"></i> Room 201</span>
      </div>
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:.82rem;"><span>Students Enrolled</span><strong>120/130</strong></div>
        <div class="progress"><div class="progress-bar secondary" style="width:92%;"></div></div>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.attendance') }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="bi bi-calendar2-check"></i> Attendance</a>
        <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></button>
      </div>
    </div>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
    <div style="background:linear-gradient(135deg,var(--orange),var(--orange-dark,var(--orange)));padding:16px 20px;color:#fff;position:relative;">
      <div style="position:absolute;top:12px;right:12px;"><span class="badge" style="background:rgba(255,255,255,.2);color:#fff;">Sun-Thu 4PM</span></div>
      <h5 style="color:#fff;font-size:1rem;margin-bottom:4px;">HSC Batch B</h5>
      <p style="font-size:.82rem;color:rgba(255,255,255,.8);margin:0;">Class XII &nbsp;|&nbsp; HSC Science</p>
    </div>
    <div style="padding:16px 20px;">
      <div style="display:flex;gap:12px;margin-bottom:12px;font-size:.82rem;color:var(--gray-600);flex-wrap:wrap;">
        <span><i class="bi bi-person-badge" style="color:var(--primary);"></i> Farhana Akter</span>
        <span><i class="bi bi-geo-alt" style="color:var(--success);"></i> Room 202</span>
      </div>
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:.82rem;"><span>Students Enrolled</span><strong>65/80</strong></div>
        <div class="progress"><div class="progress-bar orange" style="width:81%;"></div></div>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.attendance') }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="bi bi-calendar2-check"></i> Attendance</a>
        <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></button>
      </div>
    </div>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
    <div style="background:linear-gradient(135deg,var(--info),var(--info-dark,var(--info)));padding:16px 20px;color:#fff;position:relative;">
      <div style="position:absolute;top:12px;right:12px;"><span class="badge" style="background:rgba(255,255,255,.2);color:#fff;">Fri-Sat 9AM</span></div>
      <h5 style="color:#fff;font-size:1rem;margin-bottom:4px;">Admission A</h5>
      <p style="font-size:.82rem;color:rgba(255,255,255,.8);margin:0;">HSC Pass &nbsp;|&nbsp; Uni. Admission</p>
    </div>
    <div style="padding:16px 20px;">
      <div style="display:flex;gap:12px;margin-bottom:12px;font-size:.82rem;color:var(--gray-600);flex-wrap:wrap;">
        <span><i class="bi bi-person-badge" style="color:var(--primary);"></i> Md. Rafiqul</span>
        <span><i class="bi bi-geo-alt" style="color:var(--success);"></i> Hall A</span>
      </div>
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:.82rem;"><span>Students Enrolled</span><strong>200/200</strong></div>
        <div class="progress"><div class="progress-bar info" style="width:100%;"></div></div>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.attendance') }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="bi bi-calendar2-check"></i> Attendance</a>
        <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></button>
      </div>
    </div>
  </div><div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:var(--transition);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow='';">
    <div style="background:linear-gradient(135deg,var(--yellow),var(--yellow-dark,var(--yellow)));padding:16px 20px;color:#fff;position:relative;">
      <div style="position:absolute;top:12px;right:12px;"><span class="badge" style="background:rgba(255,255,255,.2);color:#fff;">Fri 2PM</span></div>
      <h5 style="color:#fff;font-size:1rem;margin-bottom:4px;">IELTS Batch</h5>
      <p style="font-size:.82rem;color:rgba(255,255,255,.8);margin:0;">Open &nbsp;|&nbsp; IELTS Prep</p>
    </div>
    <div style="padding:16px 20px;">
      <div style="display:flex;gap:12px;margin-bottom:12px;font-size:.82rem;color:var(--gray-600);flex-wrap:wrap;">
        <span><i class="bi bi-person-badge" style="color:var(--primary);"></i> Sohel Rana</span>
        <span><i class="bi bi-geo-alt" style="color:var(--success);"></i> Room 103</span>
      </div>
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:.82rem;"><span>Students Enrolled</span><strong>45/60</strong></div>
        <div class="progress"><div class="progress-bar yellow" style="width:75%;"></div></div>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.attendance') }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;"><i class="bi bi-calendar2-check"></i> Attendance</a>
        <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></button>
      </div>
    </div>
  </div>
</div>
@endsection

