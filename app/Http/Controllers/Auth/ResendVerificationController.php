<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResendVerificationToken;
use Illuminate\Support\Str;

class ResendVerificationController extends Controller
{
    public function showVerificationForm()
    {
        return view('auth.resentverification');
    }

    public function resendVerificationEmail(Request $request)
    {
        // Validate the email input
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Retrieve the user by email using query builder
        // Retrieve the user by email using query builder
        $user = DB::table('users')->where('email', $validated['email'])->first();

        // Fetch the role_id from role_user table
        $roleId = DB::table('role_user')
            ->where('user_id', $user->id)
            ->value('role_id'); 

        // Fetch the role title from the roles table based on role_id
        $role = DB::table('roles')
            ->where('id', $roleId)
            ->value('title');        
        
        // Check if the user exists and the email is not already verified
        if ($user && !$user->verified) {

            // Generate a unique token for the verification
            $token = Str::random(64);

            // Calculate the expiration time ( 60 minutes from now)
            $expiresAt = now()->addMinutes(60);

            // Check if the email already exists in the email_verifications table
            $existingVerification = DB::table('email_verifications')
                ->where('email', $user->email)
                ->first();

            if ($existingVerification) {
                // Update the existing token and expiration
                DB::table('email_verifications')
                    ->where('email', $user->email)
                    ->update([
                        'token' => $token,
                        'expires_at' => $expiresAt,
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert a new token with expiration
                DB::table('email_verifications')->insert([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'token' => $token,
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Generate a verification URL
            $verificationUrl = route('verification.verify', ['token' => $token]);

            Mail::to($user->email)->send(new ResendVerificationToken($user->name, $verificationUrl, $role ));

            

            return redirect()->back()->with('success', 'A verification email has been sent to your address!');
        }

        return redirect()->back()->with('error', 'The email address is either already verified or does not exist.');
    }

    public function verify($token)
    {
        // Retrieve the verification record using the token
        $verification = DB::table('email_verifications')->where('token', $token)->first();

        // Check if the token exists and is not expired
        if ($verification && $verification->expires_at >= now()) {
            // Mark the user's email as verified
            DB::table('users')->where('id', $verification->user_id)->update([
                'verified_at' => now(),
                'verified' => 1
            ]);


            // Delete the token after successful verification
            DB::table('email_verifications')->where('token', $token)->delete();

            return redirect()->route('login')->with('success', 'Your email has been verified successfully!');
        }

        return redirect()->route('login')->with('error', 'Invalid or expired verification token.');
    }
}
