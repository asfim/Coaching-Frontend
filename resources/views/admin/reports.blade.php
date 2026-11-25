@extends('layouts.admin')

@section('title', 'Reports & Analytics - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Reports &amp; Analytics</span>
@endsection

@section('styles')
<style>
/* Clickable Report Selector Cards */
.report-type-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
  gap: 14px;
  margin-bottom: 24px;
}
.report-card-btn {
  background: #ffffff;
  border: 1.5px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 16px 14px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
  position: relative;
  text-decoration: none;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.report-card-btn:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-md);
  border-color: var(--primary);
}
.report-card-btn.active {
  background: #eff6ff;
  border-color: var(--primary);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
}
.report-card-btn .icon-circle {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  margin-bottom: 10px;
}
.report-card-btn h5 {
  font-size: 0.85rem;
  font-weight: 700;
  margin: 0 0 2px 0;
  color: var(--gray-800);
}
.report-card-btn p {
  font-size: 0.72rem;
  color: var(--gray-500);
  margin: 0;
}
.report-card-btn .active-check {
  display: none;
  position: absolute;
  top: 8px;
  right: 8px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: var(--primary);
  color: #fff;
  font-size: 0.65rem;
  align-items: center;
  justify-content: center;
}
.report-card-btn.active .active-check {
  display: flex;
}

/* Print Styling */
@media print {
  .sidebar, .topbar, .breadcrumb-bar, .report-type-grid, .filter-card, .btn-no-print {
    display: none !important;
  }
  .main-content {
    margin: 0 !important;
    padding: 0 !important;
  }
  .page-content {
    padding: 0 !important;
  }
  .table-wrapper {
    box-shadow: none !important;
    border: 1px solid #000 !important;
  }
  body {
    background: #fff !important;
  }
}
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:20px;">
  <div>
    <h2 style="font-size:1.6rem;font-weight:800;color:var(--gray-900);margin:0 0 4px;display:flex;align-items:center;gap:10px;">
      <i class="bi bi-file-earmark-bar-graph-fill" style="color:var(--primary);"></i> Reports &amp; Analytics
    </h2>
    <p style="color:var(--gray-500);margin:0;font-size:0.88rem;">
      Generate dynamic reports with customized filters, real-time analytics, and instant export
    </p>
  </div>
  <div class="btn-no-print" style="display:flex;gap:8px;align-items:center;">
    <button type="button" class="btn btn-outline btn-sm" onclick="exportReportToCSV()">
      <i class="bi bi-file-earmark-excel"></i> Export CSV
    </button>
    <button type="button" class="btn btn-outline btn-sm" onclick="window.print()">
      <i class="bi bi-printer"></i> Print Report
    </button>
  </div>
</div>

<!-- Interactive Report Type Selector Cards -->
<div class="report-type-grid btn-no-print">
  <!-- 1. Fee Collection -->
  <a href="{{ route('admin.reports', array_merge(request()->except('page'), ['report_type' => 'fees'])) }}" 
     class="report-card-btn {{ $reportType === 'fees' ? 'active' : '' }}">
    <div class="active-check"><i class="bi bi-check"></i></div>
    <div class="icon-circle" style="background:#ecfdf5;color:#059669;">
      <i class="bi bi-cash-coin"></i>
    </div>
    <h5>Fee Collection</h5>
    <p>Payment history &amp; cash</p>
  </a>

  <!-- 2. Due Fees Report -->
  <a href="{{ route('admin.reports', array_merge(request()->except('page'), ['report_type' => 'due'])) }}" 
     class="report-card-btn {{ $reportType === 'due' ? 'active' : '' }}">
    <div class="active-check"><i class="bi bi-check"></i></div>
    <div class="icon-circle" style="background:#fef2f2;color:#dc2626;">
      <i class="bi bi-exclamation-circle-fill"></i>
    </div>
    <h5>Due Report</h5>
    <p>Outstanding dues</p>
  </a>

  <!-- 3. Student Enrollment -->
  <a href="{{ route('admin.reports', array_merge(request()->except('page'), ['report_type' => 'students'])) }}" 
     class="report-card-btn {{ $reportType === 'students' ? 'active' : '' }}">
    <div class="active-check"><i class="bi bi-check"></i></div>
    <div class="icon-circle" style="background:#eff6ff;color:#2563eb;">
      <i class="bi bi-person-vcard"></i>
    </div>
    <h5>Student Report</h5>
    <p>Admission &amp; profiles</p>
  </a>

  <!-- 4. Exam & Results -->
  <a href="{{ route('admin.reports', array_merge(request()->except('page'), ['report_type' => 'results'])) }}" 
     class="report-card-btn {{ $reportType === 'results' ? 'active' : '' }}">
    <div class="active-check"><i class="bi bi-check"></i></div>
    <div class="icon-circle" style="background:#fefce8;color:#ca8a04;">
      <i class="bi bi-trophy-fill"></i>
    </div>
    <h5>Exam Results</h5>
    <p>Marks, GPA &amp; Merit</p>
  </a>

  <!-- 5. Attendance Report -->
  <a href="{{ route('admin.reports', array_merge(request()->except('page'), ['report_type' => 'attendance'])) }}" 
     class="report-card-btn {{ $reportType === 'attendance' ? 'active' : '' }}">
    <div class="active-check"><i class="bi bi-check"></i></div>
    <div class="icon-circle" style="background:#f5f3ff;color:#7c3aed;">
      <i class="bi bi-calendar2-check-fill"></i>
    </div>
    <h5>Attendance</h5>
    <p>Daily/monthly status</p>
  </a>

  <!-- 6. Financial Accounts -->
  <a href="{{ route('admin.reports', array_merge(request()->except('page'), ['report_type' => 'accounts'])) }}" 
     class="report-card-btn {{ $reportType === 'accounts' ? 'active' : '' }}">
    <div class="active-check"><i class="bi bi-check"></i></div>
    <div class="icon-circle" style="background:#f0fdfa;color:#0d9488;">
      <i class="bi bi-wallet2"></i>
    </div>
    <h5>Finance &amp; PnL</h5>
    <p>Income &amp; expenditure</p>
  </a>
</div>

<!-- Report Filter Form Card -->
<div class="filter-card btn-no-print" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px 20px;margin-bottom:24px;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
  <form method="GET" action="{{ route('admin.reports') }}" id="reportFilterForm">
    <input type="hidden" name="report_type" value="{{ $reportType }}">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
      <h5 style="margin:0;font-weight:700;font-size:0.92rem;color:var(--gray-800);display:flex;align-items:center;gap:6px;">
        <i class="bi bi-funnel-fill" style="color:var(--primary);"></i> Filters for 
        <span style="color:var(--primary);text-transform:capitalize;">{{ str_replace('_', ' ', $reportType) }} Report</span>
      </h5>
      <span style="font-size:0.78rem;color:var(--gray-500);">Refine dates, classes, and batches</span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;align-items:end;">
      <!-- Date From -->
      <div>
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Date From</label>
        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
      </div>

      <!-- Date To -->
      <div>
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Date To</label>
        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
      </div>

      <!-- Class -->
      <div>
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Class</label>
        <select name="class_name" class="form-select">
          <option value="">All Classes (সকল ক্লাস)</option>
          @foreach($classes as $c)
            <option value="{{ $c->name }}" {{ $className == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Batch -->
      <div>
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Batch</label>
        <select name="batch_id" class="form-select">
          <option value="">All Batches (সকল ব্যাচ)</option>
          @foreach($batches as $b)
            <option value="{{ $b->id }}" {{ $batchId == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->batch_code }})</option>
          @endforeach
        </select>
      </div>

      <!-- Stream / Program Type -->
      <div>
        <label style="display:block;font-size:0.8rem;font-weight:600;margin-bottom:4px;">Program Type</label>
        <select name="program_type" class="form-select">
          <option value="">All Programs</option>
          <option value="Academic" {{ $programType === 'Academic' ? 'selected' : '' }}>Academic Class</option>
          <option value="Target" {{ $programType === 'Target' ? 'selected' : '' }}>Target Batch</option>
        </select>
      </div>

      <!-- Submit & Reset -->
      <div style="display:flex;gap:8px;">
        <button type="submit" class="btn btn-primary" style="flex:1;">
          <i class="bi bi-search"></i> Generate
        </button>
        @if(request()->anyFilled(['date_from', 'class_name', 'batch_id', 'program_type', 'exam_id']))
          <a href="{{ route('admin.reports', ['report_type' => $reportType]) }}" class="btn btn-outline" title="Clear Filters">
            <i class="bi bi-x-circle"></i>
          </a>
        @endif
      </div>
    </div>
  </form>
</div>

<!-- Summary Metrics & Chart Card -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:24px;box-shadow:0 1px 4px rgba(0,0,0,0.03);">
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">
    <!-- Chart Container -->
    <div>
      <h5 style="margin:0 0 14px 0;font-weight:700;font-size:0.95rem;color:var(--gray-800);">
        <i class="bi bi-bar-chart-fill" style="color:var(--primary);"></i> {{ $chartTitle ?: 'Visual Analysis Chart' }}
      </h5>
      <div style="position:relative;height:220px;width:100%;">
        <canvas id="reportChartCanvas"></canvas>
      </div>
    </div>

    <!-- Summary Numbers Breakdown -->
    <div>
      <h5 style="margin:0 0 14px 0;font-weight:700;font-size:0.95rem;color:var(--gray-800);">
        <i class="bi bi-calculator-fill" style="color:var(--primary);"></i> Report Summary
      </h5>
      <div style="display:flex;flex-direction:column;gap:10px;">
        @if($reportType === 'fees' || $reportType === 'due')
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#ecfdf5;border-radius:8px;">
            <span style="font-size:0.85rem;color:#047857;font-weight:600;">Total Collected:</span>
            <strong style="color:#047857;font-size:0.95rem;">৳{{ number_format($summary['total_collected'] ?? 0, 2) }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#fef2f2;border-radius:8px;">
            <span style="font-size:0.85rem;color:#b91c1c;font-weight:600;">Outstanding Due:</span>
            <strong style="color:#b91c1c;font-size:0.95rem;">৳{{ number_format($summary['total_due'] ?? 0, 2) }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#eff6ff;border-radius:8px;">
            <span style="font-size:0.85rem;color:#1e40af;font-weight:600;">Collection Rate:</span>
            <strong style="color:#1e40af;font-size:0.95rem;">{{ $summary['collection_rate'] ?? 0 }}%</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
            <span style="font-size:0.85rem;color:var(--gray-600);font-weight:600;">Invoices Found:</span>
            <strong style="color:var(--gray-800);">{{ $summary['total_records'] ?? 0 }}</strong>
          </div>

        @elseif($reportType === 'students')
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#eff6ff;border-radius:8px;">
            <span style="font-size:0.85rem;color:#1e40af;font-weight:600;">Total Students:</span>
            <strong style="color:#1e40af;font-size:0.95rem;">{{ $summary['total_records'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#ecfdf5;border-radius:8px;">
            <span style="font-size:0.85rem;color:#047857;font-weight:600;">Active Enrolled:</span>
            <strong style="color:#047857;font-size:0.95rem;">{{ $summary['active_count'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#f8fafc;border-radius:8px;">
            <span style="font-size:0.85rem;color:var(--gray-600);font-weight:600;">Academic Program:</span>
            <strong style="color:#0369a1;">{{ $summary['academic_count'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#fffbeb;border-radius:8px;">
            <span style="font-size:0.85rem;color:#b45309;font-weight:600;">Target Batch:</span>
            <strong style="color:#b45309;">{{ $summary['target_count'] ?? 0 }}</strong>
          </div>

        @elseif($reportType === 'results')
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#eff6ff;border-radius:8px;">
            <span style="font-size:0.85rem;color:#1e40af;font-weight:600;">Total Examinees:</span>
            <strong style="color:#1e40af;font-size:0.95rem;">{{ $summary['total_records'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#ecfdf5;border-radius:8px;">
            <span style="font-size:0.85rem;color:#047857;font-weight:600;">Passed Count:</span>
            <strong style="color:#047857;font-size:0.95rem;">{{ $summary['pass_count'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#fefce8;border-radius:8px;">
            <span style="font-size:0.85rem;color:#a16207;font-weight:600;">GPA 5.00 (A+):</span>
            <strong style="color:#a16207;font-size:0.95rem;">{{ $summary['gpa5_count'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#f8fafc;border-radius:8px;">
            <span style="font-size:0.85rem;color:var(--gray-600);font-weight:600;">Batch Average GPA:</span>
            <strong style="color:var(--gray-800);">{{ $summary['avg_gpa'] ?? 0 }}</strong>
          </div>

        @elseif($reportType === 'attendance')
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#eff6ff;border-radius:8px;">
            <span style="font-size:0.85rem;color:#1e40af;font-weight:600;">Attendance Rate:</span>
            <strong style="color:#1e40af;font-size:0.95rem;">{{ $summary['attendance_rate'] ?? 0 }}%</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#ecfdf5;border-radius:8px;">
            <span style="font-size:0.85rem;color:#047857;font-weight:600;">Present Count:</span>
            <strong style="color:#047857;font-size:0.95rem;">{{ $summary['present_count'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#fef2f2;border-radius:8px;">
            <span style="font-size:0.85rem;color:#b91c1c;font-weight:600;">Absent Count:</span>
            <strong style="color:#b91c1c;font-size:0.95rem;">{{ $summary['absent_count'] ?? 0 }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#fffbeb;border-radius:8px;">
            <span style="font-size:0.85rem;color:#b45309;font-weight:600;">Late Count:</span>
            <strong style="color:#b45309;">{{ $summary['late_count'] ?? 0 }}</strong>
          </div>

        @elseif($reportType === 'accounts')
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#ecfdf5;border-radius:8px;">
            <span style="font-size:0.85rem;color:#047857;font-weight:600;">Total Income:</span>
            <strong style="color:#047857;font-size:0.95rem;">৳{{ number_format($summary['total_income'] ?? 0, 2) }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#fef2f2;border-radius:8px;">
            <span style="font-size:0.85rem;color:#b91c1c;font-weight:600;">Total Expense:</span>
            <strong style="color:#b91c1c;font-size:0.95rem;">৳{{ number_format($summary['total_expense'] ?? 0, 2) }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;padding:10px 14px;background:#eff6ff;border-radius:8px;">
            <span style="font-size:0.85rem;color:#1e40af;font-weight:600;">Net Balance:</span>
            <strong style="color:#1e40af;font-size:0.95rem;">৳{{ number_format($summary['net_profit'] ?? 0, 2) }}</strong>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Dynamic Report Data Table Card -->
<div class="table-wrapper" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.03);">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
    <div>
      <h5 style="margin:0;font-weight:700;display:flex;align-items:center;gap:8px;">
        <i class="bi bi-table" style="color:var(--primary);"></i> 
        <span style="text-transform:capitalize;">{{ str_replace('_', ' ', $reportType) }}</span> Records
      </h5>
      <span style="font-size:0.8rem;color:var(--gray-500);display:block;margin-top:2px;">
        Detailed tabulated records matching your query criteria
      </span>
    </div>
    <span class="badge badge-primary" style="font-size:0.8rem;padding:6px 12px;">
      Total: {{ $reportData instanceof \Illuminate\Pagination\LengthAwarePaginator ? $reportData->total() : $reportData->count() }} records
    </span>
  </div>

  <div style="overflow-x:auto;">
    <table class="data-table" id="reportTableData" style="margin:0;">
      <!-- Table Headers based on Report Type -->
      <thead>
        @if($reportType === 'fees' || $reportType === 'due')
          <tr>
            <th>Invoice No</th>
            <th>Student</th>
            <th>Program / Class</th>
            <th>Month / Plan</th>
            <th>Total (৳)</th>
            <th>Paid (৳)</th>
            <th>Due (৳)</th>
            <th>Status</th>
            <th>Method</th>
            <th>Date</th>
          </tr>
        @elseif($reportType === 'students')
          <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Program</th>
            <th>Class</th>
            <th>Batch</th>
            <th>Phone</th>
            <th>Admission Date</th>
            <th>Status</th>
          </tr>
        @elseif($reportType === 'results')
          <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Exam Title</th>
            <th>Subject</th>
            <th>Program</th>
            <th>Marks</th>
            <th>GPA</th>
            <th>Grade</th>
            <th>Merit</th>
          </tr>
        @elseif($reportType === 'attendance')
          <tr>
            <th>Date</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Class / Batch</th>
            <th>Status</th>
            <th>Remarks</th>
          </tr>
        @elseif($reportType === 'accounts')
          <tr>
            <th>Date</th>
            <th>Particulars / Title</th>
            <th>Category</th>
            <th>Type</th>
            <th>Method</th>
            <th>Voucher Ref</th>
            <th>Amount (৳)</th>
          </tr>
        @endif
      </thead>

      <!-- Table Body -->
      <tbody>
        @forelse($reportData as $row)
          @if($reportType === 'fees' || $reportType === 'due')
            <tr>
              <td><code>{{ $row->invoice_no }}</code></td>
              <td>
                <strong>{{ $row->student?->name ?: 'N/A' }}</strong>
                <small style="display:block;color:var(--gray-500);">#{{ $row->student?->student_id }}</small>
              </td>
              <td>
                <span class="badge {{ $row->student?->program_type === 'Target' ? 'badge-warning' : 'badge-primary' }}" style="font-size:0.7rem;">
                  {{ $row->student?->program_type ?: 'Academic' }}
                </span>
                <span style="font-size:0.82rem;display:block;margin-top:2px;">{{ $row->student?->class_name }}</span>
              </td>
              <td>
                <strong>{{ $row->month }} {{ $row->year }}</strong>
                @if($row->fee_type === 'installment')
                  <small style="display:block;color:#b45309;font-weight:600;">
                    Inst. {{ $row->installment_no }}{{ !empty($row->total_installments) ? '/' . $row->total_installments : ' (Unlimited)' }}
                  </small>
                @endif
              </td>
              <td>৳{{ number_format($row->amount - $row->discount, 2) }}</td>
              <td style="color:var(--success);font-weight:700;">৳{{ number_format($row->paid_amount, 2) }}</td>
              <td>
                @if($row->due_amount > 0)
                  <strong style="color:var(--danger);">৳{{ number_format($row->due_amount, 2) }}</strong>
                @else
                  <span style="color:var(--gray-400);">৳0.00</span>
                @endif
              </td>
              <td>
                <span class="badge {{ $row->status === 'Paid' ? 'badge-success' : ($row->status === 'Partial' ? 'badge-warning' : 'badge-danger') }}">
                  {{ $row->status }}
                </span>
              </td>
              <td><span class="badge badge-secondary">{{ $row->payment_method }}</span></td>
              <td>{{ $row->payment_date ? \Carbon\Carbon::parse($row->payment_date)->format('d M Y') : '-' }}</td>
            </tr>

          @elseif($reportType === 'students')
            <tr>
              <td><code>{{ $row->student_id }}</code></td>
              <td><strong>{{ $row->name }}</strong></td>
              <td>
                <span class="badge {{ $row->program_type === 'Target' ? 'badge-warning' : 'badge-primary' }}">
                  {{ $row->program_type ?: 'Academic' }}
                </span>
              </td>
              <td>{{ $row->class_name }}</td>
              <td>{{ $row->batch?->name ?: '-' }}</td>
              <td>{{ $row->phone }}</td>
              <td>{{ $row->admission_date ? \Carbon\Carbon::parse($row->admission_date)->format('d M Y') : '-' }}</td>
              <td><span class="badge {{ $row->status === 'Active' ? 'badge-success' : 'badge-danger' }}">{{ $row->status }}</span></td>
            </tr>

          @elseif($reportType === 'results')
            <tr>
              <td><code>{{ $row->student?->student_id }}</code></td>
              <td><strong>{{ $row->student?->name ?: 'N/A' }}</strong></td>
              <td>{{ $row->exam?->title }}</td>
              <td>{{ $row->exam?->subjectModel?->name ?: ($row->exam?->subject ?: '-') }}</td>
              <td>
                <span class="badge {{ $row->program_type === 'Target' ? 'badge-warning' : 'badge-primary' }}">
                  {{ $row->program_type ?: 'Academic' }}
                </span>
              </td>
              <td><strong>{{ $row->total_marks }}</strong></td>
              <td><strong style="color:var(--success);">{{ number_format($row->gpa, 2) }}</strong></td>
              <td><span class="badge badge-primary">{{ $row->grade }}</span></td>
              <td><span class="badge badge-success">#{{ $row->merit_position ?: '-' }}</span></td>
            </tr>

          @elseif($reportType === 'attendance')
            <tr>
              <td><strong>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</strong></td>
              <td><code>{{ $row->student?->student_id }}</code></td>
              <td><strong>{{ $row->student?->name ?: 'N/A' }}</strong></td>
              <td>{{ $row->student?->class_name }} ({{ $row->batch?->batch_code ?: '-' }})</td>
              <td>
                <span class="badge {{ $row->status === 'present' ? 'badge-success' : ($row->status === 'late' ? 'badge-warning' : 'badge-danger') }}">
                  {{ ucfirst($row->status) }}
                </span>
              </td>
              <td>{{ $row->remarks ?: '-' }}</td>
            </tr>

          @elseif($reportType === 'accounts')
            <tr>
              <td><strong>{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</strong></td>
              <td><strong>{{ $row->title }}</strong></td>
              <td><span class="badge badge-secondary">{{ $row->category }}</span></td>
              <td>
                <span class="badge {{ $row->type === 'income' ? 'badge-success' : 'badge-danger' }}">
                  {{ ucfirst($row->type) }}
                </span>
              </td>
              <td><span class="badge badge-primary">{{ $row->payment_method }}</span></td>
              <td><code>{{ $row->reference ?: '-' }}</code></td>
              <td style="font-weight:700;color:{{ $row->type === 'income' ? '#059669' : '#dc2626' }};">
                {{ $row->type === 'income' ? '+' : '-' }}৳{{ number_format($row->amount, 2) }}
              </td>
            </tr>
          @endif

        @empty
          <tr>
            <td colspan="10" style="text-align:center;padding:40px;color:var(--gray-500);">
              <i class="bi bi-file-earmark-x" style="font-size:2rem;color:var(--gray-400);display:block;margin-bottom:8px;"></i>
              No records found matching your selected criteria. Try adjusting the filters above.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($reportData instanceof \Illuminate\Pagination\LengthAwarePaginator && $reportData->hasPages())
    <div class="btn-no-print" style="padding:14px 20px;border-top:1px solid var(--border);">
      {{ $reportData->links() }}
    </div>
  @endif
</div>
@endsection

@section('scripts')
<script>
// Automated CSV Export Helper
function exportReportToCSV() {
  const table = document.getElementById('reportTableData');
  if (!table) return;

  let csv = [];
  const rows = table.querySelectorAll('tr');

  for (let i = 0; i < rows.length; i++) {
    let row = [], cols = rows[i].querySelectorAll('td, th');
    for (let j = 0; j < cols.length; j++) {
      // Clean up string: remove multiple newlines and spaces
      let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
      text = text.replace(/"/g, '""');
      row.push('"' + text + '"');
    }
    csv.push(row.join(','));
  }

  const csvContent = 'data:text/csv;charset=utf-8,\uFEFF' + encodeURIComponent(csv.join('\n'));
  const link = document.createElement('a');
  link.setAttribute('href', csvContent);
  link.setAttribute('download', 'EduManage_Report_{{ $reportType }}_' + new Date().toISOString().slice(0, 10) + '.csv');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// Single Chart.js Initialization
document.addEventListener('DOMContentLoaded', function() {
  const canvas = document.getElementById('reportChartCanvas');
  if (!canvas) return;

  const labels = @json($chartLabels);
  const data1 = @json($chartData1);
  const data2 = @json($chartData2);
  const label1 = "{{ $chartLabel1 ?: 'Value' }}";
  const label2 = "{{ $chartLabel2 ?: '' }}";

  const datasets = [
    {
      label: label1,
      data: data1,
      backgroundColor: 'rgba(5, 150, 105, 0.75)',
      borderColor: '#059669',
      borderWidth: 1.5,
      borderRadius: 6
    }
  ];

  if (data2 && data2.length > 0) {
    datasets.push({
      label: label2,
      data: data2,
      backgroundColor: 'rgba(220, 38, 38, 0.75)',
      borderColor: '#dc2626',
      borderWidth: 1.5,
      borderRadius: 6
    });
  }

  new Chart(canvas, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: datasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: datasets.length > 1,
          position: 'top'
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(v) {
              return v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v;
            }
          }
        }
      }
    }
  });
});
</script>
@endsection
