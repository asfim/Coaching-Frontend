@extends('layouts.public')

@section('title', 'Examination Results - EduManage Pro')

@section('content')
<section style="background:linear-gradient(135deg,var(--primary-light),var(--secondary-light));padding:48px 0 36px;border-bottom:1px solid var(--border);">
  <div class="container text-center">
    <span class="badge badge-primary" style="margin-bottom:10px;padding:6px 16px;border-radius:50px;">Student Performance</span>
    <h1 style="margin-bottom:10px;font-weight:800;">Examination Results Search</h1>
    <p style="font-size:1.05rem;color:var(--gray-600);max-width:600px;margin:0 auto 24px;">
      Search official monthly tests, model tests, and term evaluation results using Student ID or Name.
    </p>

    <!-- Search Form -->
    <div style="max-width:560px;margin:0 auto;">
      <form method="GET" action="{{ route('results') }}" style="display:flex;gap:10px;">
        <input type="text" name="search" class="form-control form-control-lg" placeholder="Enter Student ID (e.g. STD-2401) or Name..." value="{{ request('search') }}" style="border-radius:var(--radius-lg);padding:12px 18px;">
        <button type="submit" class="btn btn-primary btn-lg" style="border-radius:var(--radius-lg);white-space:nowrap;padding:12px 24px;">
          <i class="bi bi-search"></i> Search Result
        </button>
      </form>
      @if(request('search'))
        <div style="margin-top:10px;font-size:0.85rem;color:var(--gray-600);">
          Searching for: "<strong>{{ request('search') }}</strong>" &nbsp;|&nbsp; <a href="{{ route('results') }}" style="color:var(--primary);">Clear Search</a>
        </div>
      @endif
    </div>
  </div>
</section>

<section class="section-padding">
  <div class="container">
    <div style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);max-width:1050px;margin:0 auto;">
      <div style="padding:18px 24px;border-bottom:1px solid var(--border);background:var(--gray-50);display:flex;justify-content:space-between;align-items:center;">
        <h5 style="margin:0;font-weight:700;">Published Results</h5>
        <span style="font-size:0.85rem;color:var(--gray-500);">Showing {{ $results->count() }} records</span>
      </div>
      <div class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th>Student ID</th>
              <th>Student Name</th>
              <th>Batch / Class</th>
              <th>Exam Name</th>
              <th>Subject</th>
              <th>Obtained Marks</th>
              <th>GPA</th>
              <th>Grade</th>
            </tr>
          </thead>
          <tbody>
            @forelse($results as $res)
            <tr>
              <td><strong>#{{ $res->student?->student_id }}</strong></td>
              <td>
                <div style="font-weight:700;color:var(--gray-900);">{{ $res->student?->name ?: 'N/A' }}</div>
              </td>
              <td>
                <div>{{ $res->student?->class_name }}</div>
                <small style="color:var(--gray-500);">{{ $res->student?->batch?->batch_code ?: '-' }}</small>
              </td>
              <td>{{ $res->exam?->title ?: 'General Exam' }}</td>
              <td><strong>{{ $res->exam?->subject ?: '-' }}</strong></td>
              <td>{{ $res->obtained_marks }} / {{ $res->total_marks }}</td>
              <td><strong style="color:var(--primary);font-size:1.05rem;">{{ number_format($res->gpa, 2) }}</strong></td>
              <td>
                <span class="badge {{ $res->grade === 'A+' ? 'badge-success' : 'badge-primary' }}" style="font-size:0.85rem;padding:4px 10px;">
                  {{ $res->grade }}
                </span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" style="text-align:center;padding:40px;color:var(--gray-500);">
                <i class="bi bi-search" style="font-size:2rem;display:block;margin-bottom:8px;color:var(--gray-300);"></i>
                No examination results found. Try entering a different Student ID (e.g. STD-2401) or Name.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($results->hasPages())
        <div style="padding:16px 24px;border-top:1px solid var(--border);">
          {{ $results->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
