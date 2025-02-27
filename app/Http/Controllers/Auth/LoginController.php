<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override the login attempt to check for email verification.
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and is not verified (assuming 'verified' column exists)
        if ($user && $user->verified != 1) {
            throw ValidationException::withMessages([
                'email' => ['Your email is not verified. Please verify your email before logging in.'],
            ]);
        }

        return $this->guard()->attempt(
            $credentials, $request->filled('remember')
        );
    }

    /**
     * Where to redirect users after login.
     */
    public function redirectTo()
    {
        if (!auth()->user()->verified) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Email verification pending');
        }

        if (auth()->user()->is_mentor) {
            return '/mentor/dashboard';
        }

        if (auth()->user()->is_admin) {
            return '/admin';
        }

        if (auth()->user()->is_mentee) {
            return '/mentee/dashboard';
        }

        // If the user has no valid role, log them out and redirect to login with an error message.
        Auth::logout();
        return redirect()->route('login')->with('error', 'Invalid credentials. Please try again.');
    }

    /**
     * Logout the user and redirect to login page.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}