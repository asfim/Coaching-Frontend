@extends('layouts.admin')

@section('title', 'Fees & Invoicing - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Fees Management</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Fees &amp; Collection Management</h2>
    <p>Track student monthly fees, record payments, manage dues, and issue money receipts</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('collectFeeModal').style.display='flex'"><i class="bi bi-cash-coin"></i> Collect Fee / New Invoice</button>
</div>

<!-- Stat Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($totalCollected, 2) }}</h3>
      <p>Total Collected</p>
    </div>
  </div>
  <div class="stat-card red">
    <div class="stat-icon red"><i class="bi bi-exclamation-circle-fill"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($totalDue, 2) }}</h3>
      <p>Total Dues</p>
    </div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-receipt"></i></div>
    <div class="stat-content">
      <h3>{{ $fees->total() }}</h3>
      <p>Total Invoices</p>
    </div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-check2-circle"></i></div>
    <div class="stat-content">
      <h3>{{ \App\Models\Fee::where('status', 'Paid')->count() }}</h3>
      <p>Fully Paid</p>
    </div>
  </div>
</div>

<!-- Fees Table -->
<div class="table-wrapper" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:24px;">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
    <h5 style="margin:0;font-weight:700;">Invoices &amp; Payment Records</h5>
    <span style="font-size:0.85rem;color:var(--gray-500);">Showing {{ $fees->count() }} of {{ $fees->total() }} invoices</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Invoice No</th>
        <th>Student</th>
        <th>Class / Batch</th>
        <th>Month</th>
        <th>Payable</th>
        <th>Paid Amount</th>
        <th>Due Amount</th>
        <th>Method</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($fees as $f)
      <tr>
        <td><code>{{ $f->invoice_no }}</code></td>
        <td>
          <div class="avatar-cell">
            <img src="{{ asset($f->student?->avatar ?: 'images/student_female_profile_1790497748277.jpg') }}" alt="">
            <div>
              <strong>{{ $f->student?->name ?: 'N/A' }}</strong>
              <small style="color:var(--gray-500);">#{{ $f->student?->student_id }}</small>
            </div>
          </div>
        </td>
        <td>
          <div>{{ $f->student?->class_name }}</div>
          <span class="badge badge-primary" style="font-size:0.7rem;">{{ $f->batch?->batch_code ?: '-' }}</span>
        </td>
        <td><strong>{{ $f->month }} {{ $f->year }}</strong></td>
        <td>৳{{ number_format($f->amount - $f->discount, 2) }}</td>
        <td><strong style="color:var(--success);">৳{{ number_format($f->paid_amount, 2) }}</strong></td>
        <td>
          @if($f->due_amount > 0)
            <strong style="color:var(--danger);">৳{{ number_format($f->due_amount, 2) }}</strong>
          @else
            <span style="color:var(--gray-400);">৳0.00</span>
          @endif
        </td>
        <td><span class="badge badge-primary" style="background:#eff6ff;color:#1e40af;">{{ $f->payment_method }}</span></td>
        <td>
          <span class="badge {{ $f->status === 'Paid' ? 'badge-success' : ($f->status === 'Partial' ? 'badge-warning' : 'badge-danger') }}">
            {{ $f->status }}
          </span>
        </td>
        <td>
          <div class="action-btns">
            <a href="{{ route('admin.invoice', $f->id) }}" class="btn btn-primary btn-icon btn-sm" title="Print Invoice">
              <i class="bi bi-printer"></i>
            </a>
            <form action="{{ route('admin.fees.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Delete this invoice record?');" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Delete">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="10" style="text-align:center;padding:30px;color:var(--gray-500);">
          No invoices recorded yet. Click "Collect Fee" to record payment.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if($fees->hasPages())
  <div>{{ $fees->links() }}</div>
@endif

<!-- Collect Fee Modal -->
<div id="collectFeeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:540px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-cash-coin" style="color:var(--primary);"></i> Collect Fee / Record Payment</h4>
      <button onclick="document.getElementById('collectFeeModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.fees.store') }}" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Select Student *</label>
        <select name="student_id" class="form-select" required>
          @foreach($students as $st)
            <option value="{{ $st->id }}">{{ $st->student_id }} - {{ $st->name }} ({{ $st->class_name }})</option>
          @endforeach
        </select>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Billing Month *</label>
          <select name="month" class="form-select" required>
            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
              <option value="{{ $m }}" {{ $m === date('F') ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Year *</label>
          <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Total Fee (৳) *</label>
          <input type="number" step="0.01" name="amount" class="form-control" placeholder="2500" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Discount (৳)</label>
          <input type="number" step="0.01" name="discount" class="form-control" value="0">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Paid Amount (৳) *</label>
          <input type="number" step="0.01" name="paid_amount" class="form-control" placeholder="2500" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Payment Method *</label>
          <select name="payment_method" class="form-select" required>
            <option value="Cash">Cash</option>
            <option value="bKash">bKash</option>
            <option value="Nagad">Nagad</option>
            <option value="Bank">Bank Deposit</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Payment Date *</label>
          <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Notes / Trx ID</label>
          <input type="text" name="notes" class="form-control" placeholder="e.g. TrxID 9X82KD9A">
        </div>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('collectFeeModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Generate Invoice</button>
      </div>
    </form>
  </div>
</div>
@endsection
