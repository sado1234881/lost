@extends('layouts.app')

@section('title', 'System User Guide & Operational Manual')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header Card -->
    <div class="p-8 rounded-3xl bg-gradient-to-r from-purple-950 via-indigo-950 to-slate-900 text-white shadow-xl border border-purple-800/40 relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-800/60 border border-purple-400/30 text-amber-300 text-xs font-extrabold uppercase tracking-wider">
                    <i class="fa-solid fa-book-open"></i> Institutional User Manual
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">How the Recovery System Works</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
                    Comprehensive operational workflow, report handling, verification guidelines, and safety policies for St. John's University of Tanzania.
                </p>
            </div>
            <a href="{{ route('guide.download') }}" class="px-6 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-xl shadow-amber-500/20 transition-all flex items-center gap-2.5 shrink-0 group">
                <i class="fa-solid fa-file-arrow-down text-base group-hover:translate-y-0.5 transition-transform"></i>
                <span>Download User Guide (PDF)</span>
            </a>
        </div>
    </div>

    <!-- 5-Step Process Summary -->
    <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-200 dark:border-zinc-800 p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-100 dark:border-zinc-800 pb-4">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-purple-700 dark:text-purple-400"></i>
                Standard 5-Step Recovery Workflow
            </h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">From property loss or discovery to verified institutional release at the Campus Security Desk.</p>
        </div>

        <div class="space-y-4">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 flex items-start gap-4">
                <span class="w-8 h-8 rounded-full bg-purple-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0">1</span>
                <div class="space-y-1">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Submit Report Immediately</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400">Log your lost item or found property with clear descriptions, campus location, date/time, and photo evidence.</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 flex items-start gap-4">
                <span class="w-8 h-8 rounded-full bg-blue-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0">2</span>
                <div class="space-y-1">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Security Verification & Public Registry</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400">Security personnel review submissions to verify authenticity before approving items to the campus-wide registry.</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 flex items-start gap-4">
                <span class="w-8 h-8 rounded-full bg-indigo-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0">3</span>
                <div class="space-y-1">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Automated Heuristic Matching</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400">The platform automatically analyzes records to suggest potential matches, notifying involved users via In-System Alerts.</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 flex items-start gap-4">
                <span class="w-8 h-8 rounded-full bg-amber-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0">4</span>
                <div class="space-y-1">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Ownership Claim & Digital QR Release Pass</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400">Submit unique identifying proof to claim property. Upon approval, receive a secure digital QR Release Token.</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 flex items-start gap-4">
                <span class="w-8 h-8 rounded-full bg-emerald-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0">5</span>
                <div class="space-y-1">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Physical Handover at Security Office</h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-400">Present your QR Release Pass and university ID at the SJUT Security Office to verify identity and collect the item.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reporting Specifics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-200 dark:border-zinc-800 p-6 shadow-sm space-y-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">How to Report a Lost Item</h3>
            <ul class="text-xs text-slate-600 dark:text-zinc-400 space-y-2 list-disc list-inside leading-relaxed">
                <li>Log in to your account and click <strong>Report Lost Item</strong>.</li>
                <li>Choose the category and describe the item's unique markings, model, or color.</li>
                <li>Specify the exact campus location and estimated date/time.</li>
                <li>Track matches and claim status directly in My Workspace.</li>
            </ul>
            <a href="{{ route('lost-items.create') }}" class="inline-flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline pt-2">
                Start Lost Report &rarr;
            </a>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-200 dark:border-zinc-800 p-6 shadow-sm space-y-4">
            <div class="w-10 h-10 rounded-xl bg-purple-900/10 text-purple-700 dark:text-purple-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-hand-holding-hand"></i>
            </div>
            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">How to Report a Found Item</h3>
            <ul class="text-xs text-slate-600 dark:text-zinc-400 space-y-2 list-disc list-inside leading-relaxed">
                <li>Click <strong>Report Found Item</strong> to register unattended property.</li>
                <li>Enter the location where found and custody status (in-hand or deposited).</li>
                <li>Surrender the item to the Campus Security Desk within 24-48 hours.</li>
                <li>Review claim submissions and assist in safe return.</li>
            </ul>
            <a href="{{ route('found-items.create') }}" class="inline-flex items-center gap-2 text-xs font-bold text-purple-700 dark:text-purple-400 hover:underline pt-2">
                Start Found Report &rarr;
            </a>
        </div>
    </div>

    <!-- Ownership Claim & Security Verification Protocol -->
    <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-slate-200 dark:border-zinc-800 p-8 shadow-sm space-y-6">
        <div class="border-b border-slate-100 dark:border-zinc-800 pb-4">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-qrcode text-purple-700 dark:text-purple-400"></i>
                Ownership Claims, QR Release Passes & Security Desk Protocol
            </h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Strict chain-of-custody protocols enforce fair and legitimate property restitution.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 space-y-2">
                <span class="font-extrabold text-amber-600 dark:text-amber-400 uppercase text-[10px] block">1. File Ownership Claim</span>
                <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Provide distinguishing features not visible in public photos (serial number, lockscreen wallpaper, engraving, secret marks).</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 space-y-2">
                <span class="font-extrabold text-purple-700 dark:text-purple-400 uppercase text-[10px] block">2. Security Desk Review</span>
                <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Campus Security Officers verify the claimant's student or staff credentials against university registry records.</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 space-y-2">
                <span class="font-extrabold text-emerald-600 dark:text-emerald-400 uppercase text-[10px] block">3. QR Scan & Handover</span>
                <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Present your digital QR release pass at the Security Office. The officer scans it to validate release and log restitution.</p>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-purple-50/50 dark:bg-purple-950/20 border border-purple-200/60 dark:border-purple-800/60 flex items-center justify-between flex-wrap gap-4 text-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 dark:text-white block">Campus Security Office Desk</span>
                    <span class="text-slate-500 dark:text-zinc-400 text-[11px]">Main Campus Administration Block • Mon–Fri 08:00–17:00 • Hotline: +255 26 239 0044</span>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold border border-emerald-300 dark:border-emerald-800">
                Official Campus Restitution Point
            </span>
        </div>
    </div>

    <!-- Offline Copy & Distribution Note -->
    <div class="p-6 rounded-2xl bg-purple-50 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center font-bold text-lg shrink-0">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Official Institutional Documentation</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400">Available above via the primary download button. For printed physical copies, visit the Campus Security Office Desk.</p>
            </div>
        </div>
        <a href="#top" class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-zinc-800 hover:bg-slate-300 text-slate-700 dark:text-zinc-300 font-semibold text-xs transition-colors flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-arrow-up text-[10px]"></i> Back to Top
        </a>
    </div>

</div>
@endsection
