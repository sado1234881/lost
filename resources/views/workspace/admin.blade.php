@extends('layouts.app')

@section('title', 'Management Workspace & Security Control Center')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <span>SJUT Operational Governance</span>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Technical Control Center</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Management Workspace & Technical Control Center</h1>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('staff.pending') }}" class="bg-amber-600 hover:bg-amber-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left"></i> Pending Review Queue ({{ $totalPendingApproval }})
        </a>
        <a href="{{ route('admin.security.index') }}" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-shield-halved"></i> Institutional Security & Audit Center
        </a>
        <a href="{{ route('admin.release') }}" class="bg-purple-900 hover:bg-purple-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-qrcode"></i> QR Release Verification Terminal
        </a>
    </div>
</div>

<!-- Operational Metric Cards (Clickable Direct Actions) -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3 mb-6">
    <!-- Stat 1: Pending Approval -->
    <a href="{{ route('staff.pending') }}" class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 hover:border-amber-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-amber-700 dark:text-amber-300 uppercase tracking-wider block">Review Queue</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-amber-900 dark:text-amber-200">{{ $totalPendingApproval }}</span>
            <i class="fa-solid fa-clock text-amber-600 text-xs"></i>
        </div>
        <span class="text-[9px] text-amber-600 font-bold block mt-1">Pending Intake &rarr;</span>
    </a>

    <!-- Stat 2: Approved Reports -->
    <a href="{{ route('lost-items.index', ['approval_status' => 'approved']) }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-emerald-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Public Registry</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $approvedReports }}</span>
            <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
        </div>
        <span class="text-[9px] text-slate-400 group-hover:text-emerald-500 font-bold block mt-1">Active Records &rarr;</span>
    </a>

    <!-- Stat 3: Rejected Reports -->
    <a href="{{ route('lost-items.index', ['approval_status' => 'rejected']) }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-red-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Declined Entries</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-red-600 dark:text-red-400">{{ $rejectedReports }}</span>
            <i class="fa-solid fa-circle-xmark text-red-500 text-xs"></i>
        </div>
        <span class="text-[9px] text-slate-400 group-hover:text-red-500 font-bold block mt-1">Declined Archive &rarr;</span>
    </a>

    <!-- Stat 4: Frozen Items -->
    <a href="{{ route('found-items.index') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-purple-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Admin Holds</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-purple-600 dark:text-purple-400">{{ $frozenItems }}</span>
            <i class="fa-solid fa-lock text-purple-500 text-xs"></i>
        </div>
        <span class="text-[9px] text-slate-400 group-hover:text-purple-500 font-bold block mt-1">Frozen Records &rarr;</span>
    </a>

    <!-- Stat 5: Pending Claims -->
    <a href="{{ route('claims.index') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-blue-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Claims Queue</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-blue-600 dark:text-blue-400">{{ $totalPendingClaims }}</span>
            <i class="fa-solid fa-file-signature text-blue-500 text-xs"></i>
        </div>
        <span class="text-[9px] text-slate-400 group-hover:text-blue-500 font-bold block mt-1">Verify Claims &rarr;</span>
    </a>

    <!-- Stat 6: Resolved Cases -->
    <a href="{{ route('recovery.index') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-emerald-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Restored Items</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $resolvedCases }}</span>
            <i class="fa-solid fa-hand-holding-heart text-emerald-500 text-xs"></i>
        </div>
        <span class="text-[9px] text-slate-400 group-hover:text-emerald-500 font-bold block mt-1">Handover Archive &rarr;</span>
    </a>

    <!-- Stat 7: Registered Users -->
    <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-indigo-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Users</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $totalUsers }}</span>
            <i class="fa-solid fa-users text-indigo-500 text-xs"></i>
        </div>
        <span class="text-[9px] text-slate-400 group-hover:text-indigo-500 font-bold block mt-1">User Directory &rarr;</span>
    </a>

    <!-- Stat 8: Action Required Users -->
    <a href="{{ route('admin.security.index', ['tab' => 'users']) }}" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-amber-500 rounded-2xl p-3.5 shadow-sm transition-all group">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Directives</span>
        <div class="flex items-baseline justify-between mt-1">
            <span class="text-xl font-extrabold text-amber-600 dark:text-amber-400">{{ $pendingUserActions }}</span>
            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs"></i>
        </div>
        <span class="text-[9px] text-slate-400 group-hover:text-amber-500 font-bold block mt-1">Action Alerts &rarr;</span>
    </a>
</div>

<!-- Charts Row: Monthly Bar Chart & Category Pie Chart -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Bar Chart: Monthly Activity Trends (2 Columns) -->
    <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800 dark:text-white text-base">Institutional Recovery Velocity</h3>
            <span class="text-xs text-slate-400 uppercase font-semibold">6-Month Operational Analytics</span>
        </div>
        <div class="h-64 relative">
            <canvas id="monthlyActivityChart"></canvas>
        </div>
    </div>

    <!-- Category Pie Chart & Recovery Gauge (1 Column) -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-slate-800 dark:text-white text-base">Category Distribution</h3>
                <span class="text-xs text-slate-400 font-semibold">Classification Share</span>
            </div>
            <div class="h-44 relative mb-4">
                <canvas id="categoryPieChart"></canvas>
            </div>
        </div>

        <div class="border-t border-slate-100 dark:border-zinc-800 pt-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase block">Recovery Efficiency Rate</span>
                <span class="text-xs text-slate-400">Total Handed Over Property vs Total Reports</span>
            </div>
            <div class="text-right">
                <span class="text-2xl font-extrabold text-blue-900 dark:text-blue-400">{{ $recoveryRate }}%</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Items Quick Review Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Lost Items -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-amber-500"></i> Recent Lost Item Submissions
            </h3>
            <a href="{{ route('lost-items.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">View All &rarr;</a>
        </div>

        <div class="space-y-3">
            @foreach($recentLost as $item)
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $item->item_name }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase {{ $item->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : ($item->approval_status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300') }}">
                                {{ str_replace('_', ' ', $item->approval_status) }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400">By {{ $item->user->name ?? 'User' }} on {{ $item->created_at->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('lost-items.show', $item->id) }}" class="px-3 py-1 rounded bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-bold hover:bg-slate-300 transition-colors">
                        Inspect
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Found Items -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-hand-holding-hand text-emerald-500"></i> Recent Found Property Entries
            </h3>
            <a href="{{ route('found-items.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">View All &rarr;</a>
        </div>

        <div class="space-y-3">
            @foreach($recentFound as $item)
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-100 dark:border-zinc-800 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $item->item_name }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase {{ $item->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : ($item->approval_status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300') }}">
                                {{ str_replace('_', ' ', $item->approval_status) }}
                            </span>
                            @if($item->is_frozen)
                                <span class="text-[10px] px-2 py-0.5 rounded font-bold bg-purple-100 text-purple-700 uppercase">Frozen</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400">Stored at {{ $item->storage_location }}</p>
                    </div>
                    <a href="{{ route('found-items.show', $item->id) }}" class="px-3 py-1 rounded bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-bold hover:bg-slate-300 transition-colors">
                        Inspect
                    </a>
                </div>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Bar Chart setup
        const ctxBar = document.getElementById('monthlyActivityChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Lost Reports',
                        data: {!! json_encode($monthlyLost) !!},
                        backgroundColor: '#f59e0b',
                        borderRadius: 6
                    },
                    {
                        label: 'Found Logged',
                        data: {!! json_encode($monthlyFound) !!},
                        backgroundColor: '#a855f7',
                        borderRadius: 6
                    },
                    {
                        label: 'Recoveries Released',
                        data: {!! json_encode($monthlyRecovered) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        // Pie Chart setup
        const ctxPie = document.getElementById('categoryPieChart').getContext('2d');
        const catData = {!! json_encode($categoriesStats) !!};
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: catData.map(c => c.name),
                datasets: [{
                    data: catData.map(c => c.count),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6', '#f97316']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
@endsection
