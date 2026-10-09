@extends('layouts.app')

@section('title', 'Report Lost Property')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <a href="{{ route('lost-items.index') }}" class="hover:underline">Lost Properties</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">Report Missing Item</span>
    </nav>
    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Report Lost Property</h1>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('lost-items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Category Select -->
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

                <!-- Item Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Item Title / Name *</label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required placeholder="e.g. HP Pavilion Laptop Silver 15-inch"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Color -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Item Color</label>
                    <input type="text" name="color" value="{{ old('color') }}" placeholder="e.g. Black / Silver"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>

                <!-- Date Lost -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Date Lost *</label>
                    <input type="date" name="date_lost" value="{{ old('date_lost', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>

                <!-- Location Lost -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Location Lost *</label>
                    <input type="text" name="location_lost" value="{{ old('location_lost') }}" required placeholder="e.g. Main Library 2nd Floor"
                        class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Detailed Description & Distinctive Features *</label>
                <textarea name="description" rows="4" required placeholder="Describe distinguishing marks, serial numbers, case type, stickers, contents..."
                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Upload Photo (Optional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none dark:text-white">
                <p class="text-[11px] text-slate-400 mt-1">Accepted formats: JPG, PNG, WEBP (Max size 4MB)</p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('lost-items.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 rounded-xl text-sm font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-bold py-2.5 px-6 rounded-xl text-sm shadow-md transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Submit Lost Report
                </button>
            </div>
        </form>
    </div>

    <!-- CONTEXTUAL NAVIGATION BANNER -->
    <div class="p-5 rounded-2xl bg-purple-50 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-800 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-900 text-white flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-hand-holding-hand text-amber-400"></i>
            </div>
            <div>
                <h4 class="font-bold text-xs text-slate-900 dark:text-white">Did you also find an item on campus?</h4>
                <p class="text-[11px] text-slate-500 dark:text-zinc-400">Help a fellow student by reporting property turned in or discovered.</p>
            </div>
        </div>
        <a href="{{ route('found-items.create') }}" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-white font-bold text-xs shrink-0 shadow-sm transition-colors flex items-center gap-1.5">
            <span>Report Found Property</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>
</div>
@endsection
