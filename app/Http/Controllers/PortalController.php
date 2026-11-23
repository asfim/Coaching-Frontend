<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function studentDashboard()
    {
        return view('student.dashboard');
    }

    public function parentDashboard()
    {
        return view('parent.dashboard');
    }
}
