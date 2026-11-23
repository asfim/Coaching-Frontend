<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::withCount(['batches', 'students'])->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $courses = $query->paginate(12);

        return view('admin.courses', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'duration' => 'required|string|max:50',
            'seats' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['image'] = 'storage/' . $path;
        } else {
            $validated['image'] = 'images/hero_classroom_1790497065510.jpg';
        }

        Course::create($validated);

        return redirect()->route('admin.courses')->with('success', 'Course created successfully!');
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'duration' => 'required|string|max:50',
            'seats' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['image'] = 'storage/' . $path;
        }
        $course->update($validated);

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully!');
    }
}
