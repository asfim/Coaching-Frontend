@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>routine</span>
@endsection

@section('content')
<div class="page-header"><div><h2>Class Routine / Timetable</h2><p>Weekly schedule for all batches and subjects</p></div>
<div style="display:flex;gap:10px;">
  <select class="form-select" style="width:160px;"><option>SSC Batch A</option><option>SSC Batch B</option><option>HSC Batch A</option></select>
  <button class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Print</button>
</div></div>

<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-xl);padding:24px;box-shadow:var(--shadow-sm);">
  <h5 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;"><span style="background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;border-radius:8px;padding:4px 12px;font-size:.85rem;">SSC Batch A</span> Weekly Timetable — October 2026</h5>
  <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;"><span style="width:14px;height:14px;background:#eff6ff;border-radius:3px;border:1px solid var(--primary);display:inline-block;"></span> Physics</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;"><span style="width:14px;height:14px;background:#f0fdf4;border-radius:3px;border:1px solid var(--success);display:inline-block;"></span> Math</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;"><span style="width:14px;height:14px;background:#fdf4ff;border-radius:3px;border:1px solid var(--secondary);display:inline-block;"></span> Chemistry</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;"><span style="width:14px;height:14px;background:#fff7ed;border-radius:3px;border:1px solid var(--orange);display:inline-block;"></span> Biology</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;"><span style="width:14px;height:14px;background:#ecfeff;border-radius:3px;border:1px solid var(--info);display:inline-block;"></span> English</div>
  </div>
  <div class="timetable-wrap">
    <table class="timetable">
      <thead>
        <tr>
          <th style="width:90px;">Time</th>
          <th>Saturday</th><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th>
          <th>Friday</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="time-col">8:00–9:30 AM</td>
          <td><div class="class-cell" style="background:#eff6ff;color:#1d4ed8;"><h6>Physics</h6><p>Md. Rafiqul<br>Room 101</p></div></td>
          <td><div class="class-cell" style="background:#f0fdf4;color:#065f46;"><h6>Mathematics</h6><p>Prof. Karim<br>Room 102</p></div></td>
          <td><div class="class-cell" style="background:#fdf4ff;color:#6b21a8;"><h6>Chemistry</h6><p>Farhana Akter<br>Lab 1</p></div></td>
          <td><div class="class-cell" style="background:#eff6ff;color:#1d4ed8;"><h6>Physics</h6><p>Md. Rafiqul<br>Room 101</p></div></td>
          <td><div class="class-cell" style="background:#f0fdf4;color:#065f46;"><h6>Mathematics</h6><p>Prof. Karim<br>Room 102</p></div></td>
          <td><div class="class-cell" style="background:#ecfeff;color:#155e75;"><h6>English</h6><p>Sohel Rana<br>Room 103</p></div></td>
          <td style="background:var(--gray-50);text-align:center;color:var(--gray-400);font-size:.8rem;">Holiday</td>
        </tr>
        <tr>
          <td class="time-col">10:00–11:30 AM</td>
          <td><div class="class-cell" style="background:#f0fdf4;color:#065f46;"><h6>Mathematics</h6><p>Prof. Karim<br>Room 202</p></div></td>
          <td><div class="class-cell" style="background:#fff7ed;color:#9a3412;"><h6>Biology</h6><p>Farhana Akter<br>Lab 2</p></div></td>
          <td><div class="class-cell" style="background:#eff6ff;color:#1d4ed8;"><h6>Physics</h6><p>Md. Rafiqul<br>Room 101</p></div></td>
          <td><div class="class-cell" style="background:#f0fdf4;color:#065f46;"><h6>Mathematics</h6><p>Prof. Karim<br>Room 202</p></div></td>
          <td><div class="class-cell" style="background:#fdf4ff;color:#6b21a8;"><h6>Chemistry</h6><p>Farhana Akter<br>Lab 1</p></div></td>
          <td><div class="class-cell" style="background:#fff7ed;color:#9a3412;"><h6>Biology</h6><p>Farhana Akter<br>Lab 2</p></div></td>
          <td style="background:var(--gray-50);text-align:center;color:var(--gray-400);font-size:.8rem;">Holiday</td>
        </tr>
        <tr>
          <td class="time-col">12:00–1:00 PM</td>
          <td colspan="7" style="text-align:center;background:#fffbeb;color:#92400e;font-weight:600;font-size:.85rem;">&#127812; Lunch Break / Prayer Break</td>
        </tr>
        <tr>
          <td class="time-col">2:00–3:30 PM</td>
          <td><div class="class-cell" style="background:#ecfeff;color:#155e75;"><h6>English</h6><p>Sohel Rana<br>Room 103</p></div></td>
          <td><div class="class-cell" style="background:#eff6ff;color:#1d4ed8;"><h6>Physics</h6><p>Md. Rafiqul<br>Room 101</p></div></td>
          <td><div class="class-cell" style="background:#fff7ed;color:#9a3412;"><h6>Biology</h6><p>Farhana<br>Lab 2</p></div></td>
          <td><div class="class-cell" style="background:#ecfeff;color:#155e75;"><h6>English</h6><p>Sohel Rana<br>Room 103</p></div></td>
          <td><div class="class-cell" style="background:#eff6ff;color:#1d4ed8;"><h6>Physics</h6><p>Md. Rafiqul<br>Room 101</p></div></td>
          <td><div class="class-cell" style="background:#f0fdf4;color:#065f46;"><h6>Mathematics</h6><p>Prof. Karim<br>Room 202</p></div></td>
          <td style="background:var(--gray-50);text-align:center;color:var(--gray-400);font-size:.8rem;">Holiday</td>
        </tr>
        <tr>
          <td class="time-col">4:00–5:30 PM</td>
          <td><div class="class-cell" style="background:#fdf4ff;color:#6b21a8;"><h6>Chemistry</h6><p>Farhana<br>Lab 1</p></div></td>
          <td><div class="class-cell" style="background:#ecfeff;color:#155e75;"><h6>English</h6><p>Sohel Rana<br>Room 103</p></div></td>
          <td><div class="class-cell" style="background:#f0fdf4;color:#065f46;"><h6>Mathematics</h6><p>Prof. Karim<br>Room 202</p></div></td>
          <td><div class="class-cell" style="background:#fdf4ff;color:#6b21a8;"><h6>Chemistry</h6><p>Farhana<br>Lab 1</p></div></td>
          <td style="text-align:center;color:var(--gray-400);font-size:.8rem;">—</td>
          <td style="text-align:center;color:var(--gray-400);font-size:.8rem;">—</td>
          <td style="background:var(--gray-50);text-align:center;color:var(--gray-400);font-size:.8rem;">Holiday</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

