<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
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
        $categories = CourseCategory::withCount('courses')->orderBy('name')->get();

        return view('admin.courses', compact('courses', 'categories'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        // Link with CourseCategory
        $catObj = CourseCategory::firstOrCreate(
            ['name' => $validated['category']],
            ['slug' => Str::slug($validated['category']), 'status' => 'Active']
        );
        $validated['category_id'] = $catObj->id;
        $validated['category'] = $catObj->name;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $catObj = CourseCategory::firstOrCreate(
            ['name' => $validated['category']],
            ['slug' => Str::slug($validated['category']), 'status' => 'Active']
        );
        $validated['category_id'] = $catObj->id;
        $validated['category'] = $catObj->name;

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

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_categories,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'Active';

        CourseCategory::create($validated);

        return redirect()->route('admin.courses')->with('success', 'Course category "' . $validated['name'] . '" added successfully!');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = CourseCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_categories,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $oldName = $category->name;
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        // Update any courses referencing old name
        Course::where('category', $oldName)->update(['category' => $validated['name']]);

        return redirect()->route('admin.courses')->with('success', 'Category updated successfully!');
    }

    public function destroyCategory($id)
    {
        $category = CourseCategory::findOrFail($id);
        Course::where('category_id', $category->id)->update(['category_id' => null]);
        $category->delete();

        return redirect()->route('admin.courses')->with('success', 'Course category deleted successfully!');
    }
}
