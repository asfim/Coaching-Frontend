@extends('layouts.admin')

@section('title', 'Accounts & Finance - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Accounts &amp; Finance</span>
@endsection

@section('styles')
<style>
/* Modern Accounts Stat Cards - Full Card Rich Design */
.accounts-stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
  gap: 20px;
  margin-bottom: 26px;
}
.fin-card {
  border-radius: 18px;
  padding: 24px 26px;
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border: 1px solid transparent;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.fin-card:hover {
  transform: translateY(-5px);
}

/* Full Card Themes */
.fin-card.income {
  background: linear-gradient(145deg, #ffffff 0%, #f0fdf4 100%);
  border-color: #bbf7d0;
  box-shadow: 0 4px 20px -2px rgba(16, 185, 129, 0.09), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}
.fin-card.income:hover {
  border-color: #4ade80;
  box-shadow: 0 12px 28px -4px rgba(16, 185, 129, 0.20);
}

.fin-card.expense {
  background: linear-gradient(145deg, #ffffff 0%, #fef2f2 100%);
  border-color: #fecaca;
  box-shadow: 0 4px 20px -2px rgba(239, 68, 68, 0.09), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}
.fin-card.expense:hover {
  border-color: #f87171;
  box-shadow: 0 12px 28px -4px rgba(239, 68, 68, 0.20);
}

.fin-card.balance {
  background: linear-gradient(145deg, #ffffff 0%, #eff6ff 100%);
  border-color: #bfdbfe;
  box-shadow: 0 4px 20px -2px rgba(37, 99, 235, 0.09), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}
.fin-card.balance:hover {
  border-color: #60a5fa;
  box-shadow: 0 12px 28px -4px rgba(37, 99, 235, 0.20);
}

.fin-card.monthly-inc {
  background: linear-gradient(145deg, #ffffff 0%, #f5f3ff 100%);
  border-color: #ddd6fe;
  box-shadow: 0 4px 20px -2px rgba(124, 58, 237, 0.09), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}
.fin-card.monthly-inc:hover {
  border-color: #a78bfa;
  box-shadow: 0 12px 28px -4px rgba(124, 58, 237, 0.20);
}

.fin-card.monthly-exp {
  background: linear-gradient(145deg, #ffffff 0%, #fffbeb 100%);
  border-color: #fde68a;
  box-shadow: 0 4px 20px -2px rgba(217, 119, 6, 0.09), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}
.fin-card.monthly-exp:hover {
  border-color: #fbbf24;
  box-shadow: 0 12px 28px -4px rgba(217, 119, 6, 0.20);
}

.fin-card.monthly-net {
  background: linear-gradient(145deg, #ffffff 0%, #f0fdfa 100%);
  border-color: #99f6e4;
  box-shadow: 0 4px 20px -2px rgba(13, 148, 136, 0.09), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}
.fin-card.monthly-net:hover {
  border-color: #2dd4bf;
  box-shadow: 0 12px 28px -4px rgba(13, 148, 136, 0.20);
}

/* Background Watermark Icon */
.fin-bg-icon {
  position: absolute;
  right: -6px;
  bottom: -10px;
  font-size: 5.2rem;
  opacity: 0.06;
  pointer-events: none;
  transition: transform 0.35s ease, opacity 0.35s ease;
  line-height: 1;
}
.fin-card:hover .fin-bg-icon {
  transform: scale(1.1) rotate(-6deg);
  opacity: 0.12;
}

.fin-card-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  position: relative;
  z-index: 1;
}
.fin-icon-box {
  width: 50px;
  height: 50px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.45rem;
  flex-shrink: 0;
  transition: transform 0.25s ease;
}
.fin-card:hover .fin-icon-box {
  transform: scale(1.08);
}
.fin-icon-box.green {
  background: linear-gradient(135deg, #10b981, #059669);
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(5, 150, 105, 0.28);
}
.fin-icon-box.red {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(220, 38, 38, 0.28);
}
.fin-icon-box.blue {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(29, 78, 216, 0.28);
}
.fin-icon-box.purple {
  background: linear-gradient(135deg, #8b5cf6, #6d28d9);
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(109, 40, 217, 0.28);
}
.fin-icon-box.amber {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(217, 119, 6, 0.28);
}
.fin-icon-box.teal {
  background: linear-gradient(135deg, #14b8a6, #0f766e);
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(15, 118, 110, 0.28);
}

.fin-pill {
  font-size: 0.74rem;
  font-weight: 700;
  padding: 5px 12px;
  border-radius: 50px;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  letter-spacing: 0.02em;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.fin-pill.green { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.fin-pill.red { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.fin-pill.blue { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.fin-pill.purple { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
.fin-pill.amber { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
.fin-pill.teal { background: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; }

.fin-card-body {
  position: relative;
  z-index: 1;
}
.fin-card-body h3 {
  font-size: 1.8rem;
  font-weight: 800;
  letter-spacing: -0.02em;
  margin: 0 0 4px;
  line-height: 1.2;
}
.fin-card-body .fin-label {
  font-size: 0.88rem;
  font-weight: 600;
  color: #475569;
  margin: 0;
}
.fin-card-footer {
  margin-top: 16px;
  padding-top: 12px;
  border-top: 1px solid rgba(0, 0, 0, 0.06);
  font-size: 0.80rem;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative;
  z-index: 1;
}

/* Quick Filter Pills */
.filter-pills-bar {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 14px;
}
.filter-pill-btn {
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 0.82rem;
  font-weight: 600;
  text-decoration: none;
  border: 1px solid var(--border);
  background: #fff;
  color: var(--gray-700);
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.filter-pill-btn:hover {
  background: var(--gray-100);
  color: var(--gray-900);
}
.filter-pill-btn.active {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}
.filter-pill-btn.active.income {
  background: #059669;
  border-color: #059669;
}
.filter-pill-btn.active.expense {
  background: #dc2626;
  border-color: #dc2626;
}

/* Payment Method Badges */
.method-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 6px;
}
.method-badge.cash { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.method-badge.bkash { background: #fdf2f8; color: #be185d; border: 1px solid #fbcfe8; }
.method-badge.nagad { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
.method-badge.bank { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.method-badge.other { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
  <div>
    <h2 style="font-size:1.6rem;font-weight:800;color:var(--gray-900);margin:0 0 4px;display:flex;align-items:center;gap:10px;">
      <i class="bi bi-wallet2" style="color:var(--primary);"></i> Accounts &amp; Finance Management
    </h2>
    <p style="color:var(--gray-500);margin:0;font-size:0.88rem;">
      Coaching center financial tracking, income vouchers, operating expenses, and cash flow
    </p>
  </div>
  <div style="display:flex;gap:10px;align-items:center;">
    <button class="btn btn-success" onclick="openAccountModal('income')" style="display:flex;align-items:center;gap:6px;font-weight:600;padding:8px 16px;box-shadow:0 2px 6px rgba(5,150,105,0.25);">
      <i class="bi bi-plus-circle-fill"></i> Add Income (আয়)
    </button>
    <button class="btn btn-danger" onclick="openAccountModal('expense')" style="display:flex;align-items:center;gap:6px;font-weight:600;padding:8px 16px;box-shadow:0 2px 6px rgba(220,38,38,0.25);">
      <i class="bi bi-dash-circle-fill"></i> Add Expense (ব্যয়)
    </button>
  </div>
</div>

<!-- Financial Overview Stat Cards -->
<div class="accounts-stat-grid">
  <!-- 1. Total Income -->
  <div class="fin-card income">
    <i class="bi bi-wallet2 fin-bg-icon"></i>
    <div class="fin-card-top">
      <div class="fin-icon-box green">
        <i class="bi bi-arrow-down-left-circle-fill"></i>
      </div>
      <span class="fin-pill green">
        <i class="bi bi-graph-up-arrow"></i> Lifetime Inflow
      </span>
    </div>
    <div class="fin-card-body">
      <h3 style="color:#059669;">৳{{ number_format($totalIncome, 2) }}</h3>
      <p class="fin-label">Total Income (সর্বমোট আয়)</p>
    </div>
    <div class="fin-card-footer">
      <span>Student fees &amp; all receipts</span>
      <strong style="color:#059669;"><i class="bi bi-check-circle-fill"></i> {{ $incomeCount }} entries</strong>
    </div>
  </div>

  <!-- 2. Total Expense -->
  <div class="fin-card expense">
    <i class="bi bi-cash-stack fin-bg-icon"></i>
    <div class="fin-card-top">
      <div class="fin-icon-box red">
        <i class="bi bi-arrow-up-right-circle-fill"></i>
      </div>
      <span class="fin-pill red">
        <i class="bi bi-graph-down-arrow"></i> Total Outflow
      </span>
    </div>
    <div class="fin-card-body">
      <h3 style="color:#dc2626;">৳{{ number_format($totalExpense, 2) }}</h3>
      <p class="fin-label">Total Expense (সর্বমোট ব্যয়)</p>
    </div>
    <div class="fin-card-footer">
      <span>Salaries, rent &amp; utilities</span>
      <strong style="color:#dc2626;"><i class="bi bi-dash-circle-fill"></i> {{ $expenseCount }} entries</strong>
    </div>
  </div>

  <!-- 3. Net Cash Reserve / Balance -->
  <div class="fin-card balance">
    <i class="bi bi-safe2 fin-bg-icon"></i>
    <div class="fin-card-top">
      <div class="fin-icon-box blue">
        <i class="bi bi-safe2-fill"></i>
      </div>
      <span class="fin-pill {{ $netBalance >= 0 ? 'blue' : 'red' }}">
        <i class="bi bi-shield-check"></i> {{ $netBalance >= 0 ? 'Net Surplus' : 'Net Deficit' }}
      </span>
    </div>
    <div class="fin-card-body">
      <h3 style="color:{{ $netBalance >= 0 ? '#1d4ed8' : '#dc2626' }};">
        ৳{{ number_format($netBalance, 2) }}
      </h3>
      <p class="fin-label">Net Reserve (বর্তমান স্থিতি)</p>
    </div>
    <div class="fin-card-footer">
      <span>{{ $netBalance >= 0 ? 'Current cash in fund' : 'Deficit balance' }}</span>
      <strong style="color:{{ $netBalance >= 0 ? '#1d4ed8' : '#dc2626' }};">
        {{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }}
      </strong>
    </div>
  </div>

  <!-- 4. This Month Income -->
  <div class="fin-card monthly-inc">
    <i class="bi bi-calendar-check fin-bg-icon"></i>
    <div class="fin-card-top">
      <div class="fin-icon-box purple">
        <i class="bi bi-calendar-check-fill"></i>
      </div>
      <span class="fin-pill purple">
        <i class="bi bi-calendar-month"></i> {{ now()->format('M Y') }}
      </span>
    </div>
    <div class="fin-card-body">
      <h3 style="color:#7c3aed;">৳{{ number_format($monthlyIncome, 2) }}</h3>
      <p class="fin-label">Monthly Income (চলতি মাসের আয়)</p>
    </div>
    <div class="fin-card-footer">
      <span>Collected this month</span>
      <span style="color:#7c3aed;font-weight:700;"><i class="bi bi-calendar2-week"></i> {{ now()->format('F') }}</span>
    </div>
  </div>

  <!-- 5. This Month Expense -->
  <div class="fin-card monthly-exp">
    <i class="bi bi-receipt-cutoff fin-bg-icon"></i>
    <div class="fin-card-top">
      <div class="fin-icon-box amber">
        <i class="bi bi-receipt-cutoff"></i>
      </div>
      <span class="fin-pill amber">
        <i class="bi bi-clock-history"></i> Monthly Spent
      </span>
    </div>
    <div class="fin-card-body">
      <h3 style="color:#d97706;">৳{{ number_format($monthlyExpense, 2) }}</h3>
      <p class="fin-label">Monthly Expense (চলতি মাসের ব্যয়)</p>
    </div>
    <div class="fin-card-footer">
      <span>Paid out this month</span>
      <span style="color:#d97706;font-weight:700;"><i class="bi bi-calendar2-week"></i> {{ now()->format('F') }}</span>
    </div>
  </div>

  <!-- 6. Monthly Net Profit / Margin -->
  <div class="fin-card monthly-net">
    <i class="bi bi-pie-chart fin-bg-icon"></i>
    <div class="fin-card-top">
      <div class="fin-icon-box teal">
        <i class="bi bi-pie-chart-fill"></i>
      </div>
      <span class="fin-pill {{ $monthlyNet >= 0 ? 'teal' : 'red' }}">
        <i class="bi bi-speedometer2"></i> {{ $monthlyNet >= 0 ? 'Margin Profit' : 'Margin Loss' }}
      </span>
    </div>
    <div class="fin-card-body">
      <h3 style="color:{{ $monthlyNet >= 0 ? '#0d9488' : '#dc2626' }};">
        {{ $monthlyNet >= 0 ? '+' : '' }}৳{{ number_format($monthlyNet, 2) }}
      </h3>
      <p class="fin-label">Monthly Net (চলতি মাসের নিট লাভ)</p>
    </div>
    <div class="fin-card-footer">
      <span>{{ $monthlyNet >= 0 ? 'Net positive for this month' : 'Net negative for this month' }}</span>
      <strong style="color:{{ $monthlyNet >= 0 ? '#0d9488' : '#dc2626' }};">
        {{ $monthlyNet >= 0 ? 'Profitable' : 'Deficit' }}
      </strong>
    </div>
  </div>
</div>

<!-- Filter Card -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px 22px;margin-bottom:24px;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:14px;">
    <div style="display:flex;align-items:center;gap:8px;font-weight:700;font-size:0.92rem;color:var(--gray-800);">
      <i class="bi bi-funnel-fill" style="color:var(--primary);"></i> Filter &amp; Search Transactions
    </div>
    <div class="filter-pills-bar" style="margin-bottom:0;">
      <a href="{{ route('admin.accounts.index') }}" class="filter-pill-btn {{ !request('type') ? 'active' : '' }}">
        <i class="bi bi-list-ul"></i> All ({{ $totalCount }})
      </a>
      <a href="{{ route('admin.accounts.index', ['type' => 'income']) }}" class="filter-pill-btn {{ request('type') == 'income' ? 'active income' : '' }}">
        <i class="bi bi-arrow-down-left"></i> Income ({{ $incomeCount }})
      </a>
      <a href="{{ route('admin.accounts.index', ['type' => 'expense']) }}" class="filter-pill-btn {{ request('type') == 'expense' ? 'active expense' : '' }}">
        <i class="bi bi-arrow-up-right"></i> Expense ({{ $expenseCount }})
      </a>
    </div>
  </div>

  <form method="GET" action="{{ route('admin.accounts.index') }}" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:12px;align-items:center;">
    <div>
      <div style="position:relative;">
        <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);"></i>
        <input type="text" name="search" class="form-control" style="padding-left:36px;" placeholder="Search by title, reference, or voucher..." value="{{ request('search') }}">
      </div>
    </div>
    <div>
      <select name="type" class="form-select">
        <option value="">All Types (সকল ধরন)</option>
        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income (+)</option>
        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense (-)</option>
      </select>
    </div>
    <div>
      <select name="category" class="form-select">
        <option value="">All Categories (সকল খাত)</option>
        <option value="Student Fee" {{ request('category') == 'Student Fee' ? 'selected' : '' }}>Student Fee</option>
        <option value="Admission Fee" {{ request('category') == 'Admission Fee' ? 'selected' : '' }}>Admission Fee</option>
        <option value="Book & Material" {{ request('category') == 'Book & Material' ? 'selected' : '' }}>Book &amp; Material</option>
        <option value="Exam Fee" {{ request('category') == 'Exam Fee' ? 'selected' : '' }}>Exam Fee</option>
        <option value="Teacher Salary" {{ request('category') == 'Teacher Salary' ? 'selected' : '' }}>Teacher Salary</option>
        <option value="Staff Salary" {{ request('category') == 'Staff Salary' ? 'selected' : '' }}>Staff Salary</option>
        <option value="Rent" {{ request('category') == 'Rent' ? 'selected' : '' }}>Office Rent</option>
        <option value="Utility" {{ request('category') == 'Utility' ? 'selected' : '' }}>Electricity &amp; Internet</option>
        <option value="Supplies" {{ request('category') == 'Supplies' ? 'selected' : '' }}>Printing &amp; Supplies</option>
        <option value="Marketing" {{ request('category') == 'Marketing' ? 'selected' : '' }}>Marketing &amp; Ads</option>
        <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
      </select>
    </div>
    <div style="display:flex;gap:8px;">
      <button type="submit" class="btn btn-primary" style="padding:8px 16px;"><i class="bi bi-funnel"></i> Apply</button>
      @if(request()->anyFilled(['search', 'type', 'category']))
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline" style="padding:8px 14px;" title="Clear Filters"><i class="bi bi-x-circle"></i> Reset</a>
      @endif
    </div>
  </form>
</div>

<!-- Transactions Table Card -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.03);">
  <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
    <div>
      <h5 style="margin:0;font-weight:700;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-receipt-cutoff" style="color:var(--primary);"></i> Transaction Vouchers &amp; Records
      </h5>
      <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-top:2px;">
        Chronological list of income receipts and expense disbursements
      </span>
    </div>
    <span class="badge badge-primary" style="font-size:0.8rem;padding:6px 12px;">
      Showing {{ $accounts->count() }} of {{ $accounts->total() }} records
    </span>
  </div>

  <div style="overflow-x:auto;">
    <table class="data-table" style="margin:0;">
      <thead>
        <tr style="background:#f8fafc;">
          <th style="padding:14px 18px;">Date</th>
          <th style="padding:14px 18px;">Particulars &amp; Category</th>
          <th style="padding:14px 18px;">Type</th>
          <th style="padding:14px 18px;">Method</th>
          <th style="padding:14px 18px;">Voucher / Ref</th>
          <th style="padding:14px 18px;text-align:right;">Amount (৳)</th>
          <th style="padding:14px 18px;text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($accounts as $acc)
        <tr style="transition:background 0.15s ease;">
          <td style="padding:14px 18px;">
            <div style="font-weight:700;color:var(--gray-800);">
              {{ \Carbon\Carbon::parse($acc->date)->format('d M, Y') }}
            </div>
            <small style="color:var(--gray-400);font-size:0.72rem;">
              {{ \Carbon\Carbon::parse($acc->date)->format('l') }}
            </small>
          </td>
          <td style="padding:14px 18px;">
            <div style="font-weight:700;color:var(--gray-900);font-size:0.92rem;">{{ $acc->title }}</div>
            <div style="display:flex;align-items:center;gap:6px;margin-top:3px;">
              <span class="badge" style="background:#f1f5f9;color:#475569;font-size:0.72rem;font-weight:600;">
                <i class="bi bi-tag-fill" style="font-size:0.65rem;"></i> {{ $acc->category }}
              </span>
              @if($acc->description)
                <span style="font-size:0.75rem;color:var(--gray-500);max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                  — {{ $acc->description }}
                </span>
              @endif
            </div>
          </td>
          <td style="padding:14px 18px;">
            @if($acc->type === 'income')
              <span class="badge badge-success" style="font-size:0.75rem;padding:5px 10px;">
                <i class="bi bi-arrow-down-left"></i> Income
              </span>
            @else
              <span class="badge badge-danger" style="font-size:0.75rem;padding:5px 10px;">
                <i class="bi bi-arrow-up-right"></i> Expense
              </span>
            @endif
          </td>
          <td style="padding:14px 18px;">
            @php
              $mLower = strtolower($acc->payment_method);
              $mClass = 'other';
              if (str_contains($mLower, 'cash')) $mClass = 'cash';
              elseif (str_contains($mLower, 'bkash')) $mClass = 'bkash';
              elseif (str_contains($mLower, 'nagad')) $mClass = 'nagad';
              elseif (str_contains($mLower, 'bank')) $mClass = 'bank';
            @endphp
            <span class="method-badge {{ $mClass }}">
              @if($mClass === 'cash') <i class="bi bi-cash"></i>
              @elseif($mClass === 'bkash' || $mClass === 'nagad') <i class="bi bi-phone"></i>
              @elseif($mClass === 'bank') <i class="bi bi-bank"></i>
              @else <i class="bi bi-credit-card"></i>
              @endif
              {{ $acc->payment_method }}
            </span>
          </td>
          <td style="padding:14px 18px;">
            @if($acc->reference)
              <code style="background:#f1f5f9;padding:3px 8px;border-radius:6px;font-size:0.8rem;color:#1e3a8a;border:1px solid #e2e8f0;">
                {{ $acc->reference }}
              </code>
            @else
              <span style="color:var(--gray-400);font-size:0.85rem;">-</span>
            @endif
          </td>
          <td style="padding:14px 18px;text-align:right;">
            <div style="font-size:1.02rem;font-weight:800;color:{{ $acc->type === 'income' ? '#059669' : '#dc2626' }};">
              {{ $acc->type === 'income' ? '+' : '-' }}৳{{ number_format($acc->amount, 2) }}
            </div>
          </td>
          <td style="padding:14px 18px;text-align:center;">
            <div class="action-btns" style="justify-content:center;">
              @if(auth()->user()?->canEdit('accounts'))
                <button class="btn btn-primary btn-icon btn-sm" onclick='openEditModal(@json($acc))' title="Edit Transaction">
                  <i class="bi bi-pencil"></i>
                </button>
              @endif
              @if(auth()->user()?->canDelete('accounts'))
                <form action="{{ route('admin.accounts.destroy', $acc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this transaction voucher?');" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Delete Voucher">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:48px 20px;color:var(--gray-500);">
            <div style="width:64px;height:64px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.8rem;color:var(--gray-400);">
              <i class="bi bi-wallet2"></i>
            </div>
            <h5 style="margin:0 0 6px;font-weight:700;color:var(--gray-700);">No transactions found</h5>
            <p style="margin:0 0 16px;font-size:0.85rem;color:var(--gray-500);">Try adjusting your filter or record a new income or expense transaction.</p>
            <div style="display:flex;gap:10px;justify-content:center;">
              <button class="btn btn-success btn-sm" onclick="openAccountModal('income')"><i class="bi bi-plus-circle"></i> Add Income</button>
              <button class="btn btn-danger btn-sm" onclick="openAccountModal('expense')"><i class="bi bi-dash-circle"></i> Add Expense</button>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="padding:16px 22px;border-top:1px solid var(--border);background:#fff;border-bottom-left-radius:var(--radius-lg);border-bottom-right-radius:var(--radius-lg);">
    @if($accounts->hasPages())
      {{ $accounts->links() }}
    @else
      <div style="display:flex;justify-content:space-between;align-items:center;width:100%;flex-wrap:wrap;gap:12px;">
        <div style="font-size:0.84rem;color:var(--gray-600);">
          Showing <strong style="color:var(--gray-900);font-weight:700;">{{ $accounts->count() }}</strong> of <strong style="color:var(--gray-900);font-weight:700;">{{ $accounts->total() }}</strong> records
        </div>
        <div style="font-size:0.75rem;color:var(--gray-500);background:#f1f5f9;padding:4px 10px;border-radius:12px;font-weight:600;">
          Page 1 of 1
        </div>
      </div>
    @endif
  </div>
</div>

<!-- Add Transaction Modal -->
<div id="accountModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:92vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--gray-200);">
      <h4 id="modalTitle" style="margin:0;font-weight:700;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-cash-stack" style="color:var(--primary);"></i> Record Transaction
      </h4>
      <button onclick="closeAccountModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:var(--gray-500);"><i class="bi bi-x-lg"></i></button>
    </div>

    <form action="{{ route('admin.accounts.store') }}" method="POST">
      @csrf

      <!-- Type Switcher Tabs -->
      <div style="margin-bottom:16px;background:#f8fafc;padding:6px;border-radius:10px;display:grid;grid-template-columns:1fr 1fr;gap:6px;border:1px solid #e2e8f0;">
        <label style="display:flex;align-items:center;justify-content:center;gap:6px;padding:8px;border-radius:8px;cursor:pointer;font-weight:700;font-size:0.88rem;margin:0;transition:all 0.2s;" id="labelIncome">
          <input type="radio" name="type" value="income" id="typeIncome" checked onchange="switchTransactionType('income')" style="display:none;">
          <i class="bi bi-arrow-down-left-circle-fill" style="color:#059669;"></i> Income (আয়)
        </label>
        <label style="display:flex;align-items:center;justify-content:center;gap:6px;padding:8px;border-radius:8px;cursor:pointer;font-weight:700;font-size:0.88rem;margin:0;transition:all 0.2s;" id="labelExpense">
          <input type="radio" name="type" value="expense" id="typeExpense" onchange="switchTransactionType('expense')" style="display:none;">
          <i class="bi bi-arrow-up-right-circle-fill" style="color:#dc2626;"></i> Expense (ব্যয়)
        </label>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category (খাত) *</label>
          <select name="category" id="modalCategory" class="form-select" required>
            <option value="Student Fee">Student Fee</option>
            <option value="Admission Fee">Admission Fee</option>
            <option value="Book & Material">Book &amp; Material</option>
            <option value="Exam Fee">Exam Fee</option>
            <option value="Other">Other Income</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Amount (টাকার পরিমাণ ৳) *</label>
          <input type="number" step="0.01" name="amount" class="form-control" placeholder="e.g. 5000" required>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Particulars / Title (বিবরণ / শিরোনাম) *</label>
        <input type="text" name="title" id="modalInputTitle" class="form-control" placeholder="e.g. Admission Fee Collection / Office Rent" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Payment Method (পেমেন্ট মাধ্যম) *</label>
          <select name="payment_method" class="form-select" required>
            <option value="Cash">Cash (নগদ)</option>
            <option value="bKash">bKash (বিকাশ)</option>
            <option value="Nagad">Nagad (নগদ)</option>
            <option value="Bank">Bank Deposit (ব্যাংক)</option>
            <option value="Cheque">Cheque (চেক)</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Transaction Date (তারিখ) *</label>
          <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Voucher / Reference No. (ভাউচার বা রেফারেন্স নং)</label>
        <input type="text" name="reference" class="form-control" placeholder="e.g. VCH-2026-001 or TrxID">
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Notes / Description (অতিরিক্ত বিবরণ)</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Optional transaction details or memo..."></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:12px;border-top:1px solid var(--gray-200);">
        <button type="button" class="btn btn-outline" onclick="closeAccountModal()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="modalSubmitBtn">
          <i class="bi bi-check-circle"></i> Save Transaction
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Transaction Modal -->
<div id="editAccountModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;max-height:92vh;overflow-y:auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--gray-200);">
      <h4 style="margin:0;font-weight:700;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-pencil-square" style="color:var(--primary);"></i> Edit Transaction Voucher
      </h4>
      <button onclick="closeEditModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:var(--gray-500);"><i class="bi bi-x-lg"></i></button>
    </div>

    <form id="editAccountForm" method="POST">
      @csrf
      @method('PUT')

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Type (ধরন) *</label>
          <select name="type" id="editType" class="form-select" required>
            <option value="income">Income (+)</option>
            <option value="expense">Expense (-)</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category (খাত) *</label>
          <input type="text" name="category" id="editCategory" class="form-control" required>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Particulars / Title *</label>
        <input type="text" name="title" id="editTitle" class="form-control" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Amount (৳) *</label>
          <input type="number" step="0.01" name="amount" id="editAmount" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Payment Method *</label>
          <select name="payment_method" id="editMethod" class="form-select" required>
            <option value="Cash">Cash</option>
            <option value="bKash">bKash</option>
            <option value="Nagad">Nagad</option>
            <option value="Bank">Bank Deposit</option>
            <option value="Cheque">Cheque</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Date *</label>
          <input type="date" name="date" id="editDate" class="form-control" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Voucher / Ref</label>
          <input type="text" name="reference" id="editRef" class="form-control">
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description / Memo</label>
        <textarea name="description" id="editDesc" class="form-control" rows="2"></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:12px;border-top:1px solid var(--gray-200);">
        <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Voucher</button>
      </div>
    </form>
  </div>
</div>

<script>
function openAccountModal(type) {
  const m = document.getElementById('accountModal');
  m.style.display = 'flex';
  switchTransactionType(type);
}

function closeAccountModal() {
  document.getElementById('accountModal').style.display = 'none';
}

function switchTransactionType(type) {
  const radioInc = document.getElementById('typeIncome');
  const radioExp = document.getElementById('typeExpense');
  const labelInc = document.getElementById('labelIncome');
  const labelExp = document.getElementById('labelExpense');
  const modalTitle = document.getElementById('modalTitle');
  const cat = document.getElementById('modalCategory');

  if (type === 'income') {
    radioInc.checked = true;
    labelInc.style.background = '#d1fae5';
    labelInc.style.color = '#065f46';
    labelExp.style.background = 'transparent';
    labelExp.style.color = 'var(--gray-600)';
    modalTitle.innerHTML = '<i class="bi bi-plus-circle-fill" style="color:#059669;"></i> Record Income Voucher (+)';
    cat.innerHTML = `
      <option value="Student Fee">Student Fee (শিক্ষার্থীর ফি)</option>
      <option value="Admission Fee">Admission Fee (ভর্তি ফি)</option>
      <option value="Book & Material">Book &amp; Material (বই ও শীট)</option>
      <option value="Exam Fee">Exam Fee (পরীক্ষার ফি)</option>
      <option value="Other">Other Income (অন্যান্য আয়)</option>
    `;
  } else {
    radioExp.checked = true;
    labelExp.style.background = '#fee2e2';
    labelExp.style.color = '#991b1b';
    labelInc.style.background = 'transparent';
    labelInc.style.color = 'var(--gray-600)';
    modalTitle.innerHTML = '<i class="bi bi-dash-circle-fill" style="color:#dc2626;"></i> Record Expense Voucher (-)';
    cat.innerHTML = `
      <option value="Teacher Salary">Teacher Salary (শিক্ষক বেতন)</option>
      <option value="Staff Salary">Staff Salary (স্টাফ বেতন)</option>
      <option value="Rent">Office Rent (অফিস ভাড়া)</option>
      <option value="Utility">Electricity &amp; Internet (বিদ্যুৎ ও ইন্টারনেট)</option>
      <option value="Supplies">Printing &amp; Supplies (প্রিন্টিং ও স্টেশনারি)</option>
      <option value="Marketing">Marketing &amp; Ads (প্রচার ও বিজ্ঞাপন)</option>
      <option value="Other">Other Expense (অন্যান্য খরচ)</option>
    `;
  }
}

function openEditModal(data) {
  const m = document.getElementById('editAccountModal');
  m.style.display = 'flex';
  document.getElementById('editAccountForm').action = '/admin/accounts/' + data.id;
  document.getElementById('editType').value = data.type;
  document.getElementById('editCategory').value = data.category;
  document.getElementById('editTitle').value = data.title;
  document.getElementById('editAmount').value = data.amount;
  document.getElementById('editMethod').value = data.payment_method;
  document.getElementById('editDate').value = data.date ? data.date.substring(0, 10) : '';
  document.getElementById('editRef').value = data.reference || '';
  document.getElementById('editDesc').value = data.description || '';
}

function closeEditModal() {
  document.getElementById('editAccountModal').style.display = 'none';
}
</script>
@endsection
