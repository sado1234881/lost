@extends('layouts.app')

@section('title', 'Personal Property Recovery Workspace')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
            <span>SJUT Campus Portal</span>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Personal Property Workspace</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Personal Property Recovery Workspace</h1>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Authorized User: <strong class="text-slate-700 dark:text-slate-200">{{ Auth::user()->name }}</strong> • Reg No: <strong class="text-purple-700 dark:text-purple-400 font-mono">{{ Auth::user()->registration_number }}</strong></p>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('lost-items.create') }}" class="bg-amber-600 hover:bg-amber-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> Report Lost Item
        </a>
        <a href="{{ route('found-items.create') }}" class="bg-purple-900 hover:bg-purple-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-hand-holding-hand text-amber-300"></i> Report Found Item
        </a>
    </div>
</div>

<!-- Quick Action Navigation Hub -->
<div class="mb-6 bg-white dark:bg-zinc-900 border border-purple-100 dark:border-zinc-800 rounded-3xl p-5 shadow-sm">
    <span class="text-[11px] font-extrabold text-purple-700 dark:text-purple-400 uppercase tracking-widest block mb-3">Institutional Recovery Operations</span>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <a href="{{ route('lost-items.create') }}" class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 hover:border-amber-400 transition-all flex flex-col items-center text-center space-y-1.5 group">
            <div class="w-9 h-9 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center text-base group-hover:scale-110 transition-transform shadow-sm">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <span class="text-xs font-bold text-slate-900 dark:text-white">Report Lost Property</span>
        </a>

        <a href="{{ route('found-items.create') }}" class="p-3.5 rounded-2xl bg-purple-50 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-800/60 hover:border-purple-400 transition-all flex flex-col items-center text-center space-y-1.5 group">
            <div class="w-9 h-9 rounded-xl bg-purple-900 text-white flex items-center justify-center text-base group-hover:scale-110 transition-transform shadow-sm">
                <i class="fa-solid fa-hand-holding-hand text-amber-300"></i>
            </div>
            <span class="text-xs font-bold text-slate-900 dark:text-white">Report Found Property</span>
        </a>

        <a href="{{ route('lost-items.index') }}" class="p-3.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700/60 hover:border-blue-400 transition-all flex flex-col items-center text-center space-y-1.5 group">
            <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 flex items-center justify-center text-base group-hover:scale-110 transition-transform shadow-sm">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <span class="text-xs font-bold text-slate-900 dark:text-white">Active Lost Registry</span>
        </a>

        <a href="{{ route('found-items.index') }}" class="p-3.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700/60 hover:border-purple-400 transition-all flex flex-col items-center text-center space-y-1.5 group">
            <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 flex items-center justify-center text-base group-hover:scale-110 transition-transform shadow-sm">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <span class="text-xs font-bold text-slate-900 dark:text-white">Campus Found Directory</span>
        </a>

        <a href="{{ route('claims.index') }}" class="p-3.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700/60 hover:border-amber-400 transition-all flex flex-col items-center text-center space-y-1.5 group">
            <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 flex items-center justify-center text-base group-hover:scale-110 transition-transform shadow-sm">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <span class="text-xs font-bold text-slate-900 dark:text-white">My Ownership Claims</span>
        </a>

        <a href="{{ route('recovery.index') }}" class="p-3.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700/60 hover:border-emerald-400 transition-all flex flex-col items-center text-center space-y-1.5 group">
            <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-base group-hover:scale-110 transition-transform shadow-sm">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <span class="text-xs font-bold text-slate-900 dark:text-white">Recovery Audit Trail</span>
        </a>
    </div>
</div>

<!-- Personal Operational Metrics (6 Summary Cards) -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Lost Reports</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $myLostCount }}</span>
            <i class="fa-solid fa-magnifying-glass text-amber-500 text-sm"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Found Deposited</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $myFoundCount }}</span>
            <i class="fa-solid fa-hand-holding-hand text-emerald-500 text-sm"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Active Public</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $activeReportsCount }}</span>
            <i class="fa-solid fa-globe text-blue-500 text-sm"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Review Queue</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingReportsCount }}</span>
            <i class="fa-solid fa-clock text-amber-500 text-sm"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Claims Submitted</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $myClaimsCount }}</span>
            <i class="fa-solid fa-file-signature text-purple-500 text-sm"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Resolved Cases</span>
        <div class="flex items-baseline justify-between mt-2">
            <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $resolvedItemsCount }}</span>
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
        </div>
    </div>
</div>

<!-- Main Section Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- My Submitted Reports & Potential Matches (2 Columns) -->
    <div class="lg:col-span-2 space-y-6">

        <!-- My Lost Property Reports -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-amber-500"></i> Personal Lost Property Repository
                </h3>
                <a href="{{ route('lost-items.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($myLostItems as $lost)
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $lost->item_name }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase {{ $lost->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : ($lost->approval_status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300') }}">
                                    {{ str_replace('_', ' ', $lost->approval_status) }}
                                </span>
                                @if($lost->is_frozen)
                                    <span class="text-[10px] px-2 py-0.5 rounded font-bold bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300 uppercase">
                                        <i class="fa-solid fa-lock"></i> Frozen
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-zinc-400">Lost at: <strong>{{ $lost->location_lost }}</strong> on {{ $lost->date_lost->format('M d, Y') }}</p>
                            @if($lost->matchedFoundItem)
                                <div class="mt-2 p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs flex items-center justify-between">
                                    <span><i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> Potential Match Found: <strong>{{ $lost->matchedFoundItem->item_name }}</strong></span>
                                    <a href="{{ route('found-items.show', $lost->matchedFoundItem->id) }}" class="font-bold underline">View Match &rarr;</a>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('lost-items.show', $lost->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-semibold hover:bg-slate-300 transition-colors">
                                Details
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">You have not submitted any lost property reports yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Found Properties Directory Preview -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-purple-500"></i> Campus Found Property Central Registry
                </h3>
                <a href="{{ route('found-items.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">Full Directory &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($recentFoundMatches as $found)
                    <div class="bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-800 rounded-xl p-4 flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center justify-between text-[10px] mb-1">
                                <span class="px-2 py-0.5 rounded font-bold bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300 uppercase">
                                    {{ $found->category->category_name ?? 'General' }}
                                </span>
                                <span class="text-slate-400">{{ $found->date_found->format('M d, Y') }}</span>
                            </div>
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white">{{ $found->item_name }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-zinc-400 line-clamp-2 mt-1">{{ $found->description }}</p>
                        </div>
                        <div class="pt-2 border-t border-slate-200 dark:border-zinc-700 flex items-center justify-between">
                            <span class="text-[10px] text-slate-400"><i class="fa-solid fa-warehouse mr-1"></i> {{ Str::limit($found->storage_location, 18) }}</span>
                            <a href="{{ route('found-items.show', $found->id) }}" class="px-2.5 py-1 rounded bg-blue-900 text-white text-[11px] font-bold hover:bg-blue-800 transition-colors">
                                Claim Property
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center col-span-2 py-4">No active found items in registry.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Personal Claims & Action Status (1 Column) -->
    <div class="space-y-6">

        <!-- My Active Claims -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-purple-500"></i> Ownership Claims & QR Release Passes
                </h3>
                <a href="{{ route('claims.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($myClaims as $claim)
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-900 dark:text-white">Claim #{{ $claim->id }}</span>
                            @if($claim->claim_status === 'approved')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 uppercase">
                                    Approved
                                </span>
                            @elseif($claim->claim_status === 'released')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300 uppercase">
                                    Released
                                </span>
                            @elseif($claim->claim_status === 'closed')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700 dark:bg-zinc-700 dark:text-zinc-300 uppercase">
                                    Closed
                                </span>
                            @elseif($claim->claim_status === 'rejected')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300 uppercase">
                                    Declined
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300 uppercase">
                                    Pending
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-700 dark:text-zinc-300 font-semibold truncate">
                            {{ $claim->item->item_name ?? 'Property Item' }}
                        </p>

                        <div class="flex items-center justify-between pt-1 text-[11px]">
                            <span class="text-slate-400">{{ $claim->claim_date->format('M d, Y') }}</span>
                            @if($claim->claim_status === 'approved')
                                <a href="{{ route('claims.qr', $claim->id) }}" class="text-emerald-600 dark:text-emerald-400 font-bold hover:underline">
                                    <i class="fa-solid fa-qrcode"></i> Show Release Code
                                </a>
                            @else
                                <a href="{{ route('claims.show', $claim->id) }}" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">View Claim Details</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">No active ownership claims submitted.</p>
                @endforelse
            </div>
        </div>

        <!-- System Direct Message Quick Box -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-3">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-comments text-blue-500"></i> Secure In-Platform Messenger
            </h3>
            <p class="text-xs text-slate-500 dark:text-zinc-400">Communicate directly with security staff or item finders regarding property claims.</p>
            <a href="{{ route('messages.index') }}" class="w-full py-2.5 rounded-xl bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 text-slate-700 dark:text-zinc-200 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                Open Messenger ({{ Auth::user()->unreadMessagesCount() }} unread)
            </a>
        </div>

    </div>

</div>
@endsection
