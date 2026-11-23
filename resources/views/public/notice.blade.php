@extends('layouts.public')

@section('title', 'Notice Board - EduManage Pro')

@section('content')
<section style="background:linear-gradient(135deg,var(--primary-light),var(--secondary-light));padding:48px 0 36px;border-bottom:1px solid var(--border);">
  <div class="container text-center">
    <span class="badge badge-primary" style="margin-bottom:10px;padding:6px 16px;border-radius:50px;">Announcements</span>
    <h1 style="margin-bottom:10px;font-weight:800;">Official Notice Board</h1>
    <p style="font-size:1.05rem;color:var(--gray-600);max-width:600px;margin:0 auto 20px;">
      Stay updated with the latest circulars, exam timetables, fee notifications, and holiday announcements.
    </p>

    <!-- Category Filters -->
    <div style="display:flex;justify-content:center;gap:10px;flex-wrap:wrap;">
      <a href="{{ route('notice') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline' }} btn-sm">All Notices</a>
      <a href="{{ route('notice', ['category' => 'urgent']) }}" class="btn {{ request('category') == 'urgent' ? 'btn-primary' : 'btn-outline' }} btn-sm">Urgent</a>
      <a href="{{ route('notice', ['category' => 'exam']) }}" class="btn {{ request('category') == 'exam' ? 'btn-primary' : 'btn-outline' }} btn-sm">Exams</a>
      <a href="{{ route('notice', ['category' => 'fee']) }}" class="btn {{ request('category') == 'fee' ? 'btn-primary' : 'btn-outline' }} btn-sm">Fee Deadlines</a>
      <a href="{{ route('notice', ['category' => 'holiday']) }}" class="btn {{ request('category') == 'holiday' ? 'btn-primary' : 'btn-outline' }} btn-sm">Holidays</a>
    </div>
  </div>
</section>

<section class="section-padding">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:24px;">
      @forelse($notices as $n)
      <div class="notice-card {{ $n->category }}" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
          <span class="badge {{ $n->category === 'urgent' ? 'badge-danger' : ($n->category === 'exam' ? 'badge-warning' : 'badge-primary') }}" style="text-transform:uppercase;font-size:0.75rem;">
            {{ $n->category }}
          </span>
          <span style="font-size:0.8rem;color:var(--gray-500);"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($n->published_at)->format('d M, Y') }}</span>
        </div>
        <h5 style="margin-bottom:10px;font-weight:700;color:var(--gray-900);">{{ $n->title }}</h5>
        <p style="font-size:0.88rem;color:var(--gray-600);flex:1;line-height:1.6;margin-bottom:16px;">{{ $n->content }}</p>
        <div style="margin-top:auto;font-size:0.78rem;color:var(--gray-400);border-top:1px solid var(--border);padding-top:10px;">
          <i class="bi bi-people"></i> For: {{ ucfirst($n->target_audience) }}
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:50px;">
        <h5>No notices found in this category.</h5>
      </div>
      @endforelse
    </div>

    @if($notices->hasPages())
      <div style="margin-top:36px;display:flex;justify-content:center;">
        {{ $notices->links() }}
      </div>
    @endif
  </div>
</section>
@endsection
