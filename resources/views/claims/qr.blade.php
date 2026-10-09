@extends('layouts.app')

@section('title', 'Property Release QR Code Pass')

@section('content')
<div class="max-w-xl mx-auto space-y-6 text-center">
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center justify-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <a href="{{ route('claims.index') }}" class="hover:underline">Claims</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">QR Code Pass</span>
    </nav>
    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Property Release QR Code Pass</h1>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-8 shadow-xl space-y-6">
        <div class="inline-block px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-xs font-bold uppercase tracking-wider">
            <i class="fa-solid fa-circle-check mr-1"></i> Ownership Claim Verified & Approved
        </div>

        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $item->item_name ?? 'Property Item' }}</h2>
            <p class="text-xs text-slate-400">Claimant: <strong class="text-slate-700 dark:text-zinc-300">{{ Auth::user()->name }}</strong> ({{ Auth::user()->registration_number }})</p>
        </div>

        <!-- Rendered QR Code Container -->
        <div class="flex flex-col items-center justify-center py-4 bg-slate-50 dark:bg-zinc-800/80 rounded-2xl border border-slate-100 dark:border-zinc-800">
            <div id="qrcode" class="p-3 bg-white rounded-xl shadow-md"></div>
            <p class="text-xs font-mono font-bold text-blue-900 dark:text-blue-400 mt-4 tracking-wider uppercase">
                {{ $claim->qr_code_token }}
            </p>
            <span class="text-[10px] text-slate-400 mt-0.5">Official SJUT Verification Token</span>
        </div>

        <div class="text-xs text-slate-500 dark:text-zinc-400 space-y-2 text-left bg-blue-50/50 dark:bg-zinc-800/60 p-4 rounded-xl border border-blue-100 dark:border-zinc-700">
            <p class="font-bold text-slate-800 dark:text-white"><i class="fa-solid fa-circle-info text-blue-600 mr-1"></i> Release Instructions:</p>
            <ol class="list-decimal list-inside space-y-1 text-[11px]">
                <li>Present this digital QR pass on your phone or printed card at the <strong>SJUT Security Office</strong>.</li>
                <li>The Security Officer will scan the QR code to verify your authorization.</li>
                <li>Your property will be handed over, and an official recovery record will be logged.</li>
            </ol>
        </div>

        <div class="flex justify-center gap-3 pt-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 rounded-xl text-xs font-semibold transition-all flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print QR Pass
            </button>
            <a href="{{ route('claims.index') }}" class="px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white rounded-xl text-xs font-semibold transition-all">
                Back to Claims
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $claim->qr_code_token }}",
            width: 180,
            height: 180,
            colorDark : "#0f172a",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });
</script>
@endpush
@endsection
