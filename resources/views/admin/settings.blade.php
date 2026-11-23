@extends('layouts.admin')

@section('title', 'System Settings - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Settings</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>System &amp; Institute Settings</h2>
    <p>Configure coaching center branding, contact info, currencies, and system preferences</p>
  </div>
</div>

<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow-sm);max-width:850px;">
  <form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <h5 style="margin-bottom:16px;font-weight:700;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:8px;">
      <i class="bi bi-building"></i> Institute Information
    </h5>
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:16px;">
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Institute Name *</label>
        <input type="text" name="coaching_name" class="form-control" value="{{ $settings['coaching_name'] ?? 'EduManage Pro Coaching Center' }}" required>
      </div>
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Tagline</label>
        <input type="text" name="institute_tagline" class="form-control" value="{{ $settings['institute_tagline'] ?? 'Smart Coaching Management for Modern Education' }}">
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:16px;">
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Official Email *</label>
        <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? 'info@edumanagepro.com.bd' }}" required>
      </div>
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Official Phone *</label>
        <input type="text" name="phone" class="form-control" value="{{ $settings['phone'] ?? '+880 1700-123456' }}" required>
      </div>
    </div>

    <div style="margin-bottom:24px;">
      <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Address / Location *</label>
      <input type="text" name="address" class="form-control" value="{{ $settings['address'] ?? 'House 12, Road 5, Dhanmondi, Dhaka-1205' }}" required>
    </div>

    <h5 style="margin-bottom:16px;font-weight:700;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:8px;">
      <i class="bi bi-gear-wide-connected"></i> Currency &amp; Admissions
    </h5>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;margin-bottom:24px;">
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Currency Symbol</label>
        <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? '৳' }}">
      </div>
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Currency Code</label>
        <input type="text" name="currency_code" class="form-control" value="{{ $settings['currency_code'] ?? 'BDT' }}">
      </div>
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Admission Status</label>
        <select name="admission_status" class="form-select">
          <option value="Open" {{ ($settings['admission_status'] ?? '') == 'Open' ? 'selected' : '' }}>Open</option>
          <option value="Closed" {{ ($settings['admission_status'] ?? '') == 'Closed' ? 'selected' : '' }}>Closed</option>
        </select>
      </div>
    </div>

    <h5 style="margin-bottom:16px;font-weight:700;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:8px;">
      <i class="bi bi-share"></i> Social Links
    </h5>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:24px;">
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Facebook Page URL</label>
        <input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? 'https://facebook.com' }}">
      </div>
      <div>
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">YouTube Channel URL</label>
        <input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? 'https://youtube.com' }}">
      </div>
    </div>

    <div style="display:flex;justify-content:flex-end;">
      <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Save All Settings</button>
    </div>
  </form>
</div>
@endsection
