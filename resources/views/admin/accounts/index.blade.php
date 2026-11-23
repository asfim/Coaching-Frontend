@extends('layouts.admin')

@section('title', 'Accounts & Finance - EduManage Pro Admin')

@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}">Dashboard</a><i class="bi bi-chevron-right"></i><span>Accounts & Finance</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h2>Accounts &amp; Finance Management</h2>
    <p>Comprehensive tracking of coaching center income, expenses, vouchers, and net balance.</p>
  </div>
  <div style="display:flex;gap:10px;">
    <button class="btn btn-success btn-sm" onclick="openAccountModal('income')"><i class="bi bi-plus-circle"></i> Add Income</button>
    <button class="btn btn-outline btn-sm" style="border-color:var(--danger);color:var(--danger);" onclick="openAccountModal('expense')"><i class="bi bi-dash-circle"></i> Add Expense</button>
  </div>
</div>

<!-- Stat Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;margin-bottom:24px;">
  <div class="stat-card green">
    <div class="stat-icon green"><i class="bi bi-arrow-down-left-circle-fill"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($totalIncome, 2) }}</h3>
      <p>Total Income</p>
    </div>
    <span class="stat-badge up">Lifetime</span>
  </div>

  <div class="stat-card orange">
    <div class="stat-icon orange" style="background:var(--danger-light);color:var(--danger);"><i class="bi bi-arrow-up-right-circle-fill"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($totalExpense, 2) }}</h3>
      <p>Total Expense</p>
    </div>
    <span class="stat-badge down" style="background:var(--danger-light);color:var(--danger);">Outflow</span>
  </div>

  <div class="stat-card blue">
    <div class="stat-icon blue"><i class="bi bi-wallet2"></i></div>
    <div class="stat-content">
      <h3 style="color:{{ $netBalance >= 0 ? 'var(--primary)' : 'var(--danger)' }};">৳{{ number_format($netBalance, 2) }}</h3>
      <p>Net Balance</p>
    </div>
    <span class="stat-badge up">{{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }}</span>
  </div>

  <div class="stat-card purple">
    <div class="stat-icon purple"><i class="bi bi-calendar-check"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($monthlyIncome, 2) }}</h3>
      <p>This Month Income</p>
    </div>
    <span class="stat-badge up">{{ now()->format('M Y') }}</span>
  </div>

  <div class="stat-card yellow">
    <div class="stat-icon yellow"><i class="bi bi-receipt"></i></div>
    <div class="stat-content">
      <h3>৳{{ number_format($monthlyExpense, 2) }}</h3>
      <p>This Month Expense</p>
    </div>
    <span class="stat-badge down" style="background:#fffbeb;color:#b45309;">Monthly</span>
  </div>
</div>

<!-- Filters & Search -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px 20px;margin-bottom:20px;">
  <form method="GET" action="{{ route('admin.accounts.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
    <div style="flex:1;min-width:200px;">
      <input type="text" name="search" class="form-control" placeholder="Search by title or reference..." value="{{ request('search') }}">
    </div>
    <div style="width:160px;">
      <select name="type" class="form-select" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income Only</option>
        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense Only</option>
      </select>
    </div>
    <div style="width:180px;">
      <select name="category" class="form-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <option value="Student Fee" {{ request('category') == 'Student Fee' ? 'selected' : '' }}>Student Fee</option>
        <option value="Admission Fee" {{ request('category') == 'Admission Fee' ? 'selected' : '' }}>Admission Fee</option>
        <option value="Teacher Salary" {{ request('category') == 'Teacher Salary' ? 'selected' : '' }}>Teacher Salary</option>
        <option value="Rent" {{ request('category') == 'Rent' ? 'selected' : '' }}>Office Rent</option>
        <option value="Utility" {{ request('category') == 'Utility' ? 'selected' : '' }}>Electricity & Internet</option>
        <option value="Supplies" {{ request('category') == 'Supplies' ? 'selected' : '' }}>Supplies & Printing</option>
        <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-filter"></i> Filter</button>
    @if(request()->anyFilled(['search', 'type', 'category']))
      <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline btn-sm">Reset</a>
    @endif
  </form>
</div>

<!-- Transactions Table -->
<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
    <h5 style="margin:0;font-weight:700;">Account Transactions</h5>
    <span style="font-size:0.85rem;color:var(--gray-500);">Showing {{ $accounts->count() }} of {{ $accounts->total() }} records</span>
  </div>
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Title &amp; Category</th>
          <th>Type</th>
          <th>Method</th>
          <th>Ref / Voucher</th>
          <th>Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($accounts as $acc)
        <tr>
          <td>
            <strong>{{ \Carbon\Carbon::parse($acc->date)->format('d M, Y') }}</strong>
          </td>
          <td>
            <div style="font-weight:600;color:var(--gray-900);">{{ $acc->title }}</div>
            <span style="font-size:0.75rem;color:var(--gray-500);"><i class="bi bi-tag"></i> {{ $acc->category }}</span>
            @if($acc->description)
              <div style="font-size:0.75rem;color:var(--gray-400);margin-top:2px;">{{ Str::limit($acc->description, 40) }}</div>
            @endif
          </td>
          <td>
            @if($acc->type === 'income')
              <span class="badge badge-success"><i class="bi bi-arrow-down-left"></i> Income</span>
            @else
              <span class="badge badge-danger"><i class="bi bi-arrow-up-right"></i> Expense</span>
            @endif
          </td>
          <td>
            <span class="badge badge-primary" style="background:#eff6ff;color:#1e40af;">{{ $acc->payment_method }}</span>
          </td>
          <td>
            <code>{{ $acc->reference ?: '-' }}</code>
          </td>
          <td>
            <strong style="font-size:0.95rem;color:{{ $acc->type === 'income' ? '#059669' : '#dc2626' }};">
              {{ $acc->type === 'income' ? '+' : '-' }} ৳{{ number_format($acc->amount, 2) }}
            </strong>
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <button class="btn btn-outline btn-sm" style="padding:4px 8px;" onclick='openEditModal(@json($acc))' title="Edit">
                <i class="bi bi-pencil"></i>
              </button>
              <form action="{{ route('admin.accounts.destroy', $acc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm" style="padding:4px 8px;color:var(--danger);border-color:var(--border);" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:var(--gray-500);">
            <i class="bi bi-wallet" style="font-size:2rem;display:block;margin-bottom:8px;color:var(--gray-300);"></i>
            No transactions found. Click "Add Income" or "Add Expense" to record a transaction.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($accounts->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
      {{ $accounts->links() }}
    </div>
  @endif
</div>

<!-- Add Transaction Modal -->
<div id="accountModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:520px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 id="modalTitle" style="margin:0;font-weight:700;">Record Transaction</h4>
      <button onclick="closeAccountModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('admin.accounts.store') }}" method="POST">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Type *</label>
          <select name="type" id="modalType" class="form-select" required onchange="adjustCategories(this.value)">
            <option value="income">Income (+)</option>
            <option value="expense">Expense (-)</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category *</label>
          <select name="category" id="modalCategory" class="form-select" required>
            <option value="Student Fee">Student Fee</option>
            <option value="Admission Fee">Admission Fee</option>
            <option value="Book & Material">Book &amp; Material</option>
            <option value="Other">Other Income</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Title / Particulars *</label>
        <input type="text" name="title" id="modalInputTitle" class="form-control" placeholder="e.g. October Batch A Fee Collection" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Amount (৳) *</label>
          <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Payment Method *</label>
          <select name="payment_method" class="form-select" required>
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
          <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Ref / Voucher No.</label>
          <input type="text" name="reference" class="form-control" placeholder="e.g. VCH-001">
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description / Notes</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="closeAccountModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Transaction</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Transaction Modal -->
<div id="editAccountModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:520px;padding:24px;box-shadow:var(--shadow-xl);position:relative;margin:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
      <h4 style="margin:0;font-weight:700;">Edit Transaction</h4>
      <button onclick="closeEditModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;"><i class="bi bi-x-lg"></i></button>
    </div>
    <form id="editAccountForm" method="POST">
      @csrf
      @method('PUT')
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Type *</label>
          <select name="type" id="editType" class="form-select" required>
            <option value="income">Income (+)</option>
            <option value="expense">Expense (-)</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Category *</label>
          <input type="text" name="category" id="editCategory" class="form-control" required>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Title *</label>
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
          <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Ref / Voucher</label>
          <input type="text" name="reference" id="editRef" class="form-control">
        </div>
      </div>

      <div style="margin-bottom:18px;">
        <label style="display:block;font-size:0.82rem;font-weight:600;margin-bottom:4px;">Description</label>
        <textarea name="description" id="editDesc" class="form-control" rows="2"></textarea>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;">
        <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update</button>
      </div>
    </form>
  </div>
</div>

<script>
function openAccountModal(type) {
  const m = document.getElementById('accountModal');
  m.style.display = 'flex';
  document.getElementById('modalType').value = type;
  adjustCategories(type);
  document.getElementById('modalTitle').innerText = type === 'income' ? 'Record Income (+)' : 'Record Expense (-)';
}

function closeAccountModal() {
  document.getElementById('accountModal').style.display = 'none';
}

function adjustCategories(type) {
  const cat = document.getElementById('modalCategory');
  if (type === 'income') {
    cat.innerHTML = `
      <option value="Student Fee">Student Fee</option>
      <option value="Admission Fee">Admission Fee</option>
      <option value="Book & Material">Book & Material</option>
      <option value="Exam Fee">Exam Fee</option>
      <option value="Other">Other Income</option>
    `;
  } else {
    cat.innerHTML = `
      <option value="Teacher Salary">Teacher Salary</option>
      <option value="Staff Salary">Staff Salary</option>
      <option value="Rent">Office Rent</option>
      <option value="Utility">Electricity & Internet</option>
      <option value="Supplies">Printing & Supplies</option>
      <option value="Marketing">Marketing & Ads</option>
      <option value="Other">Other Expense</option>
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
