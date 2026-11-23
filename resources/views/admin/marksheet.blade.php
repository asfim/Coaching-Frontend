<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marksheet - {{ $result->student?->name ?: 'Student' }} ({{ $result->student?->student_id }})</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
<link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
<style>
  body {
    background: #f3f4f6;
    color: #1f2937;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    margin: 0;
    padding: 24px 12px;
  }
  .marksheet-page {
    max-width: 860px;
    margin: 0 auto;
    background: #fff;
    padding: 40px 48px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    position: relative;
    border: 1px solid #e5e7eb;
  }
  .watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 5rem;
    font-weight: 900;
    color: rgba(26, 86, 219, 0.03);
    text-transform: uppercase;
    letter-spacing: 10px;
    pointer-events: none;
    user-select: none;
    z-index: 0;
  }
  .content-layer {
    position: relative;
    z-index: 1;
  }
  .meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px 24px;
    font-size: 0.9rem;
  }
  .meta-row {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
    border-bottom: 1px dashed #e5e7eb;
  }
  .meta-label {
    color: #6b7280;
    font-weight: 500;
  }
  .meta-value {
    color: #111827;
    font-weight: 700;
  }
  .marks-table {
    width: 100%;
    border-collapse: collapse;
    margin: 24px 0 16px;
    font-size: 0.88rem;
  }
  .marks-table th {
    background: #1e3a8a;
    color: #fff;
    padding: 10px 12px;
    font-weight: 600;
    text-align: left;
    border: 1px solid #1e3a8a;
  }
  .marks-table td {
    padding: 9px 12px;
    border: 1px solid #e5e7eb;
  }
  .marks-table tr:nth-child(even) {
    background: #f9fafb;
  }
  .summary-box {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 16px;
    margin: 20px 0;
    text-align: center;
  }
  .summary-item h6 {
    margin: 0 0 4px 0;
    font-size: 0.78rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .summary-item .value {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
  }
  .grading-scale-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.72rem;
    color: #475569;
  }
  .grading-scale-table td, .grading-scale-table th {
    padding: 3px 6px;
    border: 1px solid #e2e8f0;
    text-align: center;
  }
  .signature-area {
    display: flex;
    justify-content: space-between;
    margin-top: 60px;
    padding-top: 20px;
  }
  .sig-block {
    text-align: center;
    width: 180px;
    border-top: 1px solid #475569;
    padding-top: 6px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
  }
  .actions-bar {
    max-width: 860px;
    margin: 0 auto 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  @media print {
    body {
      background: #fff;
      padding: 0;
    }
    .no-print {
      display: none !important;
    }
    .marksheet-page {
      box-shadow: none;
      border: none;
      padding: 16px;
      max-width: 100%;
    }
  }
</style>
</head>
<body>

<div class="actions-bar no-print">
  <a href="{{ route('admin.results') }}" class="btn btn-outline" style="padding:8px 16px;font-size:0.9rem;">
    <i class="bi bi-arrow-left"></i> Back to Results
  </a>
  <div style="display:flex;gap:10px;">
    <button onclick="window.print()" class="btn btn-primary" style="padding:8px 20px;font-size:0.9rem;">
      <i class="bi bi-printer-fill"></i> Print / Download Marksheet (PDF)
    </button>
  </div>
</div>

<div class="marksheet-page">
  <div class="watermark">EduManage Pro</div>
  
  <div class="content-layer">
    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;border-bottom:2px solid #1e3a8a;padding-bottom:18px;margin-bottom:20px;">
      <div style="display:flex;align-items:center;gap:16px;">
        <div style="width:56px;height:56px;background:#1e3a8a;color:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:2rem;">
          <i class="bi bi-book-half"></i>
        </div>
        <div>
          <h2 style="margin:0 0 2px 0;font-weight:900;color:#1e3a8a;font-size:1.6rem;letter-spacing:0.5px;">EDUMANAGE PRO ACADEMIC CARE</h2>
          <div style="font-size:0.8rem;color:#4b5563;">Authorized Academic Coaching & Model Examination Center</div>
          <div style="font-size:0.75rem;color:#6b7280;margin-top:2px;">Dhanmondi, Dhaka-1205 | Hotline: +880 1711-000000 | Email: exam@edumanage.com</div>
        </div>
      </div>
      <div style="text-align:right;">
        <span style="display:inline-block;border:2px solid #1e3a8a;padding:6px 14px;border-radius:6px;font-weight:800;color:#1e3a8a;font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;background:#eff6ff;">
          Official Transcript
        </span>
      </div>
    </div>

    <div style="text-align:center;margin-bottom:22px;">
      <h3 style="margin:0;font-size:1.25rem;font-weight:800;color:#111827;text-transform:uppercase;letter-spacing:0.5px;">
        Student Examination Marksheet
      </h3>
      <p style="margin:4px 0 0 0;font-size:0.85rem;color:#6b7280;">
        {{ $result->exam?->title ?: 'Official Examination' }}
      </p>
    </div>

    <!-- Student & Exam Details Grid -->
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin-bottom:22px;display:grid;grid-template-columns:100px 1fr;gap:20px;align-items:center;">
      <img src="{{ asset($result->student?->avatar ?: 'images/student_female_profile_1790497748277.jpg') }}" alt="Student" style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #cbd5e1;">
      
      <div class="meta-grid">
        <div class="meta-row">
          <span class="meta-label">Student Name:</span>
          <span class="meta-value">{{ $result->student?->name ?: 'N/A' }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Student ID:</span>
          <span class="meta-value">#{{ $result->student?->student_id }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Class / Program:</span>
          <span class="meta-value">{{ $result->student?->class_name ?: ($result->student?->academicClass?->name ?: 'N/A') }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Batch:</span>
          <span class="meta-value">{{ $result->exam?->batch?->name ?: ($result->student?->batch?->name ?: 'All Batches') }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Exam Date:</span>
          <span class="meta-value">{{ $result->exam?->date ? \Carbon\Carbon::parse($result->exam->date)->format('d F, Y') : date('d F, Y') }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Published Date:</span>
          <span class="meta-value">{{ $result->updated_at ? $result->updated_at->format('d M, Y') : date('d M, Y') }}</span>
        </div>
      </div>
    </div>

    <!-- Subject-wise Marks Table -->
    <table class="marks-table">
      <thead>
        <tr>
          <th style="width:40px;text-align:center;">#</th>
          <th>Subject Name</th>
          <th style="text-align:center;">Subject Code</th>
          <th style="text-align:center;">Full Marks</th>
          <th style="text-align:center;">Obtained Marks</th>
          <th style="text-align:center;">Percentage</th>
          <th style="text-align:center;">Letter Grade</th>
          <th style="text-align:center;">Grade Point</th>
        </tr>
      </thead>
      <tbody>
        @php
          $items = $result->items;
          $sl = 1;
        @endphp

        @forelse($items as $item)
        @php
          $pct = $item->total_marks > 0 ? round(($item->obtained_marks / $item->total_marks) * 100, 1) : 0;
        @endphp
        <tr>
          <td style="text-align:center;">{{ $sl++ }}</td>
          <td><strong>{{ $item->subject_name }}</strong></td>
          <td style="text-align:center;"><code>{{ $item->subject?->code ?: '-' }}</code></td>
          <td style="text-align:center;">{{ number_format($item->total_marks, 0) }}</td>
          <td style="text-align:center;font-weight:700;color:#1e3a8a;">{{ number_format($item->obtained_marks, 1) }}</td>
          <td style="text-align:center;">{{ $pct }}%</td>
          <td style="text-align:center;">
            <span style="font-weight:800;color:{{ $item->grade === 'F' ? '#dc2626' : '#059669' }};">
              {{ $item->grade }}
            </span>
          </td>
          <td style="text-align:center;font-weight:700;">{{ number_format($item->gpa, 2) }}</td>
        </tr>
        @empty
        <tr>
          <td style="text-align:center;">1</td>
          <td><strong>{{ $result->exam?->subject ?: 'General Subject' }}</strong></td>
          <td style="text-align:center;"><code>{{ $result->exam?->subjectModel?->code ?: '-' }}</code></td>
          <td style="text-align:center;">{{ number_format($result->total_marks, 0) }}</td>
          <td style="text-align:center;font-weight:700;color:#1e3a8a;">{{ number_format($result->obtained_marks, 1) }}</td>
          <td style="text-align:center;">
            {{ $result->total_marks > 0 ? round(($result->obtained_marks / $result->total_marks) * 100, 1) : 0 }}%
          </td>
          <td style="text-align:center;">
            <span style="font-weight:800;color:{{ $result->grade === 'F' ? '#dc2626' : '#059669' }};">
              {{ $result->grade }}
            </span>
          </td>
          <td style="text-align:center;font-weight:700;">{{ number_format($result->gpa, 2) }}</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Performance Summary Cards -->
    @php
      $overallPct = $result->total_marks > 0 ? round(($result->obtained_marks / $result->total_marks) * 100, 2) : 0;
      $passed = ($result->grade !== 'F' && $result->gpa > 0);
    @endphp
    <div class="summary-box">
      <div class="summary-item">
        <h6>Total Obtained</h6>
        <div class="value" style="color:#1e3a8a;">{{ number_format($result->obtained_marks, 1) }} <span style="font-size:0.85rem;color:#64748b;font-weight:400;">/ {{ number_format($result->total_marks, 0) }}</span></div>
      </div>
      <div class="summary-item">
        <h6>Percentage</h6>
        <div class="value">{{ $overallPct }}%</div>
      </div>
      <div class="summary-item">
        <h6>Average GPA</h6>
        <div class="value" style="color:{{ $passed ? '#059669' : '#dc2626' }};">{{ number_format($result->gpa, 2) }} <span style="font-size:0.8rem;color:#64748b;font-weight:400;">(5.00)</span></div>
      </div>
      <div class="summary-item">
        <h6>Final Grade</h6>
        <div class="value" style="color:{{ $passed ? '#059669' : '#dc2626' }};">
          {{ $result->grade }} 
          <span style="font-size:0.75rem;padding:2px 8px;border-radius:4px;vertical-align:middle;background:{{ $passed ? '#dcfce7' : '#fee2e2' }};color:{{ $passed ? '#15803d' : '#991b1b' }};">
            {{ $passed ? 'PASSED' : 'FAILED' }}
          </span>
        </div>
      </div>
    </div>

    @if($result->remarks)
    <div style="background:#f1f5f9;border-left:4px solid #1e3a8a;padding:8px 14px;border-radius:4px;font-size:0.82rem;margin-bottom:20px;color:#334155;">
      <strong>Academic Remarks:</strong> {{ $result->remarks }}
    </div>
    @endif

    <!-- Grading Scale Table Reference -->
    <div style="margin-top:20px;">
      <div style="font-size:0.75rem;font-weight:700;color:#64748b;margin-bottom:4px;text-transform:uppercase;">Grading Scale Reference</div>
      <table class="grading-scale-table">
        <tr style="background:#f1f5f9;font-weight:600;">
          <td>Marks Range</td>
          <td>80% - 100%</td>
          <td>70% - 79%</td>
          <td>60% - 69%</td>
          <td>50% - 59%</td>
          <td>40% - 49%</td>
          <td>33% - 39%</td>
          <td>0% - 32%</td>
        </tr>
        <tr>
          <td style="font-weight:600;">Letter Grade</td>
          <td><strong style="color:#059669;">A+</strong></td>
          <td>A</td>
          <td>A-</td>
          <td>B</td>
          <td>C</td>
          <td>D</td>
          <td><strong style="color:#dc2626;">F</strong></td>
        </tr>
        <tr>
          <td style="font-weight:600;">Grade Point</td>
          <td>5.00</td>
          <td>4.00</td>
          <td>3.50</td>
          <td>3.00</td>
          <td>2.00</td>
          <td>1.00</td>
          <td>0.00</td>
        </tr>
      </table>
    </div>

    <!-- Signatures -->
    <div class="signature-area">
      <div class="sig-block">
        Prepared &amp; Verified By
      </div>
      <div class="sig-block">
        Exam Controller
      </div>
      <div class="sig-block">
        Principal / Director
      </div>
    </div>

    <div style="margin-top:30px;text-align:center;font-size:0.72rem;color:#94a3b8;border-top:1px dashed #cbd5e1;padding-top:10px;">
      This marksheet is computer-generated by EduManage Pro Academic System. Valid without manual signature when verified digitally.
    </div>
  </div>
</div>

</body>
</html>
