@extends('layouts.public')

@section('title', 'Login - EduManage Pro Coaching Management')

@section('styles')
<style>
.login-container {
  min-height: calc(100vh - 180px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 16px;
  background: linear-gradient(135deg, #f0f7ff 0%, #faf5ff 50%, #f0fdf4 100%);
}
.login-card {
  background: #ffffff;
  border-radius: var(--radius-xl);
  box-shadow: 0 20px 45px rgba(15, 23, 42, 0.09);
  border: 1px solid var(--border);
  width: 100%;
  max-width: 480px;
  padding: 36px 32px;
}
.role-pills {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  background: var(--gray-100);
  padding: 4px;
  border-radius: var(--radius);
  gap: 4px;
  margin-bottom: 24px;
}
.role-pill {
  border: none;
  background: transparent;
  padding: 8px 4px;
  font-size: .8rem;
  font-weight: 600;
  color: var(--gray-600);
  border-radius: var(--radius-sm);
  cursor: pointer;
  transition: all .2s;
  text-align: center;
}
.role-pill.active {
  background: #fff;
  color: var(--primary);
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.demo-accounts {
  background: #f8fafc;
  border: 1px dashed var(--gray-300);
  border-radius: var(--radius);
  padding: 14px;
  margin-top: 24px;
}
</style>
@endsection

@section('content')
<div class="login-container">
  <div class="login-card">
    <div style="text-align:center;margin-bottom:24px;">
      <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:14px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;margin-bottom:12px;box-shadow:0 8px 20px rgba(37,99,235,.25);">
        <i class="bi bi-shield-lock-fill"></i>
      </div>
      <h3 style="font-weight:800;color:var(--gray-900);margin-bottom:4px;">Account Login</h3>
      <p style="font-size:.875rem;color:var(--gray-500);margin:0;">Sign in to access your coaching portal</p>
    </div>

    <!-- Quick Role Pill Selector -->
    <div class="role-pills" id="roleTabs">
      <button type="button" class="role-pill active" onclick="switchRole('admin', 'admin@edumanage.com')"><i class="bi bi-shield-lock"></i> Admin</button>
      <button type="button" class="role-pill" onclick="switchRole('teacher', 'teacher@edumanage.com')"><i class="bi bi-person-badge"></i> Teacher</button>
      <button type="button" class="role-pill" onclick="switchRole('student', 'student@edumanage.com')"><i class="bi bi-mortarboard"></i> Student</button>
      <button type="button" class="role-pill" onclick="switchRole('parent', 'parent@edumanage.com')"><i class="bi bi-people"></i> Parent</button>
    </div>

    @if(session('success'))
      <div style="background:#ecfdf5;border-left:4px solid #059669;padding:10px 14px;border-radius:6px;margin-bottom:16px;color:#065f46;font-size:.85rem;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div style="background:#fef2f2;border-left:4px solid #dc2626;padding:10px 14px;border-radius:6px;margin-bottom:16px;color:#991b1b;font-size:.85rem;">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
      </div>
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;border-left:4px solid #dc2626;padding:10px 14px;border-radius:6px;margin-bottom:16px;color:#991b1b;font-size:.85rem;">
        <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
      @csrf

      <div class="form-group" style="margin-bottom:16px;">
        <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:6px;color:var(--gray-700);">Email / Student ID / Phone</label>
        <div style="position:relative;">
          <input type="text" name="login" id="loginField" class="form-control" placeholder="admin@edumanage.com" value="{{ old('login', 'admin@edumanage.com') }}" required style="width:100%;padding:10px 12px 10px 38px;border-radius:var(--radius);border:1px solid var(--border);">
          <i class="bi bi-person" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
          <label style="font-size:.85rem;font-weight:600;color:var(--gray-700);margin:0;">Password</label>
          <span style="font-size:.78rem;color:var(--primary);cursor:pointer;" onclick="fillDefaultPassword()">Default: password</span>
        </div>
        <div style="position:relative;">
          <input type="password" name="password" id="passwordField" class="form-control" placeholder="••••••••" value="password" required style="width:100%;padding:10px 38px 10px 38px;border-radius:var(--radius);border:1px solid var(--border);">
          <i class="bi bi-lock" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
          <i class="bi bi-eye" id="togglePasswordIcon" onclick="togglePasswordVisibility()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);cursor:pointer;"></i>
        </div>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;font-size:.85rem;">
        <label style="display:flex;align-items:center;gap:6px;color:var(--gray-600);cursor:pointer;margin:0;">
          <input type="checkbox" name="remember" value="1" checked style="accent-color:var(--primary);"> Remember Me
        </label>
        <a href="{{ route('contact') }}" style="color:var(--primary);text-decoration:none;">Forgot Password?</a>
      </div>

      <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;font-weight:700;box-shadow:0 6px 16px rgba(37,99,235,.25);">
        <i class="bi bi-box-arrow-in-right"></i> Sign In to Portal
      </button>
    </form>

    <!-- 1-Click Demo Buttons for Instant Evaluation -->
    <div class="demo-accounts">
      <div style="display:flex;align-items:center;gap:6px;font-size:.8rem;font-weight:700;color:var(--gray-700);margin-bottom:8px;">
        <i class="bi bi-lightning-charge-fill" style="color:#f59e0b;"></i> 1-Click Demo Login:
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
        <a href="{{ route('login.quick', 'admin') }}" class="btn btn-sm btn-outline" style="font-size:.78rem;justify-content:center;background:#fff;">
          <i class="bi bi-shield-check text-primary"></i> As Admin
        </a>
        <a href="{{ route('login.quick', 'teacher') }}" class="btn btn-sm btn-outline" style="font-size:.78rem;justify-content:center;background:#fff;">
          <i class="bi bi-person-badge text-success"></i> As Teacher
        </a>
        <a href="{{ route('login.quick', 'student') }}" class="btn btn-sm btn-outline" style="font-size:.78rem;justify-content:center;background:#fff;">
          <i class="bi bi-mortarboard text-secondary"></i> As Student
        </a>
        <a href="{{ route('login.quick', 'parent') }}" class="btn btn-sm btn-outline" style="font-size:.78rem;justify-content:center;background:#fff;">
          <i class="bi bi-people text-warning"></i> As Parent
        </a>
      </div>
    </div>

    <div style="text-align:center;margin-top:20px;font-size:.85rem;color:var(--gray-500);">
      Back to website? <a href="{{ route('home') }}" style="color:var(--primary);font-weight:600;text-decoration:none;">Go to Homepage</a>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function switchRole(role, defaultEmail) {
  document.querySelectorAll('.role-pill').forEach(btn => btn.classList.remove('active'));
  event.currentTarget.classList.add('active');
  document.getElementById('loginField').value = defaultEmail;
  document.getElementById('passwordField').value = 'password';
}

function fillDefaultPassword() {
  document.getElementById('passwordField').value = 'password';
}

function togglePasswordVisibility() {
  const pwd = document.getElementById('passwordField');
  const icon = document.getElementById('togglePasswordIcon');
  if (pwd.type === 'password') {
    pwd.type = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    pwd.type = 'password';
    icon.className = 'bi bi-eye';
  }
}
</script>
@endsection
