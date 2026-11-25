<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Setting;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index(Request $request)
    {
        $logs = SmsLog::latest()->paginate(15);
        $batches = Batch::all();
        $totalSent = SmsLog::count();

        return view('admin.sms', compact('logs', 'batches', 'totalSent'));
    }

    public function send(Request $request)
    {
        if (Setting::get('sms_notification_status', 'off') === 'off') {
            return redirect()->route('admin.sms')->with('error', 'SMS and Notification service is currently turned OFF (সার্ভিস বন্ধ রয়েছে). Enable it in Settings to send messages.');
        }

        $validated = $request->validate([
            'recipient_type' => 'required|string',
            'message' => 'required|string|max:480',
        ]);

        $type = $validated['recipient_type'];
        $message = $validated['message'];

        if ($type === 'all') {
            $students = Student::all();
            foreach ($students as $student) {
                SmsLog::create([
                    'recipient_type' => 'All Students',
                    'recipient' => $student->name,
                    'phone' => $student->phone,
                    'message' => $message,
                    'status' => 'Delivered',
                ]);
            }
        } elseif (is_numeric($type)) {
            $batch = Batch::find($type);
            $students = Student::where('batch_id', $type)->get();
            foreach ($students as $student) {
                SmsLog::create([
                    'recipient_type' => $batch ? $batch->name : 'Batch',
                    'recipient' => $student->name,
                    'phone' => $student->phone,
                    'message' => $message,
                    'status' => 'Delivered',
                ]);
            }
        } else {
            SmsLog::create([
                'recipient_type' => 'Custom',
                'recipient' => $request->input('recipient', 'Selected Contact'),
                'phone' => $request->input('phone', '01700-000000'),
                'message' => $message,
                'status' => 'Delivered',
            ]);
        }

        return redirect()->route('admin.sms')->with('success', 'SMS broadcast sent successfully!');
    }

    public function destroy($id)
    {
        $log = SmsLog::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.sms')->with('success', 'SMS log removed!');
    }
}
