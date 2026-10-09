@extends('layouts.app')

@section('title', 'Staff Claims Review Queue')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <a href="{{ route('staff.index') }}" class="hover:underline">Staff Portal</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Claims Queue</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Ownership Claims Verification Queue</h1>
    </div>
</div>

<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 dark:bg-zinc-800 text-slate-500 uppercase font-bold text-[10px]">
                <tr>
                    <th class="p-3">Claim ID</th>
                    <th class="p-3">Claimant Student</th>
                    <th class="p-3">Item Details</th>
                    <th class="p-3">Claim Date</th>
                    <th class="p-3">Proof Excerpt</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($claims as $claim)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/40">
                        <td class="p-3 font-mono font-bold text-slate-700 dark:text-zinc-300">#CLAIM-{{ $claim->id }}</td>
                        <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $claim->user->name ?? 'N/A' }} ({{ $claim->user->registration_number ?? 'N/A' }})</td>
                        <td class="p-3 font-semibold text-slate-700 dark:text-zinc-300">{{ $claim->item->item_name ?? 'Item Record' }}</td>
                        <td class="p-3 text-slate-500">{{ $claim->claim_date->format('M d, Y') }}</td>
                        <td class="p-3 text-slate-500 max-w-xs truncate">{{ Str::limit($claim->proof_of_ownership, 40) }}</td>
                        <td class="p-3 text-right">
                            <a href="{{ route('claims.show', $claim->id) }}" class="px-3 py-1 rounded bg-blue-900 text-white font-bold hover:bg-blue-800">
                                Verify Proof &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400 text-xs">No pending claims awaiting verification.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $claims->links() }}</div>
</div>
@endsection
