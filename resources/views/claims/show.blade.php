@extends('layouts.app')

@section('title', 'Review Claim #' . $claim->id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-medium tracking-wide">
        <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <a href="{{ route('claims.index') }}" class="hover:underline">Claims Directory</a>
        <i class="fa-solid fa-angle-right text-[10px]"></i>
        <span class="text-slate-600 dark:text-zinc-400">Claim Details #{{ $claim->id }}</span>
    </nav>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-zinc-800">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">Claim Reference #{{ $claim->id }}</span>
                    @if($claim->claim_status === 'approved')
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 uppercase">Approved</span>
                    @elseif($claim->claim_status === 'released')
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300 uppercase">Property Handed Over</span>
                    @elseif($claim->claim_status === 'closed')
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700 dark:bg-zinc-700 dark:text-zinc-300 uppercase">Case Formally Closed</span>
                    @elseif($claim->claim_status === 'rejected')
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300 uppercase">Declined</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300 uppercase">Pending Review</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Ownership Claim for {{ $item->item_name ?? 'Property Item' }}</h1>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($claim->claim_status === 'approved' || $claim->claim_status === 'released' || $claim->claim_status === 'closed')
                    <a href="{{ route('claims.qr', $claim->id) }}" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-qrcode"></i> QR Release Token
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6">
            <!-- Main Proof Details (2 cols) -->
            <div class="md:col-span-2 space-y-6">

                <!-- POST-HANDOVER REVIEW BANNER -->
                @if(($claim->claim_status === 'released' || $claim->claim_status === 'closed') && !$claim->review && Auth::id() === $claim->user_id)
                    <div class="p-5 rounded-2xl bg-gradient-to-r from-blue-900 to-indigo-900 text-white space-y-3 shadow-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-sm flex items-center gap-2">
                                <i class="fa-solid fa-star text-amber-400"></i> How was your Lost & Found Experience?
                            </h3>
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-800 text-blue-200">Feedback Requested</span>
                        </div>
                        <p class="text-xs text-blue-100">Your property has been successfully handed over! Please take a moment to confirm outcome and leave feedback.</p>
                        <button onclick="document.getElementById('review-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs shadow transition-colors">
                            Submit Experience Review &rarr;
                        </button>
                    </div>
                @endif

                @if($claim->review)
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white uppercase flex items-center gap-1.5">
                                <i class="fa-solid fa-star text-amber-400"></i> User Recovery Feedback
                            </span>
                            <div class="flex text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $claim->review->rating ? 'text-amber-400' : 'text-slate-300' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-zinc-300 italic">"{{ $claim->review->comments ?? 'No additional comment left.' }}"</p>
                        <span class="text-[10px] text-slate-400 block">Returned Successfully: <strong>{{ $claim->review->returned_successfully ? 'YES' : 'NO' }}</strong></span>
                    </div>
                @endif

                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Claimant Submitted Proof of Ownership</h3>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 text-sm text-slate-700 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                        {{ $claim->proof_of_ownership }}
                    </div>
                </div>

                <!-- Claimant Info -->
                <div class="p-4 rounded-xl bg-blue-50/50 dark:bg-zinc-800/80 border border-blue-100 dark:border-zinc-700 space-y-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-blue-900 dark:text-blue-300 uppercase tracking-wider">Claimant Information</h4>
                        @if(Auth::id() !== $claim->user_id)
                            <a href="{{ route('messages.index', ['user' => $claim->user_id, 'report_type' => $claim->item_type, 'report_id' => $claim->item_id]) }}" class="px-3 py-1 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-[11px] font-semibold flex items-center gap-1.5 transition-colors shadow-sm">
                                <i class="fa-solid fa-comments"></i> Chat with Claimant
                            </a>
                        @endif
                    </div>
                    <div class="text-xs text-slate-700 dark:text-zinc-300 space-y-1">
                        <p><strong>Name:</strong> {{ $claim->user->name }}</p>
                        <p><strong>Registration Number:</strong> {{ $claim->user->registration_number ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $claim->user->email }}</p>
                        <p><strong>Phone:</strong> {{ $claim->user->phone_number ?? 'Not provided' }}</p>
                    </div>
                </div>

                @if($claim->admin_notes)
                    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 space-y-1">
                        <span class="text-[11px] font-bold text-amber-800 dark:text-amber-300 uppercase block">Security Admin Verification Notes</span>
                        <p class="text-xs text-slate-700 dark:text-zinc-300">{{ $claim->admin_notes }}</p>
                    </div>
                @endif

                <!-- Admin/Staff Verification Controls -->
                @if((Auth::user()->isAdmin() || Auth::user()->isStaff()) && $claim->claim_status === 'pending')
                    <div class="border-t border-slate-100 dark:border-zinc-800 pt-6 space-y-4">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white"><i class="fa-solid fa-user-shield text-blue-600 mr-2"></i> Security Verification Decision</h3>
                        <form action="{{ route('claims.status', $claim->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Verification Remarks / Notes</label>
                                <textarea name="admin_notes" rows="3" placeholder="Enter verification notes or reason for decision..."
                                    class="w-full px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"></textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit" name="claim_status" value="approved" class="flex-1 py-2.5 px-4 bg-emerald-700 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-check"></i> Approve Claim & Issue QR Pass
                                </button>
                                <button type="submit" name="claim_status" value="rejected" class="py-2.5 px-4 bg-rose-700 hover:bg-rose-600 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-xmark"></i> Decline Claim
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Target Property Overview -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Target Property Record</h3>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 space-y-3">
                        @if($item)
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $item->item_name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 line-clamp-3">{{ $item->description }}</p>

                            <div class="text-[11px] text-slate-400 space-y-1 pt-2 border-t border-slate-200 dark:border-zinc-700">
                                <p><i class="fa-solid fa-location-dot w-4"></i> Found: {{ $item->location_found ?? $item->location_lost }}</p>
                                <p><i class="fa-solid fa-warehouse w-4"></i> Storage: {{ $item->storage_location ?? 'N/A' }}</p>
                            </div>

                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="w-full h-32 object-cover rounded-lg border border-slate-200 dark:border-zinc-700">
                            @endif
                        @else
                            <p class="text-xs text-slate-400">Target property record unavailable.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- REVIEW FEEDBACK MODAL -->
<div id="review-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl w-full max-w-md shadow-2xl p-6 relative">
        <button onclick="document.getElementById('review-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
        <h3 class="font-bold text-base text-slate-900 dark:text-white mb-2">Rate Your Recovery Experience</h3>
        <p class="text-xs text-slate-400 mb-4">Confirm outcome and leave feedback for SJUT Security Administration.</p>
        
        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="claim_id" value="{{ $claim->id }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Rating (1 - 5 Stars)</label>
                <select name="rating" required class="w-full p-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white">
                    <option value="5">⭐⭐⭐⭐⭐ 5 - Excellent Experience</option>
                    <option value="4">⭐⭐⭐⭐ 4 - Good Service</option>
                    <option value="3">⭐⭐⭐ 3 - Satisfactory</option>
                    <option value="2">⭐⭐ 2 - Poor Service</option>
                    <option value="1">⭐ 1 - Unsatisfactory</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Was your property returned successfully?</label>
                <select name="returned_successfully" required class="w-full p-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white">
                    <option value="1">Yes, property successfully received in full</option>
                    <option value="0">No, issues encountered during handover</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Comments / Review Notes</label>
                <textarea name="comments" rows="3" class="w-full p-2.5 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Describe your experience with the Security Desk..."></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('review-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-zinc-800 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-900 text-white font-bold text-xs hover:bg-blue-800 shadow">Submit Feedback & Close Case</button>
            </div>
        </form>
    </div>
</div>
@endsection
