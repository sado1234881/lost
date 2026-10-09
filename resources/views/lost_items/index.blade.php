@extends('layouts.app')

@section('title', 'Browse Lost Properties')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Lost Items Directory</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Reported Lost Properties Directory</h1>
    </div>

    <a href="{{ route('lost-items.create') }}" class="bg-amber-600 hover:bg-amber-500 text-white font-semibold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2 self-start md:self-auto">
        <i class="fa-solid fa-circle-exclamation"></i> Report Lost Property
    </a>
</div>

<!-- Search & Filter Controls -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 mb-6 shadow-sm">
    <form action="{{ route('lost-items.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
        <!-- Search Keyword -->
        <div>
            <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Search Keywords</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, description, location..."
                class="w-full px-3 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
        </div>

        <!-- Category -->
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

        <!-- Approval Status (Staff/Admin) -->
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

        <!-- Color -->
        <div>
            <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Color</label>
            <input type="text" name="color" value="{{ request('color') }}" placeholder="e.g. Black, Silver"
                class="w-full px-3 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
        </div>

        <!-- Submit & Reset -->
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-2 px-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl text-xs transition-colors flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-filter"></i> Apply Filter
            </button>
            <a href="{{ route('lost-items.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300 font-semibold rounded-xl text-xs transition-colors">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Table View -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
    @if($lostItems->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-zinc-800 text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="pb-3">Item Title & Category</th>
                        <th class="pb-3">Reporter</th>
                        <th class="pb-3">Location Lost</th>
                        <th class="pb-3">Date Lost</th>
                        <th class="pb-3">Approval / Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                    @foreach($lostItems as $item)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-zinc-800 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm shrink-0">
                                        <i class="fa-solid {{ $item->category->icon ?? 'fa-box' }}"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('lost-items.show', $item->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 block text-sm">
                                            {{ $item->item_name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 font-medium">{{ $item->category->category_name ?? 'General' }} {{ $item->color ? '• ' . $item->color : '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="font-semibold text-slate-800 dark:text-zinc-200 block">{{ $item->user->name ?? 'User' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item->user->registration_number ?? 'N/A' }}</span>
                            </td>
                            <td class="py-4 text-slate-600 dark:text-zinc-300 font-medium">
                                {{ $item->location_lost }}
                            </td>
                            <td class="py-4 text-slate-500">
                                {{ $item->date_lost->format('M d, Y') }}
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
                                <a href="{{ route('lost-items.show', $item->id) }}" class="px-3 py-1.5 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 text-slate-700 dark:text-zinc-300 rounded-lg text-xs font-semibold transition-colors">
                                    Inspect &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800">
            {{ $lostItems->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-amber-50 dark:bg-zinc-800 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4 class="font-bold text-slate-700 dark:text-white text-base">No lost properties found</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">No records match your specified search filters or category parameters.</p>
        </div>
    @endif
</div>
@endsection
