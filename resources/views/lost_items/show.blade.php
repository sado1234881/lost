@extends('layouts.app')

@section('title', $lostItem->item_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <a href="{{ route('lost-items.index') }}" class="hover:underline">Lost Properties</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">Report #LF-L{{ str_pad($lostItem->id, 4, '0', STR_PAD_LEFT) }}</span>
    </nav>

    <!-- FROZEN LOCK WARNING BANNER -->
    @if($lostItem->is_frozen)
        <div class="p-4 rounded-2xl bg-purple-500/10 border border-purple-500/30 text-purple-900 dark:text-purple-200 text-xs md:text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <span class="font-extrabold text-purple-700 dark:text-purple-300 block uppercase tracking-wide">REPORT ADMINISTRATIVELY FROZEN</span>
                    <p class="text-xs text-purple-800 dark:text-purple-300/80">Reason: {{ $lostItem->freeze_reason ?? 'Under administrative investigation.' }}</p>
                </div>
            </div>
            @if(Auth::user()->isAdmin())
                <form action="{{ route('lost-items.unfreeze', $lostItem->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-purple-700 hover:bg-purple-600 text-white text-xs font-bold shadow transition-colors">
                        Unfreeze Record
                    </button>
                </form>
            @endif
        </div>
    @endif

    <!-- REJECTION REASON BANNER -->
    @if($lostItem->approval_status === 'rejected')
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-900 dark:text-red-200 text-xs md:text-sm flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-xmark text-xl text-red-600 shrink-0"></i>
            <div>
                <span class="font-extrabold text-red-700 dark:text-red-300 block uppercase">Report Rejected by Security Admin</span>
                <p class="text-xs text-red-800 dark:text-red-300/80">Reason: {{ $lostItem->rejection_reason }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-zinc-800">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 uppercase">
                        <i class="fa-solid {{ $lostItem->category->icon ?? 'fa-box' }} mr-1"></i> {{ $lostItem->category->category_name }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $lostItem->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : ($lostItem->approval_status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300') }}">
                        Approval Status: {{ str_replace('_', ' ', $lostItem->approval_status) }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $lostItem->item_name }}</h1>
            </div>

            <!-- Administrative Action Toolbar -->
            <div class="flex items-center gap-2 flex-wrap">
                @if((Auth::user()->isAdmin() || Auth::user()->isStaff()) && $lostItem->approval_status === 'pending_review')
                    <form action="{{ route('lost-items.approve', $lostItem->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-check"></i> Approve Report
                        </button>
                    </form>

                    <button onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-xmark"></i> Reject Report
                    </button>
                @endif

                @if(Auth::user()->isAdmin() && !$lostItem->is_frozen)
                    <button onclick="document.getElementById('freeze-modal').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-purple-700 hover:bg-purple-600 text-white text-xs font-bold shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-lock"></i> Freeze Item
                    </button>
                @endif

                @if(! $lostItem->is_frozen && (Auth::user()->isAdmin() || Auth::id() === $lostItem->user_id))
                    <form action="{{ route('lost-items.destroy', $lostItem->id) }}" method="POST" onsubmit="return confirm('Delete this lost report?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-xs font-bold rounded-xl transition-colors">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Info Panel -->
            <div class="md:col-span-2 space-y-6">
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description & Characteristics</h3>
                    <p class="text-sm text-slate-700 dark:text-zinc-300 leading-relaxed bg-slate-50 dark:bg-zinc-800/60 p-4 rounded-xl border border-slate-100 dark:border-zinc-800 whitespace-pre-line">
                        {{ $lostItem->description }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase block">Location Lost</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5 mt-1">
                            <i class="fa-solid fa-location-dot text-amber-500"></i> {{ $lostItem->location_lost }}
                        </span>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase block">Date Reported Lost</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5 mt-1">
                            <i class="fa-solid fa-calendar text-blue-500"></i> {{ $lostItem->date_lost->format('F d, Y') }}
                        </span>
                    </div>
                </div>

                <!-- POTENTIAL MATCHES SECTION -->
                @if(isset($potentialMatches) && $potentialMatches->count() > 0)
                    <div class="p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i> Potential Matching Found Property Discovered!
                            </h3>
                            <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-extrabold text-[10px] uppercase">
                                {{ $potentialMatches->count() }} Matches
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach($potentialMatches as $match)
                                <div class="p-3.5 rounded-xl bg-white dark:bg-zinc-900 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between gap-4">
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900 dark:text-white">{{ $match->item_name }}</h4>
                                        <p class="text-[11px] text-slate-500 dark:text-zinc-400">Stored at: <strong>{{ $match->storage_location }}</strong></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('found-items.show', $match->id) }}" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                            View Match &rarr;
                                        </a>
                                        @if($match->user_id)
                                            <a href="{{ route('messages.index', ['user' => $match->user_id, 'report_type' => 'lost_item', 'report_id' => $lostItem->id]) }}" class="px-3 py-1.5 rounded-lg bg-blue-900 text-white text-xs font-bold hover:bg-blue-800 transition-colors">
                                                <i class="fa-solid fa-comments"></i> Chat
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Reporter Info -->
                <div class="p-4 rounded-xl bg-blue-50/50 dark:bg-zinc-800/80 border border-blue-100 dark:border-zinc-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-blue-900 dark:text-blue-300 uppercase tracking-wider">Reported By Owner</h4>
                        @if(Auth::id() !== $lostItem->user_id)
                            <a href="{{ route('messages.index', ['user' => $lostItem->user_id, 'report_type' => 'lost_item', 'report_id' => $lostItem->id]) }}" class="px-3 py-1 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-[11px] font-bold flex items-center gap-1.5 transition-colors shadow-sm">
                                <i class="fa-solid fa-comments"></i> Direct Message Owner
                            </a>
                        @endif
                    </div>
                    <div class="text-xs text-slate-700 dark:text-zinc-300 space-y-1">
                        <p><strong>Name:</strong> {{ $lostItem->user->name }}</p>
                        <p><strong>Registration Number:</strong> {{ $lostItem->user->registration_number ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $lostItem->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Photo & Actions Sidebar -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Item Photo</h3>
                    @if($lostItem->image)
                        <img src="{{ asset('storage/' . $lostItem->image) }}" alt="{{ $lostItem->item_name }}" class="w-full h-48 object-cover rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm">
                    @else
                        <div class="w-full h-48 bg-slate-100 dark:bg-zinc-800 rounded-xl border border-slate-200 dark:border-zinc-800 flex flex-col items-center justify-center text-slate-400">
                            <i class="fa-solid {{ $lostItem->category->icon ?? 'fa-image' }} text-3xl mb-2"></i>
                            <span class="text-xs">No Image Provided</span>
                        </div>
                    @endif
                </div>

                <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 p-4 rounded-xl text-xs text-amber-800 dark:text-amber-300 space-y-3">
                    <p class="font-bold"><i class="fa-solid fa-lightbulb"></i> Found This Item on Campus?</p>
                    <p>If you found this item, log a Found Property Report or hand it over to the Security Desk.</p>
                    <a href="{{ route('found-items.create') }}" class="w-full py-2 px-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg text-xs transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-hand-holding-hand"></i> Log Found Item Report
                    </a>
                </div>
            </div>
        </div>

        <!-- CONTEXTUAL NEXT ACTION TOOLBAR -->
        <div class="pt-6 border-t border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">What would you like to do next?</span>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('found-items.index') }}" class="px-3 py-1.5 rounded-xl bg-purple-100 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 text-xs font-bold hover:bg-purple-200 transition-colors">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> View Found Directory
                </a>
                <a href="{{ route('notifications.index') }}" class="px-3 py-1.5 rounded-xl bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-xs font-bold hover:bg-blue-200 transition-colors">
                    <i class="fa-solid fa-bell mr-1"></i> Check System Alerts
                </a>
                <a href="{{ route('workspace') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 text-xs font-bold hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-briefcase mr-1"></i> Go to My Workspace
                </a>
            </div>
        </div>
    </div>
</div>

<!-- REJECT REASON MODAL -->
<div id="reject-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl w-full max-w-md shadow-2xl p-6 relative">
        <button onclick="document.getElementById('reject-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
        <h3 class="font-bold text-base text-slate-900 dark:text-white mb-2">Reject Report</h3>
        <p class="text-xs text-slate-400 mb-4">Provide a clear rejection reason. The user will receive an instant In-System Notification.</p>
        <form action="{{ route('lost-items.reject', $lostItem->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Rejection Reason</label>
                <textarea name="rejection_reason" required rows="3" class="w-full p-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-600" placeholder="e.g. Incomplete description. Please update missing details..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold text-xs hover:bg-red-500">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<!-- FREEZE MODAL -->
<div id="freeze-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl w-full max-w-md shadow-2xl p-6 relative">
        <button onclick="document.getElementById('freeze-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
        <h3 class="font-bold text-base text-slate-900 dark:text-white mb-2">Freeze Report Record</h3>
        <p class="text-xs text-slate-400 mb-4">Freezing locks this report against user edits, deletions, or status changes.</p>
        <form action="{{ route('lost-items.freeze', $lostItem->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Freeze Reason / Note</label>
                <textarea name="freeze_reason" required rows="3" class="w-full p-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Under administrative investigation for conflicting ownership claims..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('freeze-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-purple-700 text-white font-bold text-xs hover:bg-purple-600">Freeze Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
