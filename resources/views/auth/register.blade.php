@extends('layouts.auth')

@section('title', 'Create Account — Step 1')

@section('content')
<div class="space-y-6">
    <!-- Header Step Progress -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 text-xs font-extrabold uppercase tracking-wider">
            <span class="w-4 h-4 rounded-full bg-purple-900 text-amber-300 flex items-center justify-center text-[10px]">1</span>
            <span>Step 1 of 3: Email Verification</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Create Your SJUT Account</h2>
        <p class="text-xs text-slate-500 dark:text-zinc-400">Enter your email address to receive a secure 6-digit verification code.</p>
    </div>

    @if(session('email_registered'))
        <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 text-xs space-y-2">
            <div class="flex items-center gap-2 font-bold text-amber-800 dark:text-amber-300">
                <i class="fa-solid fa-circle-info text-amber-600"></i> Account Already Exists
            </div>
            <p>{{ session('email_registered') }}</p>
            <div class="flex items-center gap-3 pt-1">
                <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-white font-bold text-xs shadow-sm">
                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Sign In
                </a>
            </div>
        </div>
    @endif

    <!-- Step 1 Email Form -->
    <form action="{{ route('register.send-code') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-1">
                Email Address *
            </label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="e.g. student@sjut.ac.tz"
                    class="w-full pl-10 pr-4 py-3 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 dark:text-white">
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">We will send a 6-digit OTP code to verify email ownership.</span>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-purple-900 hover:bg-purple-800 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-purple-950/20 transition-all flex items-center justify-center gap-2 group cursor-pointer">
            <span>Continue to Verification</span>
            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </button>
    </form>

    <div class="pt-4 border-t border-slate-100 dark:border-zinc-800 text-center text-xs text-slate-500">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}" class="font-bold text-purple-700 dark:text-purple-400 hover:underline ml-1">
            Sign In Here
        </a>
    </div>
</div>
@endsection
