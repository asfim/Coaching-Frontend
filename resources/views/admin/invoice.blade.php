@extends('layouts.admin')

@section('title', 'Invoice ' . ($fee->invoice_no ?? '') . ' - EduManage Pro')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><a href="{{ route('admin.fees') }}">Fees</a><i class="bi bi-chevron-right"></i><span>Invoice</span>
@endsection

@section('content')
<div style="max-width:800px;margin:0 auto;background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow-md);" id="printableInvoice">
  <!-- Invoice Header -->
  <div style="display:flex;justify-content:space-between;align-items:flex-start;padding-bottom:24px;border-bottom:2px solid var(--gray-200);margin-bottom:24px;">
    <div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;">
          <i class="bi bi-book-half"></i>
        </div>
        <h3 style="margin:0;font-family:Outfit,sans-serif;font-weight:800;">EduManage <span style="color:var(--primary);">Pro</span></h3>
      </div>
      <p style="margin:0;font-size:0.82rem;color:var(--gray-500);line-height:1.4;">
        Bangladesh's Smart Coaching Management Platform<br>
        House 12, Road 5, Dhanmondi, Dhaka-1205<br>
        Phone: +880 1700-123456 | info@edumanagepro.com.bd
      </p>
    </div>
    <div style="text-align:right;">
      <span class="badge {{ $fee->status === 'Paid' ? 'badge-success' : ($fee->status === 'Partial' ? 'badge-warning' : 'badge-danger') }}" style="font-size:0.95rem;padding:6px 16px;text-transform:uppercase;">
        {{ $fee->status }}
      </span>
      <h4 style="margin:10px 0 4px;font-weight:800;color:var(--gray-800);">{{ $fee->invoice_no }}</h4>
      <div style="font-size:0.82rem;color:var(--gray-500);">Date: {{ \Carbon\Carbon::parse($fee->payment_date ?: $fee->created_at)->format('d F, Y') }}</div>
    </div>
  </div>

  <!-- Student Info & Billed To -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px;">
    <div>
      <h6 style="text-transform:uppercase;font-size:0.75rem;letter-spacing:0.05em;color:var(--gray-400);margin-bottom:8px;">Billed To (Student)</h6>
      <h5 style="margin:0 0 4px;font-weight:700;">{{ $fee->student?->name ?: 'N/A' }}</h5>
      <div style="font-size:0.85rem;color:var(--gray-600);line-height:1.5;">
        <strong>ID:</strong> #{{ $fee->student?->student_id }}<br>
        <strong>Class:</strong> {{ $fee->student?->class_name }} &nbsp;|&nbsp; <strong>Batch:</strong> {{ $fee->batch?->name ?: '-' }}<br>
        <strong>Phone:</strong> {{ $fee->student?->phone }}<br>
        <strong>Guardian:</strong> {{ $fee->student?->guardian_name ?: '-' }} ({{ $fee->student?->guardian_phone ?: '-' }})
      </div>
    </div>
    <div style="text-align:right;">
      <h6 style="text-transform:uppercase;font-size:0.75rem;letter-spacing:0.05em;color:var(--gray-400);margin-bottom:8px;">Payment Details</h6>
      <div style="font-size:0.85rem;color:var(--gray-600);line-height:1.6;">
        <strong>Billing Month:</strong> {{ $fee->month }} {{ $fee->year }}<br>
        <strong>Payment Method:</strong> {{ $fee->payment_method }}<br>
        <strong>Transaction Note:</strong> {{ $fee->notes ?: 'N/A' }}
      </div>
    </div>
  </div>

  <!-- Invoice Table -->
  <table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
    <thead>
      <tr style="background:var(--gray-100);border-bottom:2px solid var(--border);">
        <th style="padding:12px;text-align:left;font-size:0.82rem;font-weight:700;color:var(--gray-600);">Item Description</th>
        <th style="padding:12px;text-align:right;font-size:0.82rem;font-weight:700;color:var(--gray-600);">Rate (৳)</th>
        <th style="padding:12px;text-align:right;font-size:0.82rem;font-weight:700;color:var(--gray-600);">Discount</th>
        <th style="padding:12px;text-align:right;font-size:0.82rem;font-weight:700;color:var(--gray-600);">Total (৳)</th>
      </tr>
    </thead>
    <tbody>
      <tr style="border-bottom:1px solid var(--border);">
        <td style="padding:14px 12px;">
          <strong>Monthly Coaching Tuition Fee — {{ $fee->month }} {{ $fee->year }}</strong>
          <div style="font-size:0.75rem;color:var(--gray-500);">Comprehensive academic coaching &amp; exam evaluation</div>
        </td>
        <td style="padding:14px 12px;text-align:right;">৳{{ number_format($fee->amount, 2) }}</td>
        <td style="padding:14px 12px;text-align:right;color:var(--danger);">-৳{{ number_format($fee->discount, 2) }}</td>
        <td style="padding:14px 12px;text-align:right;font-weight:700;">৳{{ number_format($fee->amount - $fee->discount, 2) }}</td>
      </tr>
    </tbody>
  </table>

  <!-- Calculation Summary -->
  <div style="display:flex;justify-content:flex-end;margin-bottom:32px;">
    <div style="width:280px;display:flex;flex-direction:column;gap:8px;font-size:0.88rem;">
      <div style="display:flex;justify-content:space-between;">
        <span style="color:var(--gray-600);">Subtotal:</span>
        <span>৳{{ number_format($fee->amount, 2) }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;color:var(--danger);">
        <span>Discount:</span>
        <span>-৳{{ number_format($fee->discount, 2) }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-weight:700;border-top:1px solid var(--border);padding-top:6px;">
        <span>Total Payable:</span>
        <span>৳{{ number_format($fee->amount - $fee->discount, 2) }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;color:var(--success);font-weight:700;">
        <span>Paid Amount:</span>
        <span>৳{{ number_format($fee->paid_amount, 2) }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;border-top:2px solid var(--gray-800);padding-top:8px;font-size:1.05rem;font-weight:800;color:{{ $fee->due_amount > 0 ? 'var(--danger)' : 'var(--primary)' }};">
        <span>Balance Due:</span>
        <span>৳{{ number_format($fee->due_amount, 2) }}</span>
      </div>
    </div>
  </div>

  <!-- Signatures -->
  <div style="display:flex;justify-content:space-between;padding-top:40px;margin-top:20px;border-top:1px dashed var(--gray-300);">
    <div style="text-align:center;">
      <div style="width:180px;border-top:1px solid var(--gray-400);margin-bottom:4px;"></div>
      <span style="font-size:0.8rem;color:var(--gray-500);">Student / Guardian Signature</span>
    </div>
    <div style="text-align:center;">
      <div style="width:180px;border-top:1px solid var(--gray-400);margin-bottom:4px;"></div>
      <span style="font-size:0.8rem;color:var(--gray-500);">Authorized Official Seal</span>
    </div>
  </div>
</div>

<!-- Print Buttons -->
<div style="max-width:800px;margin:20px auto 0;display:flex;justify-content:space-between;align-items:center;">
  <a href="{{ route('admin.fees') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> Back to Fees List</a>
  <button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print Money Receipt</button>
</div>

<style>
@media print {
  body * { visibility: hidden; }
  #printableInvoice, #printableInvoice * { visibility: visible; }
  #printableInvoice { position: absolute; left: 0; top: 0; width: 100%; border: none; box-shadow: none; padding: 0; }
  .sidebar, .topbar, .breadcrumb-bar { display: none !important; }
}
</style>
@endsection
