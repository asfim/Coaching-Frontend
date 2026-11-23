<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        return view('public.index');
    }

    public function about()
    {
        return view('public.about');
    }

    public function courses()
    {
        return view('public.courses');
    }

    public function courseDetail($slug = null)
    {
        return view('public.course-detail', compact('slug'));
    }

    public function teachers()
    {
        return view('public.teachers');
    }

    public function results()
    {
        return view('public.results');
    }

    public function notice()
    {
        return view('public.notice');
    }

    public function blog()
    {
        return view('public.blog');
    }

    public function contact()
    {
        return view('public.contact');
    }
}
