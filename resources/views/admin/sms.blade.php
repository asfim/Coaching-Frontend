@extends('layouts.admin')

@section('title', 'SMS & Notifications - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>SMS / Notify</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>SMS Notification &amp; Broadcasting</h2>
    <p>Send bulk SMS alerts to students, parents, and batches for notices, fees, and exams</p>
  </div>
</div>

@if(($siteSettings['sms_notification_status'] ?? 'off') === 'off')
  <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:var(--radius);padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:12px;color:#92400e;">
    <i class="bi bi-bell-slash-fill" style="font-size:1.4rem;color:#d97706;"></i>
    <div>
      <strong style="display:block;font-size:0.9rem;">SMS and Notification Service is currently OFF (সার্ভিস বন্ধ রয়েছে)</strong>
      <span style="font-size:0.8rem;color:#b45309;">SMS and Notification broadcasting is turned off. You can re-enable it anytime from <a href="{{ route('admin.settings') }}" style="color:var(--primary);font-weight:700;text-decoration:underline;">System Settings</a>.</span>
    </div>
  </div>
@endif

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;margin-bottom:28px;">
  <!-- Send SMS Box -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);">
    <h5 style="margin-bottom:18px;font-weight:700;"><i class="bi bi-chat-dots-fill" style="color:var(--primary);"></i> Send SMS Alert</h5>
    <form action="{{ route('admin.sms.send') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Recipients *</label>
        <select name="recipient_type" class="form-select" required>
          <option value="all">Broadcast to All Students</option>
          <optgroup label="Send to Specific Batch">
            @foreach($batches as $b)
              <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->batch_code }})</option>
            @endforeach
          </optgroup>
          <option value="custom">Custom Single Number</option>
        </select>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Message Text *</label>
        <textarea name="message" id="smsText" class="form-control" rows="4" placeholder="Dear student, your monthly test starts on..." required oninput="document.getElementById('charCount').innerText = this.value.length"></textarea>
        <div style="display:flex;justify-content:space-between;margin-top:4px;font-size:0.75rem;color:var(--gray-500);">
          <span>Characters: <strong id="charCount">0</strong> / 480</span>
          <span>160 chars = 1 SMS</span>
        </div>
      </div>

      @if(($siteSettings['sms_notification_status'] ?? 'off') === 'off')
        <button type="button" class="btn btn-secondary" style="width:100%;justify-content:center;cursor:not-allowed;background:#94a3b8;border-color:#94a3b8;" disabled>
          <i class="bi bi-bell-slash"></i> SMS Service is Currently OFF (সার্ভিস বন্ধ রয়েছে)
        </button>
      @else
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
          <i class="bi bi-send-fill"></i> Send Broadcast
        </button>
      @endif
    </form>
  </div>

  <!-- SMS Sent Logs -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
      <h5 style="margin:0;font-weight:700;">Sent SMS Broadcast History</h5>
      <span class="badge badge-primary">Total Sent: {{ $totalSent }}</span>
    </div>
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>Time</th>
            <th>Recipient / Target</th>
            <th>Phone</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
          <tr>
            <td>
              <small>{{ \Carbon\Carbon::parse($log->sent_at)->diffForHumans() }}</small>
            </td>
            <td>
              <strong>{{ $log->recipient ?: $log->recipient_type }}</strong>
              <div style="font-size:0.75rem;color:var(--gray-500);">{{ $log->recipient_type }}</div>
            </td>
            <td><code>{{ $log->phone }}</code></td>
            <td style="font-size:0.82rem;">{{ Str::limit($log->message, 45) }}</td>
            <td>
              <span class="badge badge-success"><i class="bi bi-check-circle"></i> {{ $log->status }}</span>
            </td>
            <td>
              <form action="{{ route('admin.sms.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Delete log?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);padding:2px 6px;border-color:transparent;"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:30px;color:var(--gray-500);">No SMS logs found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($logs->hasPages())
      <div style="padding:12px 20px;border-top:1px solid var(--border);">
        {{ $logs->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
