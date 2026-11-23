<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query()->latest();

        if ($request->filled('subject')) {
            $query->where('subject', 'like', "%{$request->subject}%");
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('subject', 'like', "%{$request->search}%");
            });
        }

        $teachers = $query->paginate(12);

        return view('admin.teachers', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'subject' => 'required|string|max:100',
            'qualification' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'join_date' => 'nullable|date',
            'bio' => 'nullable|string',
            'facebook' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('teachers', 'public');
        } else {
            $validated['image'] = 'images/teacher_male_1_1790497096029.jpg';
        }

        Teacher::create($validated);

        return redirect()->route('admin.teachers')->with('success', 'Teacher added successfully!');
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'subject' => 'required|string|max:100',
            'qualification' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('teachers', 'public');
        }

        $teacher->update($validated);

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully!');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teachers')->with('success', 'Teacher removed successfully!');
    }
}
