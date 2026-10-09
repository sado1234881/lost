@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">Profile Settings</span>
    </nav>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white mb-6">User Account Settings</h1>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Registration / Staff Number</label>
                <input type="text" value="{{ $user->registration_number }}" disabled
                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-100 dark:bg-zinc-800/40 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                <span class="text-[10px] text-slate-400 block mt-1">Registration numbers are managed by SJUT Security Registry.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-100 dark:bg-zinc-800/40 rounded-xl text-sm text-slate-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="e.g. +255 712 345 678"
                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
            </div>

            <div class="border-t border-slate-100 dark:border-zinc-800 pt-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Change Password</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">New Password</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current"
                            class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Leave blank to keep current"
                            class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-6 rounded-xl text-sm shadow-md transition-all">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
