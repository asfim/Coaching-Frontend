<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                'parent' => redirect()->route('parent.dashboard'),
                default => redirect()->route('home'),
            };
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Check if student ID (e.g., STD-2401)
        if (str_starts_with(strtoupper($loginInput), 'STD-')) {
            $student = Student::where('student_id', strtoupper($loginInput))->first();
            if ($student) {
                $user = User::where('student_id', $student->id)
                            ->orWhere('email', $student->email)
                            ->first();
                if ($user && Auth::attempt(['email' => $user->email, 'password' => $password], $remember)) {
                    $request->session()->regenerate();
                    return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
                }
            }
        }

        // Try login by email or phone
        $credentials = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? ['email' => $loginInput, 'password' => $password]
            : ['phone' => $loginInput, 'password' => $password];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $targetRoute = match ($user->role) {
                'admin' => route('admin.dashboard'),
                'teacher' => route('teacher.dashboard'),
                'student' => route('student.dashboard'),
                'parent' => route('parent.dashboard'),
                default => route('home'),
            };

            return redirect()->intended($targetRoute)->with('success', 'Welcome, ' . $user->name . '! (' . ucfirst($user->role) . ' Portal)');
        }

        return back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'Invalid credentials. Please check your login details and password.']);
    }

    public function quickLogin($role)
    {
        $validRoles = ['admin', 'teacher', 'student', 'parent'];
        if (!in_array($role, $validRoles, true)) {
            return redirect()->route('login')->with('error', 'Invalid role requested.');
        }

        $user = User::where('role', $role)->first();
        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();

            return match ($role) {
                'admin' => redirect()->route('admin.dashboard')->with('success', 'Logged in as Demo Admin'),
                'teacher' => redirect()->route('teacher.dashboard')->with('success', 'Logged in as Demo Teacher'),
                'student' => redirect()->route('student.dashboard')->with('success', 'Logged in as Demo Student'),
                'parent' => redirect()->route('parent.dashboard')->with('success', 'Logged in as Demo Parent'),
            };
        }

        return redirect()->route('login')->with('error', 'No user account found for role: ' . $role);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }
}
