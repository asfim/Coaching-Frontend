@extends('layouts.admin')

@section('title', 'EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>settings</span>
@endsection

@section('content')
<div class="page-header"><div><h2>System Settings</h2><p>Configure and customize your coaching management system</p></div>
<button class="btn btn-primary"><i class="bi bi-check-circle"></i> Save All Changes</button></div>

<div style="display:grid;grid-template-columns:240px 1fr;gap:20px;align-items:flex-start;">
  <!-- Settings Menu -->
  <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;position:sticky;top:88px;">
    <a href="#settings-general" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--primary);background:var(--primary-light);border-left:3px solid var(--primary);transition:var(--transition);" onmouseover="if(0!==0)this.style.background='var(--gray-50)'" onmouseout="if(0!==0)this.style.background='transparent'"><i class="bi bi-gear-fill" style="font-size:.9rem;width:18px;"></i> General Settings</a><a href="#settings-coaching" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(1!==0)this.style.background='var(--gray-50)'" onmouseout="if(1!==0)this.style.background='transparent'"><i class="bi bi-building" style="font-size:.9rem;width:18px;"></i> Coaching Info</a><a href="#settings-branding" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(2!==0)this.style.background='var(--gray-50)'" onmouseout="if(2!==0)this.style.background='transparent'"><i class="bi bi-palette" style="font-size:.9rem;width:18px;"></i> Logo & Branding</a><a href="#settings-academic" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(3!==0)this.style.background='var(--gray-50)'" onmouseout="if(3!==0)this.style.background='transparent'"><i class="bi bi-book" style="font-size:.9rem;width:18px;"></i> Academic Settings</a><a href="#settings-payment" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(4!==0)this.style.background='var(--gray-50)'" onmouseout="if(4!==0)this.style.background='transparent'"><i class="bi bi-credit-card" style="font-size:.9rem;width:18px;"></i> Payment Methods</a><a href="#settings-sms-s" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(5!==0)this.style.background='var(--gray-50)'" onmouseout="if(5!==0)this.style.background='transparent'"><i class="bi bi-chat-dots" style="font-size:.9rem;width:18px;"></i> SMS Settings</a><a href="#settings-notif" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(6!==0)this.style.background='var(--gray-50)'" onmouseout="if(6!==0)this.style.background='transparent'"><i class="bi bi-bell" style="font-size:.9rem;width:18px;"></i> Notifications</a><a href="#settings-users" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(7!==0)this.style.background='var(--gray-50)'" onmouseout="if(7!==0)this.style.background='transparent'"><i class="bi bi-people" style="font-size:.9rem;width:18px;"></i> User Management</a><a href="#settings-roles" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(8!==0)this.style.background='var(--gray-50)'" onmouseout="if(8!==0)this.style.background='transparent'"><i class="bi bi-shield-check" style="font-size:.9rem;width:18px;"></i> Roles & Permissions</a><a href="#settings-backup" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(9!==0)this.style.background='var(--gray-50)'" onmouseout="if(9!==0)this.style.background='transparent'"><i class="bi bi-database" style="font-size:.9rem;width:18px;"></i> Backup & Restore</a><a href="#settings-security" style="display:flex;align-items:center;gap:10px;padding:13px 18px;font-size:.875rem;font-weight:500;color:var(--gray-700);background:transparent;border-left:3px solid transparent;transition:var(--transition);" onmouseover="if(10!==0)this.style.background='var(--gray-50)'" onmouseout="if(10!==0)this.style.background='transparent'"><i class="bi bi-lock" style="font-size:.9rem;width:18px;"></i> Security</a>
  </div>

  <!-- Settings Panels -->
  <div style="display:flex;flex-direction:column;gap:20px;">
    <!-- General -->
    <div id="settings-general" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;">
      <h5 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;"><i class="bi bi-gear-fill" style="color:var(--primary);"></i> General Settings</h5>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="form-group"><label class="form-label">System Language</label><select class="form-select"><option>English</option><option>Bangla</option><option>Both</option></select></div>
        <div class="form-group"><label class="form-label">Timezone</label><select class="form-select"><option>Asia/Dhaka (GMT+6)</option></select></div>
        <div class="form-group"><label class="form-label">Date Format</label><select class="form-select"><option>DD/MM/YYYY</option><option>MM/DD/YYYY</option></select></div>
        <div class="form-group"><label class="form-label">Academic Year</label><select class="form-select"><option>2026-2027</option><option>2025-2026</option></select></div>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px;margin-top:4px;">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;"><input type="checkbox" checked style="width:16px;height:16px;"> <span style="font-size:.875rem;font-weight:500;">Enable SMS notifications</span></label>
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;"><input type="checkbox" checked style="width:16px;height:16px;"> <span style="font-size:.875rem;font-weight:500;">Allow student self-registration</span></label>
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;"><input type="checkbox" style="width:16px;height:16px;"> <span style="font-size:.875rem;font-weight:500;">Enable maintenance mode</span></label>
      </div>
    </div>

    <!-- Coaching Info -->
    <div id="settings-coaching" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;">
      <h5 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;"><i class="bi bi-building" style="color:var(--success);"></i> Coaching Center Information</h5>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="form-group"><label class="form-label">Center Name *</label><input class="form-control" value="EduManage Pro Coaching Center"></div>
        <div class="form-group"><label class="form-label">Registration No.</label><input class="form-control" value="EDU-BD-2014-0012"></div>
        <div class="form-group"><label class="form-label">Phone *</label><input class="form-control" value="+880 1700-123456"></div>
        <div class="form-group"><label class="form-label">Email *</label><input class="form-control" value="info@edumanagepro.com.bd"></div>
        <div class="form-group" style="grid-column:1/-1;"><label class="form-label">Address *</label><input class="form-control" value="House 12, Road 5, Dhanmondi, Dhaka-1205, Bangladesh"></div>
        <div class="form-group"><label class="form-label">Website</label><input class="form-control" value="www.edumanagepro.com.bd"></div>
        <div class="form-group"><label class="form-label">Established Year</label><input class="form-control" value="2014"></div>
      </div>
    </div>

    <!-- Payment Methods -->
    <div id="settings-payment" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;">
      <h5 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;"><i class="bi bi-credit-card" style="color:var(--orange);"></i> Payment Methods</h5>
      <div style="display:flex;flex-direction:column;gap:16px;">
        <div style="display:flex;align-items:center;gap:14px;padding:16px;border:1.5px solid var(--border);border-radius:var(--radius-lg);">
          <div style="width:44px;height:44px;background:var(--success-light,var(--gray-100));border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--success,var(--gray-600));font-size:1.2rem;"><i class="bi bi-phone"></i></div>
          <div style="flex:1;"><strong style="display:block;font-size:.875rem;">bKash</strong><span style="font-size:.78rem;color:var(--gray-500);">01700-123456</span></div>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><span style="font-size:.82rem;color:var(--gray-600);">Enabled</span><div style="width:42px;height:22px;background:var(--primary);border-radius:50px;position:relative;transition:.2s;"><div style="position:absolute;top:2px;right:2px;width:18px;height:18px;background:#fff;border-radius:50%;"></div></div></label>
        </div><div style="display:flex;align-items:center;gap:14px;padding:16px;border:1.5px solid var(--border);border-radius:var(--radius-lg);">
          <div style="width:44px;height:44px;background:var(--warning-light,var(--gray-100));border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--warning,var(--gray-600));font-size:1.2rem;"><i class="bi bi-phone-fill"></i></div>
          <div style="flex:1;"><strong style="display:block;font-size:.875rem;">Nagad</strong><span style="font-size:.78rem;color:var(--gray-500);">01700-654321</span></div>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><span style="font-size:.82rem;color:var(--gray-600);">Enabled</span><div style="width:42px;height:22px;background:var(--primary);border-radius:50px;position:relative;transition:.2s;"><div style="position:absolute;top:2px;right:2px;width:18px;height:18px;background:#fff;border-radius:50%;"></div></div></label>
        </div><div style="display:flex;align-items:center;gap:14px;padding:16px;border:1.5px solid var(--border);border-radius:var(--radius-lg);">
          <div style="width:44px;height:44px;background:var(--primary-light,var(--gray-100));border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--primary,var(--gray-600));font-size:1.2rem;"><i class="bi bi-cash-coin"></i></div>
          <div style="flex:1;"><strong style="display:block;font-size:.875rem;">Cash</strong><span style="font-size:.78rem;color:var(--gray-500);">Office Counter</span></div>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><span style="font-size:.82rem;color:var(--gray-600);">Enabled</span><div style="width:42px;height:22px;background:var(--primary);border-radius:50px;position:relative;transition:.2s;"><div style="position:absolute;top:2px;right:2px;width:18px;height:18px;background:#fff;border-radius:50%;"></div></div></label>
        </div><div style="display:flex;align-items:center;gap:14px;padding:16px;border:1.5px solid var(--border);border-radius:var(--radius-lg);">
          <div style="width:44px;height:44px;background:var(--secondary-light,var(--gray-100));border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--secondary,var(--gray-600));font-size:1.2rem;"><i class="bi bi-bank"></i></div>
          <div style="flex:1;"><strong style="display:block;font-size:.875rem;">Bank Transfer</strong><span style="font-size:.78rem;color:var(--gray-500);">Dutch Bangla Bank</span></div>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><span style="font-size:.82rem;color:var(--gray-600);">Disabled</span><div style="width:42px;height:22px;background:var(--gray-300);border-radius:50px;position:relative;transition:.2s;"><div style="position:absolute;top:2px;left:2px;width:18px;height:18px;background:#fff;border-radius:50%;"></div></div></label>
        </div>
      </div>
    </div>

    <!-- Security -->
    <div id="settings-security" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;">
      <h5 style="font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;"><i class="bi bi-lock" style="color:var(--danger);"></i> Security Settings</h5>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="form-group"><label class="form-label">Session Timeout (minutes)</label><input class="form-control" type="number" value="60"></div>
        <div class="form-group"><label class="form-label">Max Login Attempts</label><input class="form-control" type="number" value="5"></div>
        <div class="form-group"><label class="form-label">Password Min Length</label><input class="form-control" type="number" value="8"></div>
        <div class="form-group"><label class="form-label">Force Password Change (days)</label><input class="form-control" type="number" value="90"></div>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px;margin-top:4px;">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;"><input type="checkbox" checked style="width:16px;height:16px;"> <span style="font-size:.875rem;font-weight:500;">Require uppercase letters in passwords</span></label>
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;"><input type="checkbox" checked style="width:16px;height:16px;"> <span style="font-size:.875rem;font-weight:500;">Enable two-factor authentication (2FA)</span></label>
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;"><input type="checkbox" checked style="width:16px;height:16px;"> <span style="font-size:.875rem;font-weight:500;">Log all admin activities</span></label>
      </div>
    </div>

    <div style="display:flex;justify-content:flex-end;gap:10px;">
      <button class="btn btn-outline">Reset to Default</button>
      <button class="btn btn-primary"><i class="bi bi-check-circle"></i> Save All Changes</button>
    </div>
  </div>
</div>
@endsection

