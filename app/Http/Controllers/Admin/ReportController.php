<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Attendance;
use App\Models\Result;
use App\Models\Account;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
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

        return view('admin.reports', compact(
            'totalStudents', 'totalFeesCollected', 'totalFeesDue',
            'totalIncome', 'totalExpense', 'netProfit',
            'gpa5Count', 'gpa4Count', 'gpa3Count', 'passRate'
        ));
    }
}
