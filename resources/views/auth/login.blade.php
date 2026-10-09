@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<h2 class="text-xl font-bold text-white mb-2">Sign In to Your Account</h2>
<p class="text-xs text-slate-400 mb-6">Enter your credentials to access the Lost & Found Recovery portal.</p>

<form action="{{ route('login') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email Address</label>
        <div class="relative">
            <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="e.g. student@sjut.ac.tz"
                class="w-full pl-10 pr-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-slate-500 transition-all">
        </div>
        @error('email')
            <span class="text-red-400 text-[11px] block mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Password</label>
        <div class="relative">
            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
            <input type="password" name="password" required placeholder="••••••••"
                class="w-full pl-10 pr-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-slate-500 transition-all">
        </div>
        @error('password')
            <span class="text-red-400 text-[11px] block mt-1">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex items-center justify-between text-xs text-slate-400">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="rounded border-slate-700 text-blue-600 focus:ring-blue-500 bg-slate-800">
            <span>Remember me</span>
        </label>
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl text-sm shadow-lg shadow-blue-950/50 transition-all flex items-center justify-center gap-2">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
    </button>
</form>

<div class="mt-6 pt-6 border-t border-slate-800 text-center">
    <p class="text-xs text-slate-400">
        Don't have an account yet?
        <a href="{{ route('register') }}" class="text-blue-400 font-semibold hover:underline ml-1">Create Account</a>
    </p>
</div>

<!-- Quick Demo Credentials Box -->
<div class="mt-6 p-3 rounded-xl bg-slate-800/60 border border-slate-700 text-[11px] text-slate-300">
    <p class="font-bold text-amber-400 mb-1"><i class="fa-solid fa-key"></i> Quick Demo Test Accounts:</p>
    <div class="space-y-0.5">
        <p><strong>Admin:</strong> <code>admin@sjut.ac.tz</code> / <code>password</code></p>
        <p><strong>Staff:</strong> <code>staff@sjut.ac.tz</code> / <code>password</code></p>
        <p><strong>Student:</strong> <code>student@sjut.ac.tz</code> / <code>password</code></p>
    </div>
</div>
@endsection
