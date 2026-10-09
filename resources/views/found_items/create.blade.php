@extends('layouts.app')

@section('title', 'Report Found Property')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <a href="{{ route('found-items.index') }}" class="hover:underline">Found Properties</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">Register Found Item</span>
    </nav>
    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Register Found Property</h1>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('found-items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Item Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Item Title / Name *</label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required placeholder="e.g. Samsung Galaxy A32 Black Case"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Item Color</label>
                    <input type="text" name="color" value="{{ old('color') }}" placeholder="e.g. Black"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Date Found *</label>
                    <input type="date" name="date_found" value="{{ old('date_found', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Location Found *</label>
                    <input type="text" name="location_found" value="{{ old('location_found') }}" required placeholder="e.g. Cafeteria Annex Table 4"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Current Physical Storage / Safeguard Location *</label>
                <input type="text" name="storage_location" value="{{ old('storage_location', 'SJUT Central Security Desk Cabinet A-1') }}" required placeholder="e.g. Security Office Desk / Hostel Matron"
                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Detailed Description *</label>
                <textarea name="description" rows="4" required placeholder="Describe appearance, condition, key details..."
                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Upload Item Photo (Optional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none dark:text-white">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('found-items.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 rounded-xl text-sm font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-6 rounded-xl text-sm shadow-md transition-all flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Register Found Item
                </button>
            </div>
        </form>
    </div>

    <!-- CONTEXTUAL NAVIGATION BANNER -->
    <div class="p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-circle-exclamation text-amber-100"></i>
            </div>
            <div>
                <h4 class="font-bold text-xs text-slate-900 dark:text-white">Have you also lost something on campus?</h4>
                <p class="text-[11px] text-slate-500 dark:text-zinc-400">Log a missing property report to trigger automated matching.</p>
            </div>
        </div>
        <a href="{{ route('lost-items.create') }}" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shrink-0 shadow-sm transition-colors flex items-center gap-1.5">
            <span>Report Lost Property</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>
</div>
@endsection
