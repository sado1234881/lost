@extends('layouts.app')

@section('title', $foundItem->item_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <a href="{{ route('found-items.index') }}" class="hover:underline">Found Properties</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">Record #LF-F{{ str_pad($foundItem->id, 4, '0', STR_PAD_LEFT) }}</span>
    </nav>

    <!-- FROZEN LOCK WARNING BANNER -->
    @if($foundItem->is_frozen)
        <div class="p-4 rounded-2xl bg-purple-500/10 border border-purple-500/30 text-purple-900 dark:text-purple-200 text-xs md:text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <span class="font-extrabold text-purple-700 dark:text-purple-300 block uppercase tracking-wide">ITEM RECORD ADMINISTRATIVELY FROZEN</span>
                    <p class="text-xs text-purple-800 dark:text-purple-300/80">Reason: {{ $foundItem->freeze_reason ?? 'Under administrative custody hold.' }}</p>
                </div>
            </div>
            @if(Auth::user()->isAdmin())
                <form action="{{ route('found-items.unfreeze', $foundItem->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-purple-700 hover:bg-purple-600 text-white text-xs font-bold shadow transition-colors">
                        Unfreeze Record
                    </button>
                </form>
            @endif
        </div>
    @endif

    <!-- REJECTION REASON BANNER -->
    @if($foundItem->approval_status === 'rejected')
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-900 dark:text-red-200 text-xs md:text-sm flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-xmark text-xl text-red-600 shrink-0"></i>
            <div>
                <span class="font-extrabold text-red-700 dark:text-red-300 block uppercase">Submission Rejected by Security Desk</span>
                <p class="text-xs text-red-800 dark:text-red-300/80">Reason: {{ $foundItem->rejection_reason }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-zinc-800">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-800 uppercase">
                        <i class="fa-solid {{ $foundItem->category->icon ?? 'fa-box' }} mr-1"></i> {{ $foundItem->category->category_name }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $foundItem->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : ($foundItem->approval_status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300') }}">
                        Approval Status: {{ str_replace('_', ' ', $foundItem->approval_status) }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $foundItem->item_name }}</h1>
            </div>

            <!-- Administrative Action Toolbar -->
            <div class="flex items-center gap-2 flex-wrap">
                @if((Auth::user()->isAdmin() || Auth::user()->isStaff()) && $foundItem->approval_status === 'pending_review')
                    <form action="{{ route('found-items.approve', $foundItem->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-check"></i> Approve Record
                        </button>
                    </form>

                    <button onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-xmark"></i> Reject Record
                    </button>
                @endif

                @if(Auth::user()->isAdmin() && !$foundItem->is_frozen)
                    <button onclick="document.getElementById('freeze-modal').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-purple-700 hover:bg-purple-600 text-white text-xs font-bold shadow-md transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-lock"></i> Freeze Item
                    </button>
                @endif

                @if(! $foundItem->is_frozen && (Auth::user()->isAdmin() || Auth::id() === $foundItem->user_id))
                    <form action="{{ route('found-items.destroy', $foundItem->id) }}" method="POST" onsubmit="return confirm('Delete this found item record?')">
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
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description & Custody Details</h3>
                    <p class="text-sm text-slate-700 dark:text-zinc-300 leading-relaxed bg-slate-50 dark:bg-zinc-800/60 p-4 rounded-xl border border-slate-100 dark:border-zinc-800 whitespace-pre-line">
                        {{ $foundItem->description }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase block">Location Found</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5 mt-1">
                            <i class="fa-solid fa-location-dot text-purple-500"></i> {{ $foundItem->location_found }}
                        </span>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase block">Date Found</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5 mt-1">
                            <i class="fa-solid fa-calendar text-blue-500"></i> {{ $foundItem->date_found->format('F d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900 space-y-1">
                    <span class="text-[11px] font-semibold text-emerald-800 dark:text-emerald-400 uppercase block">Current Storage & Custody Office</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-warehouse text-emerald-600"></i> {{ $foundItem->storage_location }}
                    </span>
                </div>

                <!-- POTENTIAL MATCHES SECTION -->
                @if(isset($potentialMatches) && $potentialMatches->count() > 0)
                    <div class="p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i> Potential Matching Lost Item Reports Identified!
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
                                        <p class="text-[11px] text-slate-500 dark:text-zinc-400">Reported Lost by: <strong>{{ $match->user->name ?? 'User' }}</strong></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('lost-items.show', $match->id) }}" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                            View Match &rarr;
                                        </a>
                                        @if($match->user_id)
                                            <a href="{{ route('messages.index', ['user' => $match->user_id, 'report_type' => 'found_item', 'report_id' => $foundItem->id]) }}" class="px-3 py-1.5 rounded-lg bg-blue-900 text-white text-xs font-bold hover:bg-blue-800 transition-colors">
                                                <i class="fa-solid fa-comments"></i> Chat
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Claim Form (If not frozen and status reported) -->
                @if(!$foundItem->is_frozen && $foundItem->status !== 'recovered' && $foundItem->status !== 'closed')
                    <div class="border-t border-slate-100 dark:border-zinc-800 pt-6">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2"><i class="fa-solid fa-shield-halved text-blue-600 mr-2"></i> Is this your property?</h3>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mb-4">Submit specific proof of ownership below. Security staff will review your proof and issue a QR Code release token upon verification.</p>

                        <form action="{{ route('claims.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="item_type" value="found_item">
                            <input type="hidden" name="item_id" value="{{ $foundItem->id }}">

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Proof of Ownership & Specific Identifiers *</label>
                                <textarea name="proof_of_ownership" rows="4" required placeholder="Provide specific details that confirm this is yours (e.g. phone IMEI/passcode pattern, receipt number, card registration, inner bag contents, serial number)..."
                                    class="w-full px-4 py-2.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"></textarea>
                            </div>

                            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-6 rounded-xl text-sm shadow-md transition-all flex items-center gap-2">
                                <i class="fa-solid fa-file-signature"></i> Submit Ownership Claim
                            </button>
                        </form>
                    </div>
                @elseif($foundItem->is_frozen)
                    <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-950/20 text-purple-700 dark:text-purple-300 text-xs font-bold text-center">
                        This item is currently frozen by Security Administration. Ownership claims cannot be submitted at this time.
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-500 text-xs font-semibold text-center">
                        This item has already been verified and released to its owner.
                    </div>
                @endif
            </div>

            <!-- Right Photo & Finder Sidebar -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Item Photo</h3>
                    @if($foundItem->image)
                        <img src="{{ asset('storage/' . $foundItem->image) }}" alt="{{ $foundItem->item_name }}" class="w-full h-48 object-cover rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm">
                    @else
                        <div class="w-full h-48 bg-slate-100 dark:bg-zinc-800 rounded-xl border border-slate-200 dark:border-zinc-800 flex flex-col items-center justify-center text-slate-400">
                            <i class="fa-solid {{ $foundItem->category->icon ?? 'fa-image' }} text-3xl mb-2"></i>
                            <span class="text-xs">No Photo Uploaded</span>
                        </div>
                    @endif
                </div>

                <div class="p-4 rounded-xl bg-blue-50 dark:bg-zinc-800/80 border border-blue-100 dark:border-zinc-700 text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-blue-900 dark:text-blue-300"><i class="fa-solid fa-user-tag mr-1"></i> Reported By Finder</h4>
                        @if($foundItem->user && Auth::id() !== $foundItem->user_id)
                            <a href="{{ route('messages.index', ['user' => $foundItem->user_id, 'report_type' => 'found_item', 'report_id' => $foundItem->id]) }}" class="px-2.5 py-1 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-[10px] font-bold flex items-center gap-1 transition-colors shadow-sm">
                                <i class="fa-solid fa-comments"></i> Chat Finder
                            </a>
                        @endif
                    </div>
                    <p class="text-slate-600 dark:text-zinc-300 text-[11px]"><strong>Name:</strong> {{ $foundItem->user->name ?? 'Security Desk' }}</p>
                </div>

                <!-- Staff Property QR Tag Management Card -->
                @if(Auth::user()->isStaff() || Auth::user()->isAdmin())
                    <div class="p-4 rounded-2xl bg-purple-50 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-800 text-xs space-y-3 shadow-sm">
                        <div class="flex items-center justify-between border-b border-purple-200 dark:border-purple-800 pb-2">
                            <h4 class="font-extrabold text-purple-900 dark:text-purple-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-qrcode text-amber-500"></i> Property QR Tag Control
                            </h4>
                            @if($foundItem->qrCode)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $foundItem->qrCode->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' }}">
                                    {{ $foundItem->qrCode->status }}
                                </span>
                            @endif
                        </div>

                        @if($foundItem->qrCode)
                            <div class="space-y-1 text-[11px]">
                                <p class="text-slate-600 dark:text-zinc-300"><strong>Tag Ref #:</strong> <span class="font-mono font-bold text-purple-900 dark:text-purple-300">{{ $foundItem->qrCode->reference_number }}</span></p>
                                <p class="text-slate-500 dark:text-zinc-400"><strong>Public Scans:</strong> {{ $foundItem->qrCode->scans_count }} times</p>
                            </div>

                            <div class="space-y-2 pt-1">
                                <a href="{{ route('staff.qr.print', $foundItem->qrCode->qr_token) }}" target="_blank" class="w-full py-2 px-3 bg-purple-900 hover:bg-purple-800 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-sm transition-colors">
                                    <i class="fa-solid fa-print text-amber-300"></i> Print Physical Item Tag
                                </a>

                                <div class="flex gap-2">
                                    <form action="{{ route('staff.qr.regenerate', $foundItem->qrCode->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Regenerate QR Tag? The old tag will be invalidated.')">
                                        @csrf
                                        <button type="submit" class="w-full py-1.5 px-2 bg-slate-200 dark:bg-zinc-800 hover:bg-slate-300 text-slate-700 dark:text-zinc-200 font-bold rounded-lg text-[11px] transition-colors">
                                            <i class="fa-solid fa-rotate-right mr-1"></i> Regenerate
                                        </button>
                                    </form>

                                    @if($foundItem->qrCode->status === 'active')
                                        <form action="{{ route('staff.qr.deactivate', $foundItem->qrCode->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Deactivate this QR Tag?')">
                                            @csrf
                                            <button type="submit" class="w-full py-1.5 px-2 bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-300 font-bold rounded-lg text-[11px] hover:bg-red-200 transition-colors">
                                                <i class="fa-solid fa-ban mr-1"></i> Deactivate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-[11px] text-slate-500 dark:text-zinc-400">No active physical QR Tag assigned to this item.</p>
                            <form action="{{ route('staff.qr.generate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_type" value="found_item">
                                <input type="hidden" name="item_id" value="{{ $foundItem->id }}">
                                <button type="submit" class="w-full py-2.5 px-3 bg-purple-900 hover:bg-purple-800 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-md transition-all">
                                    <i class="fa-solid fa-qrcode text-amber-400"></i> Generate Property QR Tag
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- CONTEXTUAL NEXT ACTION TOOLBAR -->
        <div class="pt-6 border-t border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">What would you like to do next?</span>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('lost-items.index') }}" class="px-3 py-1.5 rounded-xl bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-xs font-bold hover:bg-amber-200 transition-colors">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> View Lost Directory
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

<!-- REJECT MODAL -->
<div id="reject-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl w-full max-w-md shadow-2xl p-6 relative">
        <button onclick="document.getElementById('reject-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
        <h3 class="font-bold text-base text-slate-900 dark:text-white mb-2">Reject Found Property Entry</h3>
        <p class="text-xs text-slate-400 mb-4">Provide a clear reason for declining this entry.</p>
        <form action="{{ route('found-items.reject', $foundItem->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Rejection Reason</label>
                <textarea name="rejection_reason" required rows="3" class="w-full p-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-600" placeholder="e.g. Incomplete storage information or invalid entry..."></textarea>
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
        <h3 class="font-bold text-base text-slate-900 dark:text-white mb-2">Freeze Found Item Record</h3>
        <p class="text-xs text-slate-400 mb-4">Freezing locks this item against normal user claims, edits, or status changes.</p>
        <form action="{{ route('found-items.freeze', $foundItem->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Freeze Reason / Note</label>
                <textarea name="freeze_reason" required rows="3" class="w-full p-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-600" placeholder="e.g. Under security investigation for conflicting claim statements..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('freeze-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-purple-700 text-white font-bold text-xs hover:bg-purple-600">Freeze Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
