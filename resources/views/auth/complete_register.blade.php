@extends('layouts.auth')

@section('title', 'Complete Account Setup — Step 3')

@section('content')
<div class="space-y-6">
    <!-- Header Step Progress -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-xs font-extrabold uppercase tracking-wider">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>Step 3 of 3: Account Details</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Complete Account Profile</h2>
        <p class="text-xs text-slate-500 dark:text-zinc-400">Email Verified: <strong class="text-purple-700 dark:text-purple-300">{{ $email }}</strong></p>
    </div>

    <!-- Final Account Profile Creation Form -->
    <form action="{{ route('register.complete') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-1">
                Full Name *
            </label>
            <div class="relative">
                <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g. John Doe"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 dark:text-white">
            </div>
        </div>

        <div>
            <label for="registration_number" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-1">
                Registration / Staff ID Number *
            </label>
            <div class="relative">
                <i class="fa-solid fa-id-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="registration_number" name="registration_number" required value="{{ old('registration_number') }}" placeholder="e.g. SJUT/BIT/2024/001"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 dark:text-white uppercase">
            </div>
        </div>

        <div>
            <label for="phone_number" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-1">
                Phone Number (For Contact & Verification)
            </label>
            <div class="relative">
                <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g. +255 700 000 000"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 dark:text-white">
            </div>
        </div>

        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-1">
                Account Password *
            </label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 dark:text-white">
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-1">
                Confirm Password *
            </label>
            <div class="relative">
                <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 dark:text-white">
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-emerald-700 hover:bg-emerald-600 text-white font-extrabold rounded-xl text-xs shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer mt-2">
            <i class="fa-solid fa-user-check"></i>
            <span>Create Account & Sign In</span>
        </button>
    </form>
</div>
@endsection
