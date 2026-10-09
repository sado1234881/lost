@extends('layouts.app')

@section('title', 'Property Tag Inspection — ' . $qr->reference_number)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    
    <!-- Top Breadcrumb -->
    <nav class="text-xs text-slate-400 dark:text-zinc-500 flex items-center justify-center gap-1.5 uppercase font-semibold tracking-wide">
        <a href="{{ route('landing') }}" class="hover:underline">SJUT Lost & Found</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span>Property Tag Verification</span>
    </nav>

    <!-- Main Public Inspection Card -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 md:p-8 shadow-xl space-y-6 text-center">

        <!-- Institution Crest Emblem -->
        <div class="inline-flex flex-col items-center gap-2">
            <div class="p-2 rounded-2xl bg-white shadow-md border border-purple-200 dark:border-zinc-700">
                <img src="{{ asset('images/sjut-logo.png') }}" alt="SJUT Logo" class="h-14 w-auto object-contain">
            </div>
            <span class="text-[10px] text-purple-700 dark:text-purple-400 font-extrabold tracking-widest uppercase block">St. John's University of Tanzania</span>
        </div>

        <div>
            <span class="font-mono text-xs font-bold text-slate-500 uppercase tracking-widest block mb-1">Property Tag Reference</span>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white font-mono">{{ $qr->reference_number }}</h1>
        </div>

        <!-- Tag & Item Status Banner -->
        @if($qr->status === 'inactive')
            <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-xs space-y-1">
                <div class="font-bold flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-circle-xmark text-red-600"></i> Inactive / Deactivated Property Tag
                </div>
                <p>This QR Code tag is currently inactive or has been replaced by Security Staff.</p>
            </div>
        @elseif($qr->status === 'recovered' || ($item && in_array($item->status, ['recovered', 'claimed'])))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs space-y-1">
                <div class="font-bold flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i> Property Recovered & Handed Over
                </div>
                <p>This item has already been successfully verified and returned to its rightful owner at the SJUT Security Office.</p>
            </div>
        @else
            <div class="p-4 rounded-2xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 text-purple-900 dark:text-purple-200 text-xs space-y-1">
                <div class="font-bold flex items-center justify-center gap-2 text-sm text-purple-800 dark:text-purple-300">
                    <i class="fa-solid fa-shield-halved text-amber-500"></i> Deposited at SJUT Security Desk
                </div>
                <p>This physical item has been registered in the official SJUT Lost & Found property recovery system.</p>
            </div>
        @endif

        <!-- Safe Item Details Grid -->
        <div class="bg-slate-50 dark:bg-zinc-800/60 rounded-2xl p-5 border border-slate-200 dark:border-zinc-700/60 text-left space-y-3">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Item Name</span>
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-white">{{ $item->item_name }}</h3>
                </div>
                <span class="px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-300 text-xs font-bold uppercase">
                    {{ $item->category->category_name ?? 'General' }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Storage Location</span>
                    <span class="font-semibold text-slate-800 dark:text-zinc-200"><i class="fa-solid fa-warehouse text-purple-600 mr-1"></i> {{ $item->storage_location ?? 'Security Office Desk' }}</span>
                </div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Date Registered</span>
                    <span class="font-semibold text-slate-800 dark:text-zinc-200"><i class="fa-solid fa-calendar text-blue-600 mr-1"></i> {{ $item->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            @if($item->color)
                <div class="text-xs">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Color / Primary Shade</span>
                    <span class="font-semibold text-slate-800 dark:text-zinc-200">{{ $item->color }}</span>
                </div>
            @endif

            <div class="text-xs pt-1 border-t border-slate-200 dark:border-zinc-700">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">General Specifications</span>
                <p class="text-slate-600 dark:text-zinc-300 leading-relaxed">{{ $item->description }}</p>
            </div>
        </div>

        <!-- System Action Buttons -->
        <div class="space-y-3 pt-2">
            @if($qr->status === 'active' && !in_array($item->status, ['recovered', 'claimed']))
                @guest
                    <a href="{{ route('login') }}" class="w-full py-3.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl text-xs shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                        <span>Is This Your Lost Item? — Sign In to Claim</span>
                    </a>
                @else
                    <a href="{{ route('found-items.show', $item->id) }}" class="w-full py-3.5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl text-xs shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                        <span>Is This Your Lost Item? — Submit Ownership Claim</span>
                    </a>
                @endguest
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('lost-items.create') }}" class="py-3 px-4 bg-purple-900 hover:bg-purple-800 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-amber-400"></i> Report Similar Lost Item
                </a>
                <a href="tel:+255262390044" class="py-3 px-4 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 text-slate-700 dark:text-zinc-200 font-bold rounded-xl text-xs transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-phone text-emerald-600"></i> Security Desk: +255 26 239 0044
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
