@extends('layouts.app')

@section('title', 'Recovery History Audit Trail')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Recovery Audit</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Recovery History & Audit Trail</h1>
    </div>
</div>

<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-6">
    @if($histories->count() > 0)
        <!-- Timeline Audit View -->
        <div class="relative pl-6 border-l-2 border-slate-100 dark:border-zinc-800 space-y-8">
            @foreach($histories as $history)
                <div class="relative">
                    <!-- Bullet Marker -->
                    <span class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-white dark:bg-zinc-900 border-2 border-emerald-600 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    </span>

                    <div class="bg-slate-50 dark:bg-zinc-800/60 p-4 rounded-xl border border-slate-100 dark:border-zinc-800 space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">
                                {{ $history->item_name }} ({{ $history->category_name }})
                            </h4>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 uppercase self-start sm:self-auto">
                                <i class="fa-solid fa-shield-check mr-1"></i> {{ $history->verification_method }}
                            </span>
                        </div>

                        <div class="text-xs text-slate-600 dark:text-zinc-300 space-y-1">
                            <p><strong>Property Owner:</strong> {{ $history->owner->name ?? 'N/A' }} ({{ $history->owner->registration_number ?? 'N/A' }})</p>
                            @if($history->finder)
                                <p><strong>Found / Turned In By:</strong> {{ $history->finder->name }} ({{ $history->finder->registration_number }})</p>
                            @endif
                            <p class="text-slate-500 italic">"{{ $history->remarks }}"</p>
                        </div>

                        <div class="pt-2 border-t border-slate-200 dark:border-zinc-700 text-[10px] text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-clock"></i>
                            <span>Handed Over & Verified on {{ $history->recovery_date->format('F d, Y \a\t H:i A') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800">
            {{ $histories->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h4 class="font-bold text-slate-700 dark:text-white text-base">No recovery records logged yet</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">When properties are verified and released via QR scanner, recovery log history will be generated here.</p>
        </div>
    @endif
</div>
@endsection
