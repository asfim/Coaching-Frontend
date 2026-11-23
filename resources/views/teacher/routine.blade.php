@extends('layouts.teacher')

@section('title', 'Class Routine - Teacher Portal')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
  <div>
    <h3 style="font-weight:800;margin-bottom:4px;">Weekly Class Schedule</h3>
    <p style="color:var(--gray-500);margin:0;font-size:.9rem;">Classes and slots assigned to {{ $teacher?->name ?? 'you' }}</p>
  </div>
  <a href="{{ route('teacher.attendance') }}" class="btn btn-success"><i class="bi bi-check2-circle"></i> Go to Attendance</a>
</div>

<div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
  <div class="table-responsive">
    <table class="table" style="width:100%;font-size:.88rem;">
      <thead>
        <tr>
          <th>Day</th>
          <th>Time Slot</th>
          <th>Subject</th>
          <th>Batch</th>
          <th>Room</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($routines as $r)
        <tr>
          <td><span class="badge badge-primary">{{ $r->day }}</span></td>
          <td><strong>{{ $r->start_time }} - {{ $r->end_time }}</strong></td>
          <td><strong>{{ $r->subject }}</strong></td>
          <td>{{ $r->batch?->name ?? 'All' }}</td>
          <td>{{ $r->room ?? 'Main Hall' }}</td>
          <td>
            <a href="{{ route('teacher.attendance', ['batch_id' => $r->batch_id]) }}" class="btn btn-outline btn-sm">
              <i class="bi bi-calendar2-check"></i> Take Attendance
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;padding:24px;color:var(--gray-500);">No classes scheduled yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
