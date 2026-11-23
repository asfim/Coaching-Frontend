<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function students()
    {
        return view('admin.students');
    }

    public function studentProfile($id = null)
    {
        return view('admin.student-profile', compact('id'));
    }

    public function teachers()
    {
        return view('admin.teachers');
    }

    public function courses()
    {
        return view('admin.courses');
    }

    public function batches()
    {
        return view('admin.batches');
    }

    public function attendance()
    {
        return view('admin.attendance');
    }

    public function exams()
    {
        return view('admin.exams');
    }

    public function results()
    {
        return view('admin.results');
    }

    public function routine()
    {
        return view('admin.routine');
    }

    public function fees()
    {
        return view('admin.fees');
    }

    public function invoice($id = null)
    {
        return view('admin.invoice', compact('id'));
    }

    public function notices()
    {
        return view('admin.notices');
    }

    public function sms()
    {
        return view('admin.sms');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
