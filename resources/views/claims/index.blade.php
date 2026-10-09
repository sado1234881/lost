@extends('layouts.app')

@section('title', 'Ownership Claims')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Claims Directory</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Ownership Claims Directory</h1>
    </div>

    @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.release') }}" class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2 self-start md:self-auto">
            <i class="fa-solid fa-qrcode"></i> Property Release Scanner
        </a>
    @endif
</div>

<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
    @if($claims->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-zinc-800 text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        <th class="pb-3">Claim ID & Claimant</th>
                        <th class="pb-3">Target Property Item</th>
                        <th class="pb-3">Submission Date</th>
                        <th class="pb-3">Verification Status</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                    @foreach($claims as $claim)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="py-4">
                                <span class="font-bold text-slate-900 dark:text-white block">Claim #{{ $claim->id }}</span>
                                <span class="text-slate-500 dark:text-zinc-400 font-medium">{{ $claim->user->name }}</span>
                                <span class="text-[10px] text-slate-400 block">Reg: {{ $claim->user->registration_number }}</span>
                            </td>
                            <td class="py-4 font-semibold text-slate-800 dark:text-zinc-200">
                                {{ $claim->item->item_name ?? 'Property Record #' . $claim->item_id }}
                            </td>
                            <td class="py-4 text-slate-400">
                                {{ $claim->claim_date->format('M d, Y') }}
                            </td>
                            <td class="py-4">
                                @if($claim->claim_status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 uppercase">
                                        <i class="fa-solid fa-check mr-1"></i> Approved
                                    </span>
                                @elseif($claim->claim_status === 'released')
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 uppercase">
                                        <i class="fa-solid fa-hand-holding-hand mr-1"></i> Released
                                    </span>
                                @elseif($claim->claim_status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 uppercase">
                                        <i class="fa-solid fa-xmark mr-1"></i> Rejected
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 uppercase">
                                        <i class="fa-solid fa-clock mr-1"></i> Pending Review
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('claims.show', $claim->id) }}" class="bg-blue-50 hover:bg-blue-100 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-blue-900 dark:text-blue-400 text-xs px-3 py-1.5 rounded-lg font-semibold transition-colors inline-block">
                                    Review Details
                                </a>

                                @if($claim->claim_status === 'approved' || $claim->claim_status === 'released')
                                    <a href="{{ route('claims.qr', $claim->id) }}" class="bg-emerald-700 hover:bg-emerald-600 text-white text-xs px-3 py-1.5 rounded-lg font-semibold transition-colors inline-block">
                                        <i class="fa-solid fa-qrcode mr-1"></i> QR Pass
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800">
            {{ $claims->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <h4 class="font-bold text-slate-700 dark:text-white text-base">No ownership claims found</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">There are no submitted property ownership claims registered yet.</p>
        </div>
    @endif
</div>
@endsection
