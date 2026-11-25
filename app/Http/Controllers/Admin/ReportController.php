<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Attendance;
use App\Models\Result;
use App\Models\Account;
use App\Models\AcademicClass;
use App\Models\Batch;
use App\Models\Exam;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->input('report_type', 'fees');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to', now()->toDateString());
        $className = $request->input('class_name');
        $batchId = $request->input('batch_id');
        $programType = $request->input('program_type');

        // Dropdown references
        $classes = AcademicClass::where('status', 'Active')->orderBy('numeric_level')->get();
        $batches = Batch::all();
        $exams = Exam::latest()->get();

        // High-level KPI Counters
        $totalStudents = Student::count();
        $totalFeesCollected = Fee::sum('paid_amount');
        $totalFeesDue = Fee::sum('due_amount');
        $totalIncome = Account::where('type', 'income')->sum('amount');
        $totalExpense = Account::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $gpa5Count = Result::where('gpa', '>=', 5.0)->count();
        $gpa4Count = Result::whereBetween('gpa', [4.0, 4.99])->count();
        $gpa3Count = Result::whereBetween('gpa', [3.0, 3.99])->count();
        $totalExaminees = Result::count() ?: 1;
        $passRate = round((($gpa5Count + $gpa4Count + $gpa3Count) / $totalExaminees) * 100);

        // Chart data initialization
        $chartLabels = [];
        $chartData1 = [];
        $chartData2 = [];
        $chartTitle = '';
        $chartLabel1 = '';
        $chartLabel2 = '';

        // Dynamic query based on active report type
        $reportData = collect();
        $summary = [];

        if ($reportType === 'fees' || $reportType === 'due') {
            $query = Fee::with(['student.academicClass', 'batch'])->latest();

            if ($reportType === 'due') {
                $query->where('due_amount', '>', 0);
            }
            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
            if ($className && $className !== 'All') {
                $query->whereHas('student', fn($q) => $q->where('class_name', $className));
            }
            if ($batchId && $batchId !== 'All') {
                $query->where('batch_id', $batchId);
            }
            if ($programType && $programType !== 'All') {
                $query->whereHas('student', fn($q) => $q->where('program_type', $programType));
            }

            $totalBilled = (clone $query)->sum('amount');
            $totalDisc = (clone $query)->sum('discount');
            $totalColl = (clone $query)->sum('paid_amount');
            $totalDue = (clone $query)->sum('due_amount');
            $payable = max(0, $totalBilled - $totalDisc);
            $collRate = $payable > 0 ? round(($totalColl / $payable) * 100, 1) : 0;

            $reportData = $query->paginate(20)->withQueryString();

            $summary = [
                'total_records' => $reportData->total(),
                'total_billed' => $totalBilled,
                'total_collected' => $totalColl,
                'total_due' => $totalDue,
                'collection_rate' => $collRate,
            ];

            // Chart: Batches collection comparison
            $sampleBatches = $batches->take(6);
            if ($sampleBatches->count() > 0) {
                foreach ($sampleBatches as $b) {
                    $chartLabels[] = $b->batch_code ?: $b->name;
                    $chartData1[] = Fee::where('batch_id', $b->id)->sum('paid_amount');
                    $chartData2[] = Fee::where('batch_id', $b->id)->sum('due_amount');
                }
            } else {
                $chartLabels = ['Month 1', 'Month 2', 'Month 3'];
                $chartData1 = [$totalColl, 0, 0];
                $chartData2 = [$totalDue, 0, 0];
            }
            $chartTitle = 'Fee Collection vs Outstanding Due (৳)';
            $chartLabel1 = 'Collected (আদায়)';
            $chartLabel2 = 'Due (বকেয়া)';

        } elseif ($reportType === 'students') {
            $query = Student::with(['batch', 'academicClass', 'fees'])->latest();

            if ($dateFrom) {
                $query->whereDate('admission_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('admission_date', '<=', $dateTo);
            }
            if ($className && $className !== 'All') {
                $query->where('class_name', $className);
            }
            if ($batchId && $batchId !== 'All') {
                $query->where('batch_id', $batchId);
            }
            if ($programType && $programType !== 'All') {
                $query->where('program_type', $programType);
            }

            $totalEnrolled = (clone $query)->count();
            $academicCount = (clone $query)->where('program_type', 'Academic')->count();
            $targetCount = (clone $query)->where('program_type', 'Target')->count();
            $activeCount = (clone $query)->where('status', 'Active')->count();

            $reportData = $query->paginate(20)->withQueryString();

            $summary = [
                'total_records' => $totalEnrolled,
                'academic_count' => $academicCount,
                'target_count' => $targetCount,
                'active_count' => $activeCount,
            ];

            foreach ($classes->take(6) as $cls) {
                $chartLabels[] = $cls->name;
                $chartData1[] = Student::where('class_name', $cls->name)->count();
                $chartData2[] = Student::where('class_name', $cls->name)->where('status', 'Active')->count();
            }
            $chartTitle = 'Class-wise Enrollment & Active Students';
            $chartLabel1 = 'Total Enrolled';
            $chartLabel2 = 'Active Students';

        } elseif ($reportType === 'results') {
            $query = Result::with(['student', 'exam', 'items'])->latest();

            if ($request->filled('exam_id') && $request->exam_id !== 'All') {
                $query->where('exam_id', $request->exam_id);
            }
            if ($className && $className !== 'All') {
                $query->whereHas('student', fn($q) => $q->where('class_name', $className));
            }
            if ($programType && $programType !== 'All') {
                $query->where('program_type', $programType);
            }

            $totalRes = (clone $query)->count();
            $passCount = (clone $query)->where('gpa', '>=', 1.0)->count();
            $avgGpa = $totalRes > 0 ? round((clone $query)->avg('gpa'), 2) : 0;
            $gpa5 = (clone $query)->where('gpa', '>=', 5.0)->count();

            $reportData = $query->paginate(20)->withQueryString();

            $summary = [
                'total_records' => $totalRes,
                'pass_count' => $passCount,
                'avg_gpa' => $avgGpa,
                'gpa5_count' => $gpa5,
            ];

            $chartLabels = ['GPA 5.0 (A+)', 'GPA 4.0-4.99 (A)', 'GPA 3.5-3.99 (A-)', 'GPA 3.0-3.49 (B)', 'Below 3.0', 'Fail'];
            $chartData1 = [
                Result::where('gpa', '>=', 5.0)->count(),
                Result::whereBetween('gpa', [4.0, 4.99])->count(),
                Result::whereBetween('gpa', [3.5, 3.99])->count(),
                Result::whereBetween('gpa', [3.0, 3.49])->count(),
                Result::whereBetween('gpa', [1.0, 2.99])->count(),
                Result::where('gpa', '<', 1.0)->count(),
            ];
            $chartTitle = 'Examination Grade Distribution';
            $chartLabel1 = 'Examinee Count';

        } elseif ($reportType === 'attendance') {
            $query = Attendance::with(['student.academicClass', 'batch'])->latest('date');

            if ($dateFrom) {
                $query->whereDate('date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('date', '<=', $dateTo);
            }
            if ($batchId && $batchId !== 'All') {
                $query->where('batch_id', $batchId);
            }

            $totalAtt = (clone $query)->count();
            $present = (clone $query)->where('status', 'present')->count();
            $absent = (clone $query)->where('status', 'absent')->count();
            $late = (clone $query)->where('status', 'late')->count();
            $rate = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 1) : 0;

            $reportData = $query->paginate(20)->withQueryString();

            $summary = [
                'total_records' => $totalAtt,
                'present_count' => $present,
                'absent_count' => $absent,
                'late_count' => $late,
                'attendance_rate' => $rate,
            ];

            $chartLabels = ['Present (উপস্থিত)', 'Absent (অনুপস্থিত)', 'Late (দেরি)'];
            $chartData1 = [$present, $absent, $late];
            $chartTitle = 'Attendance Overview Ratio';
            $chartLabel1 = 'Record Count';

        } elseif ($reportType === 'accounts') {
            $query = Account::query()->latest('date');

            if ($dateFrom) {
                $query->whereDate('date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('date', '<=', $dateTo);
            }
            if ($request->filled('transaction_type') && $request->transaction_type !== 'All') {
                $query->where('type', $request->transaction_type);
            }

            $inc = (clone $query)->where('type', 'income')->sum('amount');
            $exp = (clone $query)->where('type', 'expense')->sum('amount');
            $net = $inc - $exp;

            $reportData = $query->paginate(20)->withQueryString();

            $summary = [
                'total_records' => $reportData->total(),
                'total_income' => $inc,
                'total_expense' => $exp,
                'net_profit' => $net,
            ];

            $chartLabels = ['Total Income', 'Total Expense', 'Net Balance'];
            $chartData1 = [$inc, $exp, max(0, $net)];
            $chartTitle = 'Income vs Operating Expense (৳)';
            $chartLabel1 = 'Amount (৳)';
        }

        return view('admin.reports', compact(
            'reportType', 'dateFrom', 'dateTo', 'className', 'batchId', 'programType',
            'classes', 'batches', 'exams',
            'totalStudents', 'totalFeesCollected', 'totalFeesDue',
            'totalIncome', 'totalExpense', 'netProfit',
            'gpa5Count', 'gpa4Count', 'gpa3Count', 'passRate',
            'reportData', 'summary',
            'chartLabels', 'chartData1', 'chartData2', 'chartTitle', 'chartLabel1', 'chartLabel2'
        ));
    }
}
