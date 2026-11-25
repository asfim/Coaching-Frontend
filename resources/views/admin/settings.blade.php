@extends('layouts.admin')

@section('title', 'System Settings - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Settings</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>System &amp; Institute Settings</h2>
    <p>Configure coaching center branding, logo, favicon, contact info, currencies, and system preferences</p>
  </div>
</div>

@if(session('success'))
  <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#047857;padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif

@if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;">
    <strong style="display:block;margin-bottom:4px;"><i class="bi bi-exclamation-triangle-fill"></i> Please correct the following errors:</strong>
    <ul style="margin:0;padding-left:20px;font-size:0.85rem;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow-sm);max-width:850px;">
  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- 1. Branding & Identity (Logo & Favicon) -->
    <h5 style="margin-bottom:16px;font-weight:700;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:8px;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-palette2"></i> Branding, Logo &amp; Favicon
    </h5>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;">
      <!-- Logo Upload Box -->
      <div style="background:#f8fafc;border:1.5px dashed var(--border);border-radius:var(--radius-lg);padding:18px;display:flex;flex-direction:column;justify-content:space-between;">
        <div>
          <label style="display:block;font-size:0.85rem;font-weight:700;color:var(--gray-800);margin-bottom:4px;">
            <i class="bi bi-image" style="color:var(--primary);"></i> Coaching Logo (প্রতিষ্ঠান লোগো)
          </label>
          <span style="display:block;font-size:0.75rem;color:var(--gray-500);margin-bottom:12px;line-height:1.4;">
            Recommended: Transparent PNG, SVG, WEBP or JPG (approx 250×60px, max 3MB). Displayed on sidebar, site header &amp; receipts.
          </span>

          <!-- Logo Preview Container -->
          <div id="logoPreviewWrapper" style="background:#fff;border:1px solid #e2e8f0;border-radius:var(--radius);padding:14px;min-height:85px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            @if(!empty($settings['site_logo']))
              <img id="logoPreviewImg" src="{{ asset($settings['site_logo']) }}" alt="Site Logo" style="max-height:60px;max-width:100%;object-fit:contain;">
              <div id="logoPlaceholder" style="display:none;align-items:center;gap:10px;color:var(--gray-400);">
                <div class="logo-icon" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-book-half"></i></div>
                <span style="font-size:0.8rem;color:var(--gray-500);font-weight:600;">Default System Logo</span>
              </div>
            @else
              <div id="logoPlaceholder" style="display:flex;align-items:center;gap:10px;color:var(--gray-400);">
                <div class="logo-icon" style="width:36px;height:36px;font-size:1.1rem;"><i class="bi bi-book-half"></i></div>
                <span style="font-size:0.8rem;color:var(--gray-500);font-weight:600;">Default System Logo</span>
              </div>
              <img id="logoPreviewImg" src="" alt="Preview" style="max-height:60px;max-width:100%;object-fit:contain;display:none;">
            @endif
          </div>
        </div>

        <div>
          <input type="file" name="site_logo" id="siteLogoInput" class="form-control" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp" onchange="previewFile(this, 'logoPreviewImg', 'logoPlaceholder')">
          @if(!empty($settings['site_logo']))
            <div style="margin-top:8px;display:flex;align-items:center;gap:6px;">
              <input type="checkbox" name="remove_logo" value="1" id="removeLogoCheck">
              <label for="removeLogoCheck" style="font-size:0.75rem;color:var(--danger);cursor:pointer;font-weight:600;">
                <i class="bi bi-trash"></i> Remove custom logo &amp; use default
              </label>
            </div>
          @endif
        </div>
      </div>

      <!-- Favicon Upload Box -->
      <div style="background:#f8fafc;border:1.5px dashed var(--border);border-radius:var(--radius-lg);padding:18px;display:flex;flex-direction:column;justify-content:space-between;">
        <div>
          <label style="display:block;font-size:0.85rem;font-weight:700;color:var(--gray-800);margin-bottom:4px;">
            <i class="bi bi-window" style="color:var(--primary);"></i> Favicon (ওয়েবসাইট ফেভিকন)
          </label>
          <span style="display:block;font-size:0.75rem;color:var(--gray-500);margin-bottom:12px;line-height:1.4;">
            Recommended: Square .ico, PNG or SVG (32×32px or 64×64px, max 1MB). Displayed on browser tabs &amp; shortcuts.
          </span>

          <!-- Favicon Preview Container with browser tab look -->
          <div id="faviconPreviewWrapper" style="background:#fff;border:1px solid #e2e8f0;border-radius:var(--radius);padding:14px;min-height:85px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:6px;padding:6px 14px;display:flex;align-items:center;gap:8px;box-shadow:0 1px 3px rgba(0,0,0,0.05);max-width:240px;overflow:hidden;">
              @if(!empty($settings['site_favicon']))
                <img id="faviconPreviewImg" src="{{ asset($settings['site_favicon']) }}" alt="Favicon" style="width:22px;height:22px;object-fit:contain;">
                <div id="faviconPlaceholder" style="display:none;color:var(--primary);font-size:1.1rem;align-items:center;">
                  <i class="bi bi-book-half"></i>
                </div>
              @else
                <div id="faviconPlaceholder" style="color:var(--primary);font-size:1.1rem;display:flex;align-items:center;">
                  <i class="bi bi-book-half"></i>
                </div>
                <img id="faviconPreviewImg" src="" alt="Favicon" style="width:22px;height:22px;object-fit:contain;display:none;">
              @endif
              <span style="font-size:0.75rem;font-weight:600;color:var(--gray-700);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $settings['coaching_name'] ?? 'EduManage Pro' }}
              </span>
            </div>
          </div>
        </div>

        <div>
          <input type="file" name="site_favicon" id="siteFaviconInput" class="form-control" accept=".ico,image/x-icon,image/png,image/svg+xml,image/jpeg" onchange="previewFile(this, 'faviconPreviewImg', 'faviconPlaceholder')">
          @if(!empty($settings['site_favicon']))
            <div style="margin-top:8px;display:flex;align-items:center;gap:6px;">
              <input type="checkbox" name="remove_favicon" value="1" id="removeFaviconCheck">
              <label for="removeFaviconCheck" style="font-size:0.75rem;color:var(--danger);cursor:pointer;font-weight:600;">
                <i class="bi bi-trash"></i> Remove custom favicon &amp; use default
              </label>
            </div>
          @endif
        </div>
      </div>
    </div>

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

    <h5 style="margin-bottom:16px;font-weight:700;color:var(--primary);border-bottom:1px solid var(--border);padding-bottom:8px;">
      <i class="bi bi-chat-dots"></i> SMS &amp; Notifications Service (এসএমএস ও নোটিফিকেশন)
    </h5>

    <div style="background:#f8fafc;border:1px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:24px;">
      <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
          <strong style="font-size:0.9rem;color:var(--gray-900);display:block;margin-bottom:2px;">
            SMS &amp; Notification Feature Status
          </strong>
          <span style="font-size:0.78rem;color:var(--gray-600);">
            Turn off SMS broadcasting, parent alerts, and header notification bells across the system.
          </span>
        </div>
        <select name="sms_notification_status" class="form-select" style="width:200px;font-weight:600;">
          <option value="off" {{ ($settings['sms_notification_status'] ?? 'off') == 'off' ? 'selected' : '' }}>🔴 Disabled (Off / বন্ধ)</option>
          <option value="on" {{ ($settings['sms_notification_status'] ?? 'off') == 'on' ? 'selected' : '' }}>🟢 Enabled (On / চালু)</option>
        </select>
      </div>
    </div>

    <div style="display:flex;justify-content:flex-end;">
      <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Save All Settings</button>
    </div>
  </form>
</div>

<script>
function previewFile(input, previewImgId, placeholderId) {
  const file = input.files && input.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.getElementById(previewImgId);
      const placeholder = document.getElementById(placeholderId);
      if (img) {
        img.src = e.target.result;
        img.style.display = 'block';
      }
      if (placeholder) {
        placeholder.style.display = 'none';
      }
    };
    reader.readAsDataURL(file);
  }
}
</script>
@endsection
