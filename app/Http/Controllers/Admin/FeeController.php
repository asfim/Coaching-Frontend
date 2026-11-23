<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Account;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Fee::with(['student', 'batch'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $fees = $query->paginate(15);
        $students = Student::all();
        $batches = Batch::all();

        $totalCollected = Fee::sum('paid_amount');
        $totalDue = Fee::sum('due_amount');

        return view('admin.fees', compact('fees', 'students', 'batches', 'totalCollected', 'totalDue'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required|string|max:50',
            'year' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $validated['batch_id'] = $student->batch_id;

        $discount = $validated['discount'] ?? 0;
        $payable = $validated['amount'] - $discount;
        $paid = $validated['paid_amount'];
        $due = max(0, $payable - $paid);

        $validated['due_amount'] = $due;
        $validated['status'] = $due == 0 ? 'Paid' : ($paid > 0 ? 'Partial' : 'Due');

        $last = Fee::latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;
        $validated['invoice_no'] = 'INV-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $fee = Fee::create($validated);

        // Also record in Accounts as income!
        if ($paid > 0) {
            Account::create([
                'title' => "Fee Collection - {$student->name} ({$validated['month']})",
                'type' => 'income',
                'category' => 'Student Fee',
                'amount' => $paid,
                'payment_method' => $validated['payment_method'],
                'date' => $validated['payment_date'],
                'reference' => $validated['invoice_no'],
                'description' => "Monthly fee collection for {$student->student_id}",
            ]);
        }

        return redirect()->route('admin.fees')->with('success', 'Fee payment recorded successfully! Invoice generated.');
    }

    public function invoice($id = null)
    {
        $fee = $id ? Fee::with(['student.batch', 'batch'])->findOrFail($id)
                   : Fee::with(['student.batch', 'batch'])->latest()->first();

        if (!$fee) {
            return redirect()->route('admin.fees')->with('error', 'No invoice found.');
        }

        return view('admin.invoice', compact('fee'));
    }

    public function destroy($id)
    {
        $fee = Fee::findOrFail($id);
        $fee->delete();

        return redirect()->route('admin.fees')->with('success', 'Fee record deleted successfully!');
    }
}
