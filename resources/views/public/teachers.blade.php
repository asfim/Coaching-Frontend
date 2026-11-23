@extends('layouts.public')

@section('title', 'Faculty & Expert Teachers - EduManage Pro')

@section('content')
<section style="background:linear-gradient(135deg,var(--primary-light),var(--secondary-light));padding:48px 0 36px;border-bottom:1px solid var(--border);">
  <div class="container text-center">
    <span class="badge badge-primary" style="margin-bottom:10px;padding:6px 16px;border-radius:50px;">Faculty Members</span>
    <h1 style="margin-bottom:10px;font-weight:800;">Our Expert Teachers</h1>
    <p style="font-size:1.05rem;color:var(--gray-600);max-width:600px;margin:0 auto;">
      Learn from Bangladesh's top university graduates (BUET, DU, DMC) with years of dedicated coaching excellence.
    </p>
  </div>
</section>

<section class="section-padding">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:28px;">
      @forelse($teachers as $t)
      <div class="teacher-card" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);text-align:center;">
        <div class="teacher-img-wrap" style="height:220px;overflow:hidden;background:var(--gray-100);">
          <img src="{{ asset($t->image ?: 'images/teacher_male_1_1790497096029.jpg') }}" alt="{{ $t->name }}" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <div class="card-body" style="padding:20px;">
          <h5 style="margin-bottom:4px;font-weight:700;">{{ $t->name }}</h5>
          <div class="subject" style="color:var(--primary);font-weight:600;font-size:0.9rem;margin-bottom:4px;">{{ $t->subject }}</div>
          <div class="qual" style="font-size:0.8rem;color:var(--gray-500);margin-bottom:12px;">{{ $t->qualification }}</div>
          <div class="teacher-social" style="display:flex;justify-content:center;gap:12px;">
            <a href="{{ $t->facebook ?: '#' }}" style="color:var(--gray-600);font-size:1.1rem;"><i class="bi bi-facebook"></i></a>
            <a href="{{ $t->linkedin ?: '#' }}" style="color:var(--gray-600);font-size:1.1rem;"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:40px;">
        <p style="color:var(--gray-500);">No faculty members listed at this time.</p>
      </div>
      @endforelse
    </div>

    @if($teachers->hasPages())
      <div style="margin-top:36px;display:flex;justify-content:center;">
        {{ $teachers->links() }}
      </div>
    @endif
  </div>
</section>
@endsection
