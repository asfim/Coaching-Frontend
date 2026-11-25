<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['user.permissions', 'user.roles'])->latest();

        if ($request->filled('subject')) {
            $query->where('subject', 'like', "%{$request->subject}%");
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('subject', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $teachers = $query->paginate(12);

        return view('admin.teachers', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
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
            'permissions' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('teachers', 'public');
            $validated['image'] = 'storage/' . $path;
        } else {
            $validated['image'] = 'images/teacher_male_1_1790497096029.jpg';
        }

        $permissions = $request->input('permissions', []);
        if (!is_array($permissions)) {
            $permissions = [];
        }
        unset($validated['permissions']);

        $teacher = Teacher::create($validated);

        // Create login User for the Teacher
        $password = $request->filled('password') ? $request->password : 'teacher123';
        $user = User::create([
            'name' => $teacher->name,
            'email' => $teacher->email,
            'password' => Hash::make($password),
            'role' => 'teacher',
            'phone' => $teacher->phone,
            'avatar' => $teacher->image,
            'teacher_id' => $teacher->id,
            'status' => $teacher->status,
        ]);
        $user->assignRole('teacher');

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }
        $user->syncPermissions($permissions);

        return redirect()->route('admin.teachers')->with('success', 'Teacher profile and login credentials created successfully!');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()?->canEdit('teachers')) {
            return redirect()->back()->with('error', 'You do not have permission to edit teachers.');
        }

        $teacher = Teacher::findOrFail($id);
        $user = User::where('teacher_id', $teacher->id)->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'subject' => 'required|string|max:100',
            'qualification' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'bio' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'permissions' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('teachers', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $permissions = $request->input('permissions', []);
        if (!is_array($permissions)) {
            $permissions = [];
        }
        unset($validated['permissions']);

        $teacher->update($validated);

        // Ensure permissions exist in Spatie
        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // Sync User Account
        if ($user) {
            $userData = [
                'name' => $teacher->name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'status' => $teacher->status,
            ];
            if (!empty($validated['image'])) {
                $userData['avatar'] = $validated['image'];
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
            $user->assignRole('teacher');
            $user->syncPermissions($permissions);
        } else {
            $user = User::create([
                'name' => $teacher->name,
                'email' => $teacher->email,
                'password' => Hash::make($request->filled('password') ? $request->password : 'teacher123'),
                'role' => 'teacher',
                'phone' => $teacher->phone,
                'avatar' => $teacher->image,
                'teacher_id' => $teacher->id,
                'status' => $teacher->status,
            ]);
            $user->assignRole('teacher');
            $user->syncPermissions($permissions);
        }

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully!');
    }

    public function destroy($id)
    {
        if (!auth()->user()?->canDelete('teachers')) {
            return redirect()->back()->with('error', 'You do not have permission to delete teachers.');
        }

        $teacher = Teacher::findOrFail($id);
        User::where('teacher_id', $teacher->id)->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers')->with('success', 'Teacher and linked user account removed successfully!');
    }
}
