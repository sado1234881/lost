<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationOtpMail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('workspace');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Ensure user has verified email timestamp
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }

            $request->session()->regenerate();
            return redirect()->intended(route('workspace'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Step 1: Render Email Entry Screen
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('workspace');
        }
        return view('auth.register');
    }

    // Step 1 Submission: Generate and Send OTP Code
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $email = strtolower(trim($request->email));

        // Check if email already belongs to an existing account
        if (User::where('email', $email)->exists()) {
            return back()->with('email_registered', 'This email address (' . $email . ') is already associated with an account.')
                ->withInput();
        }

        // Check rate limit (30-second cooldown)
        $existing = EmailVerification::where('email', $email)->first();
        if ($existing && $existing->last_sent_at && $existing->last_sent_at->gt(now()->subSeconds(30))) {
            $secondsLeft = 30 - now()->diffInSeconds($existing->last_sent_at);
            return back()->withErrors(['email' => 'Please wait ' . $secondsLeft . ' seconds before requesting another code.'])
                ->withInput();
        }

        // Generate 6-digit random code
        $code = sprintf('%06d', mt_rand(100000, 999999));

        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'code_hash' => Hash::make($code),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
                'last_sent_at' => now(),
                'is_verified' => false,
            ]
        );

        // Send OTP email
        try {
            Mail::to($email)->send(new EmailVerificationOtpMail($code, 10));
        } catch (\Exception $e) {
            // Log mail exception if mailer fails
            \Log::error('OTP Email sending failed: ' . $e->getMessage());
        }

        return redirect()->route('register.verify', ['email' => $email])
            ->with('success', 'A 6-digit verification code has been sent to ' . $email);
    }

    // Step 2: Render OTP Code Input Screen
    public function showVerifyCode(Request $request)
    {
        $email = strtolower(trim($request->query('email')));

        if (!$email) {
            return redirect()->route('register')->with('error', 'Please enter your email address first.');
        }

        return view('auth.verify_otp', compact('email'));
    }

    // Step 2 Submission: Verify OTP Code
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $email = strtolower(trim($request->email));
        $code = trim($request->code);

        $verification = EmailVerification::where('email', $email)->first();

        if (!$verification) {
            return redirect()->route('register')->with('error', 'Verification session expired. Please enter your email again.');
        }

        // Check if attempts exceeded limit (5 attempts max)
        if ($verification->attempts >= 5) {
            return back()->with('error', 'Too many failed verification attempts. Please click "Resend Code" to get a new passcode.');
        }

        // Check expiration (10 minutes)
        if ($verification->expires_at->isPast()) {
            return back()->with('error', 'This verification code has expired. Please request a new code.');
        }

        // Check passcode match
        if (!Hash::check($code, $verification->code_hash)) {
            $verification->increment('attempts');
            return back()->with('error', 'Incorrect verification code. Please check your email and try again.');
        }

        // Mark verified
        $verification->update(['is_verified' => true]);
        session(['verified_email' => $email]);

        return redirect()->route('register.complete', ['email' => $email])
            ->with('success', 'Email verified successfully! Please complete your profile details.');
    }

    // Resend OTP Code
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->email));

        $verification = EmailVerification::where('email', $email)->first();

        if ($verification && $verification->last_sent_at && $verification->last_sent_at->gt(now()->subSeconds(30))) {
            $secondsLeft = 30 - now()->diffInSeconds($verification->last_sent_at);
            return back()->with('error', 'Please wait ' . $secondsLeft . ' seconds before requesting a new code.');
        }

        $code = sprintf('%06d', mt_rand(100000, 999999));

        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'code_hash' => Hash::make($code),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
                'last_sent_at' => now(),
                'is_verified' => false,
            ]
        );

        try {
            Mail::to($email)->send(new EmailVerificationOtpMail($code, 10));
        } catch (\Exception $e) {
            \Log::error('Resend OTP Email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'A new 6-digit verification code has been sent to ' . $email);
    }

    // Step 3: Render Complete Profile Screen
    public function showCompleteRegister(Request $request)
    {
        $email = strtolower(trim($request->query('email')));

        $verification = EmailVerification::where('email', $email)->where('is_verified', true)->first();

        if (!$verification && session('verified_email') !== $email) {
            return redirect()->route('register')->with('error', 'Please verify your email address before completing account setup.');
        }

        return view('auth.complete_register', compact('email'));
    }

    // Step 3 Submission: Complete Profile & Create User
    public function completeRegister(Request $request)
    {
        $email = strtolower(trim($request->email));

        $verification = EmailVerification::where('email', $email)->where('is_verified', true)->first();

        if (!$verification && session('verified_email') !== $email) {
            return redirect()->route('register')->with('error', 'Unverified email address. Please start registration process.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:50', 'unique:users,registration_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'registration_number' => strtoupper(trim($request->registration_number)),
            'email' => $email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Clean up verification record
        EmailVerification::where('email', $email)->delete();
        session()->forget('verified_email');

        Auth::login($user);

        return redirect()->route('workspace')
            ->with('success', 'Account created successfully! Welcome to SJUT Lost Property Recovery System.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    public function profile()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
