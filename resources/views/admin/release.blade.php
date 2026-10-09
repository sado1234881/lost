@extends('layouts.app')

@section('title', 'Admin QR Property Release Verification')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <a href="{{ route('claims.index') }}" class="hover:underline">Claims</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">QR Release Terminal</span>
    </nav>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 md:p-8 shadow-xl">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Security Property Release Terminal</h1>
            <p class="text-xs text-slate-400 mt-1">Scan the owner's QR Code pass or enter the release token manually to complete item handover.</p>
        </div>

        <!-- Camera Scanner Section -->
        <div class="mb-6 p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700 text-center">
            <div id="qr-reader" class="w-full max-w-sm mx-auto overflow-hidden rounded-xl"></div>
            <p class="text-[11px] text-slate-400 mt-2"><i class="fa-solid fa-camera mr-1"></i> Position the QR code pass in front of your camera to scan automatically.</p>
        </div>

        <div class="relative my-6 text-center">
            <hr class="border-slate-100 dark:border-zinc-800">
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-zinc-900 px-3 text-xs text-slate-400 font-semibold uppercase">Or Enter Token Manually</span>
        </div>

        <!-- Form input for manual token verification -->
        <form action="{{ route('admin.verify-release') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">QR Release Code Token *</label>
                <div class="relative">
                    <i class="fa-solid fa-barcode absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="qr_code_token_input" name="qr_code_token" required placeholder="e.g. CLAIM-A1B2C3D4E5F6"
                        class="w-full pl-10 pr-4 py-3 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white uppercase tracking-wider">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Security Officer Release Remarks (Optional)</label>
                <textarea name="remarks" rows="2" placeholder="e.g. Verified student identity card and physical signature recorded at desk."
                    class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"></textarea>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-emerald-700 hover:bg-emerald-600 text-white font-semibold rounded-xl text-sm shadow-md transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-check"></i> Authorize & Release Property
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Html5QrcodeScanner !== 'undefined') {
            function onScanSuccess(decodedText, decodedResult) {
                document.getElementById('qr_code_token_input').value = decodedText;
                alert('QR Code Scanned successfully: ' + decodedText);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                { fps: 10, qrbox: { width: 220, height: 220 } },
                /* verbose= */ false
            );
            html5QrcodeScanner.render(onScanSuccess);
        }
    });
</script>
@endpush
@endsection
