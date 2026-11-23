@extends('layouts.teacher')

@section('title', 'Take Attendance - Teacher Portal')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
  <div>
    <h3 style="font-weight:800;margin-bottom:4px;">Batch Attendance Sheet</h3>
    <p style="color:var(--gray-500);margin:0;font-size:.9rem;">Mark daily attendance for enrolled students</p>
  </div>
  <form method="GET" action="{{ route('teacher.attendance') }}" style="display:flex;gap:10px;align-items:center;">
    <select name="batch_id" class="form-control" onchange="this.form.submit()" style="padding:8px 12px;border-radius:var(--radius);border:1px solid var(--border);">
      @foreach($batches as $b)
        <option value="{{ $b->id }}" {{ $selectedBatch == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
      @endforeach
    </select>
    <input type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()" style="padding:8px 12px;border-radius:var(--radius);border:1px solid var(--border);">
  </form>
</div>

<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;">
  <form action="{{ route('admin.attendance.mark') }}" method="POST">
    @csrf
    <input type="hidden" name="batch_id" value="{{ $selectedBatch }}">
    <input type="hidden" name="date" value="{{ $date }}">

    <div class="table-responsive">
      <table class="table" style="width:100%;font-size:.88rem;">
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th style="text-align:center;">Attendance Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $st)
          @php $att = $attendances[$st->id] ?? null; @endphp
          <tr>
            <td><strong>#{{ $st->student_id }}</strong></td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <img src="{{ !empty($st->avatar) ? asset($st->avatar) : asset('images/student_female_profile_1790497748277.jpg') }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                <strong>{{ $st->name }}</strong>
              </div>
            </td>
            <td>{{ $st->phone }}</td>
            <td>
              <div style="display:flex;justify-content:center;gap:16px;">
                <label style="display:flex;align-items:center;gap:4px;cursor:pointer;color:var(--success);font-weight:600;">
                  <input type="radio" name="attendance[{{ $st->id }}]" value="present" {{ ($att?->status == 'present' || !$att) ? 'checked' : '' }}> Present
                </label>
                <label style="display:flex;align-items:center;gap:4px;cursor:pointer;color:var(--danger);font-weight:600;">
                  <input type="radio" name="attendance[{{ $st->id }}]" value="absent" {{ $att?->status == 'absent' ? 'checked' : '' }}> Absent
                </label>
                <label style="display:flex;align-items:center;gap:4px;cursor:pointer;color:var(--warning);font-weight:600;">
                  <input type="radio" name="attendance[{{ $st->id }}]" value="late" {{ $att?->status == 'late' ? 'checked' : '' }}> Late
                </label>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align:center;padding:24px;color:var(--gray-500);">No students enrolled in this batch.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($students->count() > 0)
    <div style="display:flex;justify-content:flex-end;margin-top:20px;">
      <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save"></i> Save Attendance Sheet</button>
    </div>
    @endif
  </form>
</div>
@endsection
