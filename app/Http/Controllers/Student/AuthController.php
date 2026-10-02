<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('frontend.auth.student-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'student_id' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('student')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (! Auth::guard('student')->user()->is_active) {
                Auth::guard('student')->logout();
                return back()->withErrors(['student_id' => 'Your account is currently inactive. Please contact the school office.']);
            }

            return redirect()->intended(route('student.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('student')->user()->name . '!');
        }

        return back()
            ->withInput($request->only('student_id'))
            ->withErrors(['student_id' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }
}
