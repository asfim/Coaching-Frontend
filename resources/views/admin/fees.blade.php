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
  <button class="btn btn-primary" onclick="openCollectFeeModal()"><i class="bi bi-cash-coin"></i> Collect Fee / New Invoice</button>
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
        <td>
          <strong>{{ $f->month }} {{ $f->year }}</strong>
          @if($f->fee_type === 'installment')
            <div>
              <span class="badge" style="background:#fef3c7;color:#92400e;font-size:0.7rem;font-weight:600;display:inline-block;margin-top:2px;">
                @if(!empty($f->total_installments))
                  <i class="bi bi-clock-history"></i> Inst. {{ $f->installment_no }}/{{ $f->total_installments }}
                @else
                  <i class="bi bi-infinity"></i> Inst. {{ $f->installment_no }} (Unlimited)
                @endif
              </span>
            </div>
          @elseif($f->fee_type === 'onetime')
            <div>
              <span class="badge" style="background:#ecfdf5;color:#047857;font-size:0.7rem;font-weight:600;display:inline-block;margin-top:2px;">
                <i class="bi bi-wallet2"></i> One-time
              </span>
            </div>
          @endif
          @if($f->due_date)
            <small style="color:var(--gray-500);font-size:0.68rem;display:block;">Due: {{ \Carbon\Carbon::parse($f->due_date)->format('d M Y') }}</small>
          @endif
        </td>
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
            @if($f->due_amount > 0)
              <button type="button" class="btn btn-success btn-icon btn-sm" onclick='openCollectDueModal(@json($f))' title="Collect Payment / বকেয়া গ্রহণ">
                <i class="bi bi-cash"></i>
              </button>
            @endif
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
      <button onclick="closeCollectFeeModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.fees.store') }}" method="POST" onsubmit="return validateCollectFeeForm()">
      @csrf

      <!-- Live Searchable Student Selector Component -->
      <div style="margin-bottom:16px;position:relative;" id="feeStudentSearchContainer">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">
          Select Student * <span style="font-size:0.75rem;color:var(--gray-500);font-weight:400;">(Live Search by name, ID or class)</span>
        </label>

        <!-- Hidden input for form submission -->
        <input type="hidden" name="student_id" id="feeSelectedStudentId" required>

        <!-- Selected Student Card (Shown once student is chosen) -->
        <div id="feeSelectedStudentCard" style="display:none;background:#ecfdf5;border:1px solid #10b981;border-radius:var(--radius);padding:10px 14px;align-items:center;justify-content:space-between;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
          <div style="display:flex;align-items:center;gap:12px;">
            <img id="feeSelectedStudentAvatar" src="{{ asset('images/student_female_profile_1790497748277.jpg') }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #10b981;">
            <div>
              <div id="feeSelectedStudentName" style="font-weight:700;font-size:0.92rem;color:#065f46;"></div>
              <div style="display:flex;gap:6px;align-items:center;margin-top:2px;">
                <span id="feeSelectedStudentCode" class="badge" style="background:#059669;color:#fff;font-size:0.72rem;padding:2px 6px;"></span>
                <span id="feeSelectedStudentClass" style="font-size:0.78rem;color:#047857;font-weight:500;"></span>
              </div>
            </div>
          </div>
          <button type="button" onclick="clearFeeStudentSelection()" class="btn btn-outline btn-sm" style="border-color:#10b981;color:#065f46;padding:4px 10px;font-size:0.78rem;background:#fff;" title="Change Student">
            <i class="bi bi-arrow-repeat"></i> Change
          </button>
        </div>

        <!-- Search Input with Dropdown -->
        <div id="feeStudentSearchBoxWrapper" style="position:relative;">
          <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);pointer-events:none;font-size:0.9rem;"></i>
          <input type="text" id="feeStudentSearchInput" class="form-control" placeholder="Search student by name, student ID, or class..." autocomplete="off" style="padding-left:36px;padding-right:32px;" onfocus="onFeeStudentInputFocus()" oninput="onFeeStudentSearchInput(this.value)">
          <button type="button" id="feeClearSearchBtn" onclick="clearFeeSearchText()" style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;font-size:1.1rem;padding:0;">
            <i class="bi bi-x-circle-fill"></i>
          </button>
        </div>

        <!-- Live Results Dropdown -->
        <div id="feeStudentDropdown" style="display:none;position:absolute;top:100%;left:0;right:0;max-height:220px;overflow-y:auto;background:#fff;border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-xl);z-index:1050;margin-top:4px;">
          <!-- dynamically populated -->
        </div>
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
        <button type="button" class="btn btn-outline" onclick="closeCollectFeeModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Generate Invoice</button>
      </div>
    </form>
  </div>
</div>

<!-- Collect Due Payment Modal -->
<div id="collectDueModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:500px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <h4 style="margin:0;font-weight:700;"><i class="bi bi-cash-stack" style="color:var(--success);"></i> Collect Due Payment (বকেয়া আদায়)</h4>
      <button onclick="document.getElementById('collectDueModal').style.display='none'" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>

    <!-- Student & Invoice Info Card -->
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px;margin-bottom:16px;">
      <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
        <span style="font-size:0.85rem;color:var(--gray-600);">Student:</span>
        <strong id="dueModalStudentName" style="font-size:0.85rem;">-</strong>
      </div>
      <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
        <span style="font-size:0.85rem;color:var(--gray-600);">Invoice No:</span>
        <code id="dueModalInvoiceNo">-</code>
      </div>
      <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
        <span style="font-size:0.85rem;color:var(--gray-600);">Billing Month / Plan:</span>
        <span id="dueModalPlan" style="font-size:0.85rem;font-weight:600;color:#1e40af;">-</span>
      </div>
      <div style="display:flex;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:1px dashed #cbd5e1;">
        <span style="font-size:0.9rem;font-weight:700;color:var(--danger);">Current Due (বকেয়া):</span>
        <strong id="dueModalDueAmount" style="font-size:1.05rem;color:var(--danger);">৳0.00</strong>
      </div>
    </div>

    <form id="collectDueForm" method="POST">
      @csrf
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">
          Collecting Amount (আদায়কৃত টাকা ৳) *
        </label>
        <input type="number" step="0.01" name="paying_amount" id="dueModalPayingAmount" class="form-control" required>
        <small style="color:var(--gray-500);font-size:0.72rem;">Can be full due or partial payment</small>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Payment Method *</label>
          <select name="payment_method" class="form-select" required>
            <option value="Cash">Cash (নগদ)</option>
            <option value="bKash">bKash (বিকাশ)</option>
            <option value="Nagad">Nagad (নগদ)</option>
            <option value="Bank">Bank Deposit (ব্যাংক)</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Payment Date *</label>
          <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Notes / Trx ID</label>
        <input type="text" name="notes" class="form-control" placeholder="e.g. Received by Admin, TrxID: ...">
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('collectDueModal').style.display='none'">Cancel</button>
        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Confirm &amp; Collect</button>
      </div>
    </form>
  </div>
</div>

<script>
const allStudentsForFee = @json($students);
const defaultFeeAvatar = "{{ asset('images/student_female_profile_1790497748277.jpg') }}";

function openCollectFeeModal() {
  document.getElementById('collectFeeModal').style.display = 'flex';
  setTimeout(() => {
    const box = document.getElementById('feeStudentSearchBoxWrapper');
    if (box && box.style.display !== 'none') {
      const input = document.getElementById('feeStudentSearchInput');
      if (input) input.focus();
    }
  }, 100);
}

function closeCollectFeeModal() {
  document.getElementById('collectFeeModal').style.display = 'none';
  closeFeeStudentDropdown();
}

function validateCollectFeeForm() {
  const stId = document.getElementById('feeSelectedStudentId').value;
  if (!stId) {
    alert('Please search and select a student first (অনুগ্রহ করে একজন শিক্ষার্থী নির্বাচন করুন)।');
    const input = document.getElementById('feeStudentSearchInput');
    if (input) input.focus();
    return false;
  }
  return true;
}

function onFeeStudentInputFocus() {
  const input = document.getElementById('feeStudentSearchInput');
  renderFeeStudentDropdown(input ? input.value : '');
}

function onFeeStudentSearchInput(val) {
  const clearBtn = document.getElementById('feeClearSearchBtn');
  if (clearBtn) clearBtn.style.display = val.trim().length > 0 ? 'block' : 'none';
  renderFeeStudentDropdown(val);
}

function clearFeeSearchText() {
  const input = document.getElementById('feeStudentSearchInput');
  if (input) {
    input.value = '';
    input.focus();
  }
  const clearBtn = document.getElementById('feeClearSearchBtn');
  if (clearBtn) clearBtn.style.display = 'none';
  renderFeeStudentDropdown('');
}

function renderFeeStudentDropdown(query) {
  const dropdown = document.getElementById('feeStudentDropdown');
  if (!dropdown) return;
  const q = (query || '').toLowerCase().trim();

  let matches = [];
  if (!q) {
    matches = allStudentsForFee.slice(0, 15);
  } else {
    matches = allStudentsForFee.filter(st => {
      const name = (st.name || '').toLowerCase();
      const code = (st.student_id || '').toLowerCase();
      const cls = (st.class_name || '').toLowerCase();
      const phone = (st.phone || '').toLowerCase();
      return name.includes(q) || code.includes(q) || cls.includes(q) || phone.includes(q);
    }).slice(0, 20);
  }

  if (matches.length === 0) {
    dropdown.innerHTML = `
      <div style="padding:14px;text-align:center;color:var(--gray-500);font-size:0.85rem;">
        <i class="bi bi-person-x" style="font-size:1.4rem;display:block;margin-bottom:4px;color:var(--gray-400);"></i>
        No students found matching "<strong>${escapeFeeHtml(query)}</strong>"
      </div>
    `;
    dropdown.style.display = 'block';
    return;
  }

  let html = `<div style="padding:6px 0;">`;
  matches.forEach(st => {
    const avatar = st.avatar ? '/' + st.avatar.replace(/^\//, '') : defaultFeeAvatar;
    const name = escapeFeeHtml(st.name || 'Unnamed');
    const code = escapeFeeHtml(st.student_id || '');
    const cls = escapeFeeHtml(st.class_name || 'No Class');
    const phone = escapeFeeHtml(st.phone || '');

    html += `
      <div class="student-search-item" onclick='onSelectFeeStudentClicked(${JSON.stringify(st).replace(/'/g, "&#39;")})' style="display:flex;align-items:center;gap:10px;padding:8px 12px;cursor:pointer;transition:background 0.15s;border-bottom:1px solid #f3f4f6;">
        <img src="${avatar}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:1px solid var(--border);" onerror="this.src='${defaultFeeAvatar}'">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <strong style="color:var(--gray-900);font-size:0.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</strong>
            <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:0.7rem;">#${code}</span>
          </div>
          <div style="display:flex;gap:8px;font-size:0.75rem;color:var(--gray-500);margin-top:2px;">
            <span><i class="bi bi-book"></i> ${cls}</span>
            ${phone ? `<span><i class="bi bi-telephone"></i> ${phone}</span>` : ''}
          </div>
        </div>
      </div>
    `;
  });
  html += `</div>`;

  dropdown.innerHTML = html;
  dropdown.style.display = 'block';
}

function onSelectFeeStudentClicked(student) {
  selectFeeStudent(student);
  closeFeeStudentDropdown();
}

function selectFeeStudent(student) {
  document.getElementById('feeSelectedStudentId').value = student.id;
  document.getElementById('feeSelectedStudentName').innerText = student.name;
  document.getElementById('feeSelectedStudentCode').innerText = '#' + (student.student_id || student.id);
  document.getElementById('feeSelectedStudentClass').innerText = student.class_name ? '• ' + student.class_name : '';
  
  const avatar = student.avatar ? '/' + student.avatar.replace(/^\//, '') : defaultFeeAvatar;
  document.getElementById('feeSelectedStudentAvatar').src = avatar;

  document.getElementById('feeSelectedStudentCard').style.display = 'flex';
  document.getElementById('feeStudentSearchBoxWrapper').style.display = 'none';
  document.getElementById('feeStudentDropdown').style.display = 'none';
}

function clearFeeStudentSelection() {
  document.getElementById('feeSelectedStudentId').value = '';
  document.getElementById('feeSelectedStudentCard').style.display = 'none';
  
  const box = document.getElementById('feeStudentSearchBoxWrapper');
  box.style.display = 'block';
  
  const input = document.getElementById('feeStudentSearchInput');
  input.value = '';
  document.getElementById('feeClearSearchBtn').style.display = 'none';
  document.getElementById('feeStudentDropdown').style.display = 'none';
  input.focus();
}

function closeFeeStudentDropdown() {
  const dropdown = document.getElementById('feeStudentDropdown');
  if (dropdown) dropdown.style.display = 'none';
}

function escapeFeeHtml(str) {
  return String(str).replace(/[&<>"']/g, function(s) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[s];
  });
}

document.addEventListener('click', function(e) {
  const container = document.getElementById('feeStudentSearchContainer');
  if (container && !container.contains(e.target)) {
    closeFeeStudentDropdown();
  }
});

function openCollectDueModal(fee) {
  document.getElementById('collectDueModal').style.display = 'flex';
  document.getElementById('collectDueForm').action = '/admin/fees/' + fee.id + '/pay';
  document.getElementById('dueModalStudentName').textContent = (fee.student ? (fee.student.name + ' (#' + fee.student.student_id + ')') : 'Student');
  document.getElementById('dueModalInvoiceNo').textContent = fee.invoice_no;

  let planDesc = fee.month + ' ' + fee.year;
  if (fee.fee_type === 'installment') {
    if (fee.total_installments) {
      planDesc += ' (Installment ' + (fee.installment_no || 1) + '/' + fee.total_installments + ')';
    } else {
      planDesc += ' (Installment ' + (fee.installment_no || 1) + ' - Unlimited / Ongoing)';
    }
  } else if (fee.fee_type === 'onetime') {
    planDesc += ' (One-time)';
  }
  document.getElementById('dueModalPlan').textContent = planDesc;

  const due = parseFloat(fee.due_amount) || 0;
  document.getElementById('dueModalDueAmount').textContent = '৳' + due.toFixed(2);
  const payInput = document.getElementById('dueModalPayingAmount');
  payInput.value = due.toFixed(2);
  payInput.max = due.toFixed(2);
}
</script>

<style>
.student-search-item:hover {
  background: #f0fdf4 !important;
}
.student-search-item:last-child {
  border-bottom: none !important;
}
</style>
@endsection
