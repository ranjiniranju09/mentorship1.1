<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\EmailVerification;

use App\Models\User;

class ForgotPasswordController extends Controller
{

    public function Emailverify(Request $request){

        return view('auth.passwords.emailverify');

    }
    public function sendVerificationEmail(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    // Retrieve user details
    $user = DB::table('users')->where('email', $request->email)->first();
    $token = Str::random(64);

    // Store or update verification data with an expiration time of 10 minutes
    DB::table('email_verifications')->updateOrInsert(
        ['user_id' => $user->id],
        [
            'email' => $user->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(10), // Set expiration time
            'created_at' => now(),
            'updated_at' => now()
        ]
    );

    // Send verification email
    Mail::to($user->email)->send(new \App\Mail\VerifyEmail($token));

    return back()->with('status', 'Verification email sent!');
}

    
public function verifyEmail($token)
{
    // Find verification entry by token and check expiration
    $verification = DB::table('email_verifications')
        ->where('token', $token)
        ->where('expires_at', '>=', now()) // Ensure the token is still valid
        ->first();

    if (!$verification) {
        return redirect()->route('password.forgotpassword')->withErrors(['error' => 'Invalid or expired token']);
    }

    // Store the verified email in session
    session(['verified_email' => $verification->email]);

    // return $verification;

    // Delete the verification entry (so they can't reuse it)
    DB::table('email_verifications')->where('token', $token)->delete();

    return redirect()->route('password.reset'); // Redirect to reset password page
}


    public function forgotpassword()
    {
        return view('auth.passwords.forgotpassword');
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if the session has a verified email
        if (!session()->has('verified_email')) {
            return redirect()->route('password.forgotpassword')->withErrors(['email' => 'You need to verify your email first.']);
        }

        // Retrieve the verified email from session
        $email = session('verified_email');

        // Find user and update password
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Remove the verified email from session after reset
        session()->forget('verified_email');

        // Send reset confirmation email
        Mail::to($user->email)->send(new ResetPasswordMail($user));

        return redirect()->route('login')->with('success', 'Password has been reset successfully. A confirmation email has been sent.');
    }


}
