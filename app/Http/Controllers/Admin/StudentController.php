<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use App\Models\AcademicClass;
use App\Models\Fee;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['batch', 'course', 'fees'])->latest();

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('class_name')) {
            $query->where('class_name', $request->class_name);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('student_id', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $students = $query->paginate(12)->withQueryString();
        $batches = Batch::all();
        $courses = Course::all();
        $classes = AcademicClass::where('status', 'Active')->orderBy('numeric_level')->get();

        $totalStudents = Student::count();
        $academicStudentsCount = Student::where('program_type', 'Academic')->count();
        $targetStudentsCount = Student::where('program_type', 'Target')->count();

        return view('admin.students', compact(
            'students', 'batches', 'courses', 'classes',
            'totalStudents', 'academicStudentsCount', 'targetStudentsCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:4|max:100',
            'phone' => 'required|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'program_type' => 'nullable|in:Academic,Target',
            'class_name' => 'required|string|max:100',
            'course_id' => 'nullable',
            'batch_id' => 'nullable',
            'admission_date' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Completed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',

            // Fee Setup attributes
            'setup_fees' => 'nullable',
            'fee_type' => 'nullable|in:onetime,installment',
            'installment_mode' => 'nullable|in:fixed,unlimited',
            'fee_amount' => 'nullable|numeric|min:0',
            'fee_discount' => 'nullable|numeric|min:0',
            'fee_paid_amount' => 'nullable|numeric|min:0',
            'fee_payment_method' => 'nullable|string|max:50',
            'fee_payment_date' => 'nullable|date',
            'fee_notes' => 'nullable|string|max:255',
            'total_installments' => 'nullable|integer|min:1|max:999',
            'installment_interval' => 'nullable|in:monthly,bi_monthly',
        ]);

        // Clean nullable relationships
        $validated['course_id'] = $request->filled('course_id') ? $request->course_id : null;
        $validated['batch_id'] = $request->filled('batch_id') ? $request->batch_id : null;
        $validated['admission_date'] = $request->filled('admission_date') ? $request->admission_date : now()->toDateString();

        // Associate with AcademicClass and deduce program_type
        $classObj = AcademicClass::where('name', $validated['class_name'])->first();
        if ($classObj) {
            $validated['class_id'] = $classObj->id;
            if (empty($validated['program_type'])) {
                $validated['program_type'] = $classObj->class_type ?? 'Academic';
            }
        }

        if (empty($validated['program_type'])) {
            $batchObj = $validated['batch_id'] ? Batch::find($validated['batch_id']) : null;
            $validated['program_type'] = $batchObj?->program_type ?? 'Academic';
        }

        // Auto-generate guaranteed unique student ID (STD-2401, STD-2402, ...)
        $maxNum = 2400;
        $existingIds = Student::pluck('student_id');
        foreach ($existingIds as $sid) {
            if (preg_match('/STD-(\d+)/', $sid, $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $validated['student_id'] = 'STD-' . ($maxNum + 1);

        // Set or auto-generate login email
        $loginEmail = $request->filled('email') 
            ? trim($request->email) 
            : strtolower(str_replace('-', '', $validated['student_id'])) . '@student.com';
        $validated['email'] = $loginEmail;

        // Avatar upload handling
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = 'storage/' . $path;
        } else {
            $validated['avatar'] = 'images/student_female_profile_1790497748277.jpg';
        }

        // Create student record
        $student = Student::create($validated);

        // Create or update login account for the student
        $plainPassword = $request->filled('password') ? $request->password : '123456';
        User::updateOrCreate(
            ['email' => $loginEmail],
            [
                'name' => $student->name,
                'password' => Hash::make($plainPassword),
                'role' => 'student',
                'phone' => $student->phone,
                'student_id' => $student->id,
                'avatar' => $student->avatar,
                'status' => 'active',
            ]
        );

        // Process Fee Setup if opted in and amount provided
        if ($request->filled('setup_fees') && $request->filled('fee_amount') && floatval($request->fee_amount) > 0) {
            $feeType = $request->input('fee_type', 'onetime');
            $totalAmount = floatval($request->fee_amount);
            $discount = floatval($request->input('fee_discount', 0));
            $payable = max(0, $totalAmount - $discount);
            $paidNow = floatval($request->input('fee_paid_amount', 0));
            $paymentMethod = $request->input('fee_payment_method', 'Cash');
            $paymentDate = $request->input('fee_payment_date', now()->toDateString());
            $feeNotes = $request->input('fee_notes', '');

            if ($feeType === 'installment') {
                $isUnlimited = ($request->input('installment_mode') === 'unlimited') || empty($request->input('total_installments'));

                if ($isUnlimited) {
                    // Unlimited / Ongoing Installment: creates Installment #1 with total_installments = null (representing unlimited/ongoing plan)
                    $paid = min($payable, $paidNow);
                    $due = max(0, $payable - $paid);
                    $status = ($due == 0) ? 'Paid' : (($paid > 0) ? 'Partial' : 'Due');

                    $lastFee = Fee::latest('id')->first();
                    $nextId = $lastFee ? ($lastFee->id + 1) : 1;
                    $invNo = 'INV-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

                    $fee = Fee::create([
                        'invoice_no' => $invNo,
                        'student_id' => $student->id,
                        'batch_id' => $student->batch_id,
                        'fee_type' => 'installment',
                        'installment_no' => 1,
                        'total_installments' => null, // null represents unlimited / ongoing
                        'month' => date('F', strtotime($paymentDate)),
                        'year' => intval(date('Y', strtotime($paymentDate))),
                        'amount' => $totalAmount,
                        'discount' => $discount,
                        'paid_amount' => $paid,
                        'due_amount' => $due,
                        'payment_method' => $paymentMethod,
                        'payment_date' => $paid > 0 ? $paymentDate : null,
                        'due_date' => $paymentDate,
                        'status' => $status,
                        'notes' => 'Installment 1 (Unlimited / Ongoing)' . ($feeNotes ? " - {$feeNotes}" : ''),
                    ]);

                    if ($paid > 0) {
                        Account::create([
                            'title' => "Fee Collection - {$student->name} (Installment 1 - Unlimited)",
                            'type' => 'income',
                            'category' => 'Student Fee',
                            'amount' => $paid,
                            'payment_method' => $paymentMethod,
                            'date' => $paymentDate,
                            'reference' => $invNo,
                            'description' => "Installment 1 (Unlimited / Ongoing) fee collection for {$student->student_id}",
                        ]);
                    }
                } else {
                    // Fixed installments: can be any count (e.g. 2, 3, 5, 8, 10, 12, 18, 24, 36...)
                    $numInstallments = max(1, min(999, intval($request->input('total_installments', 3))));
                    $intervalMonths = ($request->input('installment_interval') === 'bi_monthly') ? 2 : 1;

                    $instPayable = round($payable / $numInstallments, 2);
                    $lastInstPayable = round($payable - ($instPayable * ($numInstallments - 1)), 2);

                    $instAmount = round($totalAmount / $numInstallments, 2);
                    $lastInstAmount = round($totalAmount - ($instAmount * ($numInstallments - 1)), 2);

                    $instDiscount = round($discount / $numInstallments, 2);
                    $lastInstDiscount = round($discount - ($instDiscount * ($numInstallments - 1)), 2);

                    $remainingPaid = $paidNow;

                    for ($i = 1; $i <= $numInstallments; $i++) {
                        $thisPayable = ($i == $numInstallments) ? $lastInstPayable : $instPayable;
                        $thisAmount = ($i == $numInstallments) ? $lastInstAmount : $instAmount;
                        $thisDiscount = ($i == $numInstallments) ? $lastInstDiscount : $instDiscount;

                        $thisPaid = min($thisPayable, max(0, $remainingPaid));
                        $remainingPaid -= $thisPaid;
                        $thisDue = max(0, $thisPayable - $thisPaid);
                        $thisStatus = ($thisDue == 0) ? 'Paid' : (($thisPaid > 0) ? 'Partial' : 'Due');

                        $offsetMonths = ($i - 1) * $intervalMonths;
                        $dueDate = date('Y-m-d', strtotime("+{$offsetMonths} month", strtotime($paymentDate)));
                        $month = date('F', strtotime($dueDate));
                        $year = intval(date('Y', strtotime($dueDate)));

                        $lastFee = Fee::latest('id')->first();
                        $nextId = $lastFee ? ($lastFee->id + 1) : 1;
                        $invNo = 'INV-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

                        $fee = Fee::create([
                            'invoice_no' => $invNo,
                            'student_id' => $student->id,
                            'batch_id' => $student->batch_id,
                            'fee_type' => 'installment',
                            'installment_no' => $i,
                            'total_installments' => $numInstallments,
                            'month' => $month,
                            'year' => $year,
                            'amount' => $thisAmount,
                            'discount' => $thisDiscount,
                            'paid_amount' => $thisPaid,
                            'due_amount' => $thisDue,
                            'payment_method' => $paymentMethod,
                            'payment_date' => $thisPaid > 0 ? $paymentDate : null,
                            'due_date' => $dueDate,
                            'status' => $thisStatus,
                            'notes' => "Installment {$i}/{$numInstallments}" . ($feeNotes ? " - {$feeNotes}" : ""),
                        ]);

                        if ($thisPaid > 0) {
                            Account::create([
                                'title' => "Fee Collection - {$student->name} (Installment {$i}/{$numInstallments})",
                                'type' => 'income',
                                'category' => 'Student Fee',
                                'amount' => $thisPaid,
                                'payment_method' => $paymentMethod,
                                'date' => $paymentDate,
                                'reference' => $invNo,
                                'description' => "Installment {$i}/{$numInstallments} fee collection for {$student->student_id}",
                            ]);
                        }
                    }
                }
            } else {
                // One-time payment
                $paid = min($payable, $paidNow);
                $due = max(0, $payable - $paid);
                $status = ($due == 0) ? 'Paid' : (($paid > 0) ? 'Partial' : 'Due');

                $lastFee = Fee::latest('id')->first();
                $nextId = $lastFee ? ($lastFee->id + 1) : 1;
                $invNo = 'INV-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

                $fee = Fee::create([
                    'invoice_no' => $invNo,
                    'student_id' => $student->id,
                    'batch_id' => $student->batch_id,
                    'fee_type' => 'onetime',
                    'month' => date('F', strtotime($paymentDate)),
                    'year' => intval(date('Y', strtotime($paymentDate))),
                    'amount' => $totalAmount,
                    'discount' => $discount,
                    'paid_amount' => $paid,
                    'due_amount' => $due,
                    'payment_method' => $paymentMethod,
                    'payment_date' => $paid > 0 ? $paymentDate : null,
                    'due_date' => $paymentDate,
                    'status' => $status,
                    'notes' => $feeNotes ?: 'One-time admission & tuition fee',
                ]);

                if ($paid > 0) {
                    Account::create([
                        'title' => "Fee Collection - {$student->name} (One-time Admission)",
                        'type' => 'income',
                        'category' => 'Student Fee',
                        'amount' => $paid,
                        'payment_method' => $paymentMethod,
                        'date' => $paymentDate,
                        'reference' => $invNo,
                        'description' => "One-time admission fee collection for {$student->student_id}",
                    ]);
                }
            }
        }

        return redirect()->route('admin.students')->with('success', 'Student ' . $student->name . ' (' . $student->student_id . ') added successfully! Login credentials generated.');
    }

    public function show($id = null)
    {
        $student = $id ? Student::with(['batch', 'course', 'attendances', 'results.exam', 'fees'])->findOrFail($id)
                       : Student::with(['batch', 'course', 'attendances', 'results.exam', 'fees'])->first();

        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'No student found.');
        }

        $totalDays = $student->attendances->count();
        $presentDays = $student->attendances->where('status', 'present')->count();
        $attRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 94;

        $avgGpa = $student->results->avg('gpa') ?: 4.83;
        $totalPaid = $student->fees->sum('paid_amount');
        $totalDue = $student->fees->sum('due_amount');

        return view('admin.student-profile', compact('student', 'attRate', 'avgGpa', 'totalPaid', 'totalDue'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()?->canEdit('students')) {
            return redirect()->back()->with('error', 'You do not have permission to edit students.');
        }

        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:4|max:100',
            'phone' => 'required|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'program_type' => 'nullable|in:Academic,Target',
            'class_name' => 'required|string|max:100',
            'course_id' => 'nullable',
            'batch_id' => 'nullable',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,Completed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $validated['course_id'] = $request->filled('course_id') ? $request->course_id : null;
        $validated['batch_id'] = $request->filled('batch_id') ? $request->batch_id : null;
        $validated['email'] = $request->filled('email') ? $request->email : $student->email;

        $classObj = AcademicClass::where('name', $validated['class_name'])->first();
        if ($classObj) {
            $validated['class_id'] = $classObj->id;
            if (empty($validated['program_type'])) {
                $validated['program_type'] = $classObj->class_type ?? 'Academic';
            }
        }

        if (empty($validated['program_type'])) {
            $batchObj = $validated['batch_id'] ? Batch::find($validated['batch_id']) : null;
            $validated['program_type'] = $batchObj?->program_type ?? ($student->program_type ?? 'Academic');
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = 'storage/' . $path;
        }

        $student->update($validated);

        // Update student user login details if user exists
        $user = User::where('student_id', $student->id)
                    ->orWhere('email', $student->email)
                    ->first();
        if ($user) {
            $userData = [
                'name' => $student->name,
                'phone' => $student->phone,
                'avatar' => $student->avatar,
            ];
            if (!empty($student->email)) {
                $userData['email'] = $student->email;
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
        }

        return redirect()->route('admin.students')->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        if (!auth()->user()?->canDelete('students')) {
            return redirect()->back()->with('error', 'You do not have permission to delete students.');
        }

        $student = Student::findOrFail($id);
        User::where('student_id', $student->id)->delete();
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Student deleted successfully!');
    }
}
