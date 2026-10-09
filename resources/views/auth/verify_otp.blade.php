@extends('layouts.auth')

@section('title', 'Verify Email — Step 2')

@section('content')
<div class="space-y-6">
    <!-- Header Step Progress -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-xs font-extrabold uppercase tracking-wider">
            <span class="w-4 h-4 rounded-full bg-amber-600 text-white flex items-center justify-center text-[10px]">2</span>
            <span>Step 2 of 3: Enter Passcode</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Verify Your Email</h2>
        <p class="text-xs text-slate-500 dark:text-zinc-400">We sent a 6-digit verification code to:</p>
        <p class="text-sm font-extrabold text-purple-800 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/40 py-1.5 px-3 rounded-xl inline-block border border-purple-200 dark:border-purple-800">
            {{ $email }}
        </p>
    </div>

    <!-- OTP Verification Form -->
    <form action="{{ route('register.verify') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label for="code" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-2 text-center">
                Enter 6-Digit Code Below
            </label>
            <div class="relative max-w-xs mx-auto">
                <input type="text" id="code" name="code" required maxlength="6" autofocus autocomplete="off" placeholder="• • • • • •"
                    class="w-full text-center tracking-[0.5em] font-mono text-2xl py-3 px-4 border-2 border-purple-300 dark:border-purple-800 bg-slate-50 dark:bg-zinc-800 rounded-2xl focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-600 dark:text-white font-extrabold shadow-inner">
            </div>
            <span class="text-[11px] text-slate-400 mt-2 block text-center">Code expires in 10 minutes.</span>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-purple-900 hover:bg-purple-800 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-purple-950/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
            <i class="fa-solid fa-circle-check text-amber-400"></i>
            <span>Verify Email Code</span>
        </button>
    </form>

    <!-- Resend & Change Email Actions -->
    <div class="pt-4 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between text-xs">
        <form action="{{ route('register.resend-code') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="text-purple-700 dark:text-purple-400 font-bold hover:underline flex items-center gap-1 cursor-pointer">
                <i class="fa-solid fa-rotate-right"></i> Resend Code
            </button>
        </form>

        <a href="{{ route('register') }}" class="text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-pen-to-square"></i> Change Email
        </a>
    </div>
</div>
@endsection
