@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>sms</span>
@endsection

@section('content')
<div class="page-header"><div><h2>SMS &amp; Notifications</h2><p>Send messages to students, parents and teachers</p></div></div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;">
  <div class="stat-card green"><div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div><div class="stat-content"><h3>12,450</h3><p>Sent</p></div></div>
  <div class="stat-card yellow"><div class="stat-icon yellow"><i class="bi bi-hourglass-fill"></i></div><div class="stat-content"><h3>24</h3><p>Pending</p></div></div>
  <div class="stat-card red"><div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div><div class="stat-content"><h3>12</h3><p>Failed</p></div></div>
  <div class="stat-card blue"><div class="stat-icon blue"><i class="bi bi-chat-dots-fill"></i></div><div class="stat-content"><h3>৳2,450</h3><p>Balance</p></div></div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <!-- Send SMS Form -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;">
    <h5 style="font-weight:700;margin-bottom:20px;"><i class="bi bi-chat-dots-fill" style="color:var(--primary);"></i> Send New Message</h5>
    <div class="form-group"><label class="form-label">Send To *</label>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
        <label style="display:flex;align-items:center;gap:6px;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;font-size:.875rem;font-weight:500;"><input type="checkbox" style="margin:0;"> All Students</label>
        <label style="display:flex;align-items:center;gap:6px;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;font-size:.875rem;font-weight:500;"><input type="checkbox" style="margin:0;"> All Parents</label>
        <label style="display:flex;align-items:center;gap:6px;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;font-size:.875rem;font-weight:500;"><input type="checkbox" style="margin:0;"> All Teachers</label>
        <label style="display:flex;align-items:center;gap:6px;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;font-size:.875rem;font-weight:500;"><input type="checkbox" style="margin:0;"> By Class/Batch</label>
      </div>
    </div>
    <div class="form-group"><label class="form-label">Class / Batch (Optional)</label><select class="form-select"><option>Select Batch</option><option>SSC Batch A</option><option>HSC Batch A</option></select></div>
    <div class="form-group"><label class="form-label">Message Type</label>
      <div style="display:flex;gap:10px;">
        <label style="display:flex;align-items:center;gap:6px;font-size:.875rem;"><input type="radio" name="msgtype" checked> SMS</label>
        <label style="display:flex;align-items:center;gap:6px;font-size:.875rem;"><input type="radio" name="msgtype"> Push Notification</label>
        <label style="display:flex;align-items:center;gap:6px;font-size:.875rem;"><input type="radio" name="msgtype"> Both</label>
      </div>
    </div>
    <div class="form-group"><label class="form-label">Message Template</label><select class="form-select"><option>Custom Message</option><option>Fee Reminder</option><option>Exam Notice</option><option>Holiday Notice</option><option>Result Published</option></select></div>
    <div class="form-group"><label class="form-label">Message *</label><textarea class="form-control" rows="5" placeholder="Type your message here... (Max 160 characters for SMS)">Dear Student, Your SSC Model Test is scheduled on October 5, 2026 at 9:00 AM in Exam Hall A. Please arrive 30 minutes early. EduManage Pro.</textarea></div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;font-size:.82rem;color:var(--gray-500);">
      <span>Recipients: <strong style="color:var(--gray-900);">85 students</strong></span>
      <span>Estimated cost: <strong style="color:var(--primary);">৳85</strong></span>
    </div>
    <div style="display:flex;gap:10px;"><button class="btn btn-outline" style="flex:1;"><i class="bi bi-clock"></i> Schedule</button><button class="btn btn-primary" style="flex:2;"><i class="bi bi-send-fill"></i> Send Now</button></div>
  </div>

  <!-- Message History -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;">
    <h5 style="font-weight:700;margin-bottom:20px;"><i class="bi bi-clock-history" style="color:var(--secondary);"></i> Message History</h5>
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="padding:12px;background:var(--gray-50);border-radius:var(--radius-lg);border-left:3px solid var(--success);">
        <p style="font-size:.84rem;color:var(--gray-700);margin:0 0 8px;">SSC Model Test registration reminder sent to all Class X students.</p>
        <div style="display:flex;justify-content:space-between;font-size:.75rem;color:var(--gray-500);">
          <span><i class="bi bi-clock"></i> 27 Sep, 9:00 AM &nbsp; <i class="bi bi-people"></i> 85</span>
          <span class="badge badge-success">Sent</span>
        </div>
      </div><div style="padding:12px;background:var(--gray-50);border-radius:var(--radius-lg);border-left:3px solid var(--success);">
        <p style="font-size:.84rem;color:var(--gray-700);margin:0 0 8px;">Fee payment reminder for October 2026. Please pay before the deadline.</p>
        <div style="display:flex;justify-content:space-between;font-size:.75rem;color:var(--gray-500);">
          <span><i class="bi bi-clock"></i> 25 Sep, 10:00 AM &nbsp; <i class="bi bi-people"></i> 128</span>
          <span class="badge badge-success">Sent</span>
        </div>
      </div><div style="padding:12px;background:var(--gray-50);border-radius:var(--radius-lg);border-left:3px solid var(--success);">
        <p style="font-size:.84rem;color:var(--gray-700);margin:0 0 8px;">Holiday notice: Center closed on Eid holidays Oct 18-22.</p>
        <div style="display:flex;justify-content:space-between;font-size:.75rem;color:var(--gray-500);">
          <span><i class="bi bi-clock"></i> 22 Sep, 11:00 AM &nbsp; <i class="bi bi-people"></i> 5243</span>
          <span class="badge badge-success">Sent</span>
        </div>
      </div><div style="padding:12px;background:var(--gray-50);border-radius:var(--radius-lg);border-left:3px solid var(--success);">
        <p style="font-size:.84rem;color:var(--gray-700);margin:0 0 8px;">New HSC batch orientation on October 15 at 10:00 AM.</p>
        <div style="display:flex;justify-content:space-between;font-size:.75rem;color:var(--gray-500);">
          <span><i class="bi bi-clock"></i> 20 Sep, 2:00 PM &nbsp; <i class="bi bi-people"></i> 250</span>
          <span class="badge badge-success">Sent</span>
        </div>
      </div><div style="padding:12px;background:var(--gray-50);border-radius:var(--radius-lg);border-left:3px solid var(--danger);">
        <p style="font-size:.84rem;color:var(--gray-700);margin:0 0 8px;">Result published: SSC Trial Exam results now available.</p>
        <div style="display:flex;justify-content:space-between;font-size:.75rem;color:var(--gray-500);">
          <span><i class="bi bi-clock"></i> 15 Sep, 3:00 PM &nbsp; <i class="bi bi-people"></i> 80</span>
          <span class="badge badge-danger">Failed</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

