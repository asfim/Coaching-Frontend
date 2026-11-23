@extends('layouts.public')

@section('title', 'All Courses - EduManage Pro')

@section('content')
<section style="background:linear-gradient(135deg,var(--primary-light),var(--secondary-light));padding:48px 0 36px;border-bottom:1px solid var(--border);">
  <div class="container text-center">
    <span class="badge badge-primary" style="margin-bottom:10px;padding:6px 16px;border-radius:50px;">Academic Programs</span>
    <h1 style="margin-bottom:10px;font-weight:800;">Our Coaching Courses</h1>
    <p style="font-size:1.05rem;color:var(--gray-600);max-width:600px;margin:0 auto 20px;">
      Comprehensive board curriculum, admission prep, and foundation batches taught by expert educators.
    </p>
    
    <!-- Filter categories -->
    <div style="display:flex;justify-content:center;gap:10px;flex-wrap:wrap;">
      <a href="{{ route('courses') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline' }} btn-sm">All Courses</a>
      @foreach($categories as $cat)
        <a href="{{ route('courses', ['category' => $cat->name]) }}" class="btn {{ request('category') == $cat->name ? 'btn-primary' : 'btn-outline' }} btn-sm">{{ $cat->name }}</a>
      @endforeach
    </div>
  </div>
</section>

<section class="section-padding">
  <div class="container">
    <div class="grid-3" style="gap:28px;">
      @forelse($courses as $c)
      <div class="course-card" style="border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);background:#fff;display:flex;flex-direction:column;">
        <div class="course-img" style="position:relative;height:200px;overflow:hidden;">
          <img src="{{ asset($c->image ?: 'images/hero_classroom_1790497065510.jpg') }}" alt="{{ $c->title }}" style="width:100%;height:100%;object-fit:cover;">
          <span class="badge badge-primary" style="position:absolute;top:14px;left:14px;">{{ $c->category }}</span>
        </div>
        <div class="course-body" style="padding:22px;flex:1;display:flex;flex-direction:column;">
          <div class="course-meta" style="display:flex;gap:16px;margin-bottom:10px;font-size:0.82rem;color:var(--gray-500);">
            <span><i class="bi bi-clock"></i> {{ $c->duration }}</span>
            <span><i class="bi bi-people"></i> {{ $c->seats }} Seats</span>
          </div>
          <h5 style="margin-bottom:10px;font-weight:700;font-size:1.1rem;">{{ $c->title }}</h5>
          <p style="font-size:0.85rem;color:var(--gray-500);flex:1;line-height:1.5;">{{ Str::limit($c->description, 95) }}</p>
        </div>
        <div class="course-footer" style="padding:16px 22px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:var(--gray-50);">
          <div class="course-price" style="font-size:1.25rem;font-weight:800;color:var(--primary);">
            ৳ {{ number_format($c->fee, 0) }}<span style="font-size:0.75rem;font-weight:400;color:var(--gray-500);">/mo</span>
          </div>
          <a href="{{ route('course.detail', $c->slug ?: $c->id) }}" class="btn btn-primary btn-sm">View Details &rarr;</a>
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:50px;">
        <h5>No courses found in this category.</h5>
      </div>
      @endforelse
    </div>

    @if($courses->hasPages())
      <div style="margin-top:36px;display:flex;justify-content:center;">
        {{ $courses->links() }}
      </div>
    @endif
  </div>
</section>
@endsection
