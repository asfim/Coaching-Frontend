@extends('layouts.admin')

@section('title', 'Invoice - EduManage Pro')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><a href="{{ route('admin.fees') }}">Fees</a><i class="bi bi-chevron-right"></i><span>Invoice</span>
@endsection

@section('content')
<div class="page-header">
    <div><h2>Payment Invoice</h2></div>
    <div style="display:flex;gap:10px;">
      <button class="btn btn-outline btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
      <button class="btn btn-primary btn-sm"><i class="bi bi-download"></i> Download PDF</button>
    </div>
  </div>

  <div class="invoice-page">
    <div class="invoice-header">
      <div class="invoice-logo">
        <div class="logo-icon" style="margin-bottom:10px;"><i class="bi bi-book-half"></i></div>
        <h4 style="font-size:1.1rem;font-weight:700;">EduManage Pro</h4>
        <p style="font-size:.8rem;color:var(--gray-500);">House 12, Road 5, Dhanmondi<br>Dhaka-1205, Bangladesh<br>+880 1700-123456</p>
      </div>
      <div class="invoice-number">
        <span class="badge badge-success" style="font-size:.75rem;margin-bottom:10px;">PAID</span>
        <h2 style="color:var(--primary);">#INV-2024-001</h2>
        <p>Invoice Date: 01 Sep 2026</p>
        <p>Due Date: 10 Sep 2026</p>
      </div>
    </div>

    <div class="invoice-divider"></div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px;">
      <div>
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-500);margin-bottom:8px;">Billed To</div>
        <div style="display:flex;gap:12px;align-items:center;margin-bottom:10px;"><img src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;"></div>
        <strong style="display:block;font-size:1rem;">Fatima Begum</strong>
        <p style="font-size:.85rem;margin:4px 0;">Student ID: #STD-2401</p>
        <p style="font-size:.85rem;margin:4px 0;">Class X — SSC Batch A</p>
        <p style="font-size:.85rem;margin:4px 0;">Guardian: Karim Hossain</p>
        <p style="font-size:.85rem;margin:4px 0;">Phone: 01712-345678</p>
      </div>
      <div>
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-500);margin-bottom:8px;">Payment Details</div>
        <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:16px;">
          <div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span style="font-size:.85rem;color:var(--gray-600);">Payment Method</span><strong style="font-size:.85rem;">bKash</strong></div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span style="font-size:.85rem;color:var(--gray-600);">Transaction ID</span><strong style="font-size:.85rem;">TXN-BK-98765</strong></div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px;"><span style="font-size:.85rem;color:var(--gray-600);">Payment Date</span><strong style="font-size:.85rem;">01 Sep 2026</strong></div>
          <div style="display:flex;justify-content:space-between;"><span style="font-size:.85rem;color:var(--gray-600);">Received By</span><strong style="font-size:.85rem;">Admin Office</strong></div>
        </div>
      </div>
    </div>

    <table class="data-table invoice-table" style="margin-bottom:24px;">
      <thead><tr><th>#</th><th>Description</th><th>Period</th><th>Amount</th></tr></thead>
      <tbody>
        <tr><td>1</td><td>Monthly Tuition Fee — SSC Science Batch A</td><td>September 2026</td><td>৳2,500.00</td></tr>
        <tr><td>2</td><td>Exam Fee — SSC Model Test</td><td>October 2026</td><td>৳200.00</td></tr>
        <tr><td>3</td><td>Study Material Fee</td><td>One-time</td><td>৳300.00</td></tr>
      </tbody>
      <tfoot>
        <tr style="background:var(--gray-50);"><td colspan="3" style="text-align:right;font-weight:600;">Subtotal</td><td>৳3,000.00</td></tr>
        <tr style="background:var(--gray-50);"><td colspan="3" style="text-align:right;font-weight:600;">Discount</td><td style="color:var(--success);">- ৳0.00</td></tr>
        <tr class="invoice-total-row" style="background:var(--primary-light);"><td colspan="3" style="text-align:right;font-weight:700;color:var(--primary);">Total Paid</td><td style="color:var(--primary);font-weight:800;font-size:1.1rem;">৳3,000.00</td></tr>
      </tfoot>
    </table>

    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> Payment received in full. Thank you!</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:48px;">
      <div class="signature-area"><p style="font-size:.78rem;color:var(--gray-500);">Student Signature</p></div>
      <div class="signature-area"><p style="font-size:.78rem;color:var(--gray-500);">Authorized Signature &amp; Stamp</p></div>
    </div>
  </div>
@endsection

