@extends('layouts.app')

@section('title', 'Browse Found Properties')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Found Items Directory</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Recovered / Found Properties Directory</h1>
    </div>

    <a href="{{ route('found-items.create') }}" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2 self-start md:self-auto">
        <i class="fa-solid fa-plus"></i> Register Found Property
    </a>
</div>

<!-- Search & Filter Controls -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 mb-6 shadow-sm">
    <form action="{{ route('found-items.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
        <div>
            <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Search Keywords</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, location, specs..."
                class="w-full px-3 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
        </div>

        <div>
            <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Category</label>
            <select name="category_id" class="w-full px-3 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->category_name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
            <div>
                <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Approval State</label>
                <select name="approval_status" class="w-full px-3 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <option value="">All States</option>
                    <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending_review" {{ request('approval_status') == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                    <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        @endif

        <div>
            <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Custody Status</label>
            <select name="status" class="w-full px-3 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                <option value="">All Custody States</option>
                <option value="reported" {{ request('status') === 'reported' ? 'selected' : '' }}>In Custody (Reported)</option>
                <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claim Approved</option>
                <option value="recovered" {{ request('status') === 'recovered' ? 'selected' : '' }}>Handed Over</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-2 px-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl text-xs transition-colors flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-filter"></i> Apply Filter
            </button>
            <a href="{{ route('found-items.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 font-semibold rounded-xl text-xs transition-colors">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Table View -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
    @if($foundItems->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-zinc-800 text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="pb-3">Found Item & Category</th>
                        <th class="pb-3">Location Found</th>
                        <th class="pb-3">Safeguard Storage Location</th>
                        <th class="pb-3">Date Found</th>
                        <th class="pb-3">Approval / Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                    @foreach($foundItems as $item)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-zinc-800 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm shrink-0">
                                        <i class="fa-solid {{ $item->category->icon ?? 'fa-box' }}"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('found-items.show', $item->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 block text-sm">
                                            {{ $item->item_name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 font-medium">{{ $item->category->category_name ?? 'General' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-slate-700 dark:text-zinc-300 font-medium">
                                {{ $item->location_found }}
                            </td>
                            <td class="py-4 text-slate-500 dark:text-zinc-400">
                                {{ $item->storage_location }}
                            </td>
                            <td class="py-4 text-slate-500">
                                {{ $item->date_found->format('M d, Y') }}
                            </td>
                            <td class="py-4">
                                <div class="space-y-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase inline-block {{ $item->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($item->approval_status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ str_replace('_', ' ', $item->approval_status) }}
                                    </span>
                                    @if($item->is_frozen)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-100 text-purple-700 block w-max">
                                            <i class="fa-solid fa-lock"></i> Frozen
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 text-right">
                                <a href="{{ route('found-items.show', $item->id) }}" class="px-3 py-1.5 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    Inspect & Claim &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800">
            {{ $foundItems->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-purple-50 dark:bg-zinc-800 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h4 class="font-bold text-slate-700 dark:text-white text-base">No found properties matching filter</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">No property items in custody match your specified parameters.</p>
        </div>
    @endif
</div>
@endsection
