@extends('layouts.app')

@section('title', 'Campus Security Staff Operations Portal')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <span>SJUT Operational Staff</span>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Custody & Verification Control</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Campus Security Staff Operations Portal</h1>
    </div>

    <!-- Staff Navigation Tabs -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('staff.pending') }}" class="bg-amber-600 hover:bg-amber-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left"></i> Intake Review Queue ({{ $stats['pending_approval'] }})
        </a>
        <a href="{{ route('staff.claims') }}" class="bg-purple-700 hover:bg-purple-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-file-signature text-amber-300"></i> Ownership Claims Queue ({{ $stats['pending_claims'] }})
        </a>
        <a href="{{ route('admin.release') }}" class="bg-purple-950 hover:bg-purple-900 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2 border border-purple-500/40">
            <i class="fa-solid fa-qrcode text-amber-400"></i> QR Release Scanner
        </a>
    </div>
</div>

<!-- 4 Staff Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('staff.pending') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between hover:border-amber-500 transition-all">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Intake Review Queue</span>
            <span class="text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $stats['pending_approval'] }}</span>
            <span class="text-[11px] text-amber-600 font-bold block">Awaiting Inspection &rarr;</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-zinc-800 text-amber-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-clock"></i>
        </div>
    </a>

    <a href="{{ route('staff.claims') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between hover:border-purple-500 transition-all">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Ownership Claims Queue</span>
            <span class="text-2xl font-extrabold text-purple-600 dark:text-purple-400">{{ $stats['pending_claims'] }}</span>
            <span class="text-[11px] text-purple-600 font-bold block">Proof Verification &rarr;</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-zinc-800 text-purple-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-file-signature"></i>
        </div>
    </a>

    <a href="{{ route('found-items.index') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between hover:border-blue-500 transition-all">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Central Storage Log</span>
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $stats['total_found_logged'] }}</span>
            <span class="text-[11px] text-blue-600 font-bold block">Secured Custody &rarr;</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-zinc-800 text-blue-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-warehouse"></i>
        </div>
    </a>

    <a href="{{ route('recovery.index') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between hover:border-emerald-500 transition-all">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Handed Over Properties</span>
            <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $stats['total_released'] }}</span>
            <span class="text-[11px] text-emerald-600 font-bold block">Handover History &rarr;</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-zinc-800 text-emerald-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </a>
</div>

<!-- Pending Review Queues Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Pending Lost Reports Queue -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-clock text-amber-500"></i> Lost Property Reports Pending Approval
            </h3>
            <a href="{{ route('staff.pending') }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">Full Queue &rarr;</a>
        </div>

        <div class="space-y-3">
            @forelse($pendingLost as $item)
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $item->item_name }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-700 uppercase">Pending Review</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">By <strong>{{ $item->user->name ?? 'User' }}</strong> ({{ $item->user->registration_number ?? 'Reg N/A' }})</p>
                    <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-zinc-700">
                        <a href="{{ route('lost-items.show', $item->id) }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">Review Details</a>
                        <div class="flex gap-2">
                            <form action="{{ route('lost-items.approve', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                    Approve
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-6">No lost property reports awaiting staff review.</p>
            @endforelse
        </div>
    </div>

    <!-- Pending Found Items Queue -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
            <h3 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-clock text-amber-500"></i> Found Property Submissions Pending Approval
            </h3>
            <a href="{{ route('staff.pending') }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">Full Queue &rarr;</a>
        </div>

        <div class="space-y-3">
            @forelse($pendingFound as $item)
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $item->item_name }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-700 uppercase">Pending Review</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Stored at: <strong>{{ $item->storage_location }}</strong></p>
                    <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-zinc-700">
                        <a href="{{ route('found-items.show', $item->id) }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">Review Details</a>
                        <div class="flex gap-2">
                            <form action="{{ route('found-items.approve', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                    Approve
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-6">No found property reports awaiting staff review.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
