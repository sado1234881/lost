@extends('layouts.app')

@section('title', 'Security & Audit Center')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">Security & Audit</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Admin Security & Audit Control Center</h1>
    </div>
</div>

<!-- 4 Audit KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Recorded Audit Events</span>
            <span class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $stats['total_audits'] }}</span>
            <span class="text-[11px] text-blue-600 font-bold block">Immutable Log</span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-zinc-800 text-blue-600 flex items-center justify-center text-lg">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">In-System Alerts Dispatched</span>
            <span class="text-2xl font-extrabold text-purple-700 dark:text-purple-400">{{ $stats['total_notifications'] }}</span>
            <span class="text-[11px] text-purple-600 font-bold block">In-Platform System</span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-zinc-800 text-purple-700 dark:text-purple-400 flex items-center justify-center text-lg">
            <i class="fa-solid fa-bell"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Pending / Unread Alerts</span>
            <span class="text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $stats['unread_notifications'] }}</span>
            <span class="text-[11px] text-amber-600 font-bold block">User Attention Queue</span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-zinc-800 text-amber-600 flex items-center justify-center text-lg">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Unverified Accounts</span>
            <span class="text-2xl font-extrabold text-slate-700 dark:text-slate-300">{{ $stats['unverified_accounts'] }}</span>
            <span class="text-[11px] text-slate-500 font-bold block">Restricted Access</span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-600 flex items-center justify-center text-lg">
            <i class="fa-solid fa-envelope-circle-check"></i>
        </div>
    </div>
</div>

<!-- Tab Navigation Header -->
<div class="flex items-center gap-2 border-b border-slate-200 dark:border-zinc-800 pb-2 mb-6 text-sm font-bold flex-wrap">
    <a href="{{ route('admin.security.index', ['tab' => 'audit']) }}" class="px-4 py-2 rounded-xl transition-colors {{ $tab === 'audit' ? 'bg-purple-900 text-white shadow-sm' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
        <i class="fa-solid fa-list-check mr-1.5"></i> Administrative Audit Trail
    </a>
    <a href="{{ route('admin.security.index', ['tab' => 'notifications']) }}" class="px-4 py-2 rounded-xl transition-colors {{ $tab === 'notifications' ? 'bg-purple-900 text-white shadow-sm' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
        <i class="fa-solid fa-bell mr-1.5"></i> In-System Notification Logs
    </a>
    <a href="{{ route('admin.security.index', ['tab' => 'users']) }}" class="px-4 py-2 rounded-xl transition-colors {{ $tab === 'users' ? 'bg-purple-900 text-white shadow-sm' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
        <i class="fa-solid fa-user-lock mr-1.5"></i> Account Verification & Compliance
    </a>
</div>

<!-- Tab Content -->
@if($tab === 'notifications')
    <!-- In-System Notification Logs Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">In-System Notification Log Index</h2>
            <span class="text-xs text-slate-500">Total: {{ $stats['total_notifications'] }} records</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-slate-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Alert ID</th>
                        <th class="p-3">Recipient User</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Title & Summary</th>
                        <th class="p-3">Read Status</th>
                        <th class="p-3">Dispatched Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($notifications as $notif)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/40">
                            <td class="p-3 font-mono text-slate-500">#NOTIF-{{ $notif->id }}</td>
                            <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $notif->user->name ?? 'System' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                                    @if($notif->type === 'match') bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300
                                    @elseif($notif->type === 'claim') bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300
                                    @elseif($notif->type === 'approval') bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300
                                    @elseif($notif->type === 'rejection') bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300
                                    @elseif($notif->type === 'freeze') bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300
                                    @else bg-slate-100 text-slate-800 dark:bg-zinc-800 dark:text-zinc-300 @endif">
                                    {{ $notif->type ?? 'alert' }}
                                </span>
                            </td>
                            <td class="p-3 text-slate-700 dark:text-zinc-200 max-w-md">
                                <div class="font-semibold">{{ $notif->title }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-zinc-400 truncate">{{ $notif->message }}</div>
                            </td>
                            <td class="p-3">
                                @if($notif->is_read)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400">
                                        <i class="fa-solid fa-check-double mr-1"></i> Read
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 animate-pulse">
                                        <i class="fa-solid fa-circle text-[7px] mr-1"></i> Unread
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-slate-400 whitespace-nowrap">{{ $notif->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400 text-xs">No in-system notifications recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $notifications->links() }}</div>
    </div>
@elseif($tab === 'users')
    <!-- User Verification & Action Compliance Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 dark:text-white">Unverified Accounts & Action Compliance Monitoring</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-slate-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">User ID</th>
                        <th class="p-3">Full Name</th>
                        <th class="p-3">Email Address</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Verification State</th>
                        <th class="p-3">Action Required</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($unverifiedUsers as $u)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/40">
                            <td class="p-3 font-mono text-slate-500">#USR-{{ $u->id }}</td>
                            <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $u->name }}</td>
                            <td class="p-3 text-slate-600 dark:text-zinc-300">{{ $u->email }}</td>
                            <td class="p-3 capitalize font-semibold">{{ $u->role }}</td>
                            <td class="p-3">
                                @if($u->email_verified_at)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase">Verified</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 uppercase">Unverified</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($u->action_required)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 uppercase">Action Required</span>
                                @else
                                    <span class="text-slate-400">Clear</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400 text-xs">All user accounts are fully verified and compliant.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@else
    <!-- Audit Trail Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 dark:text-white">System Administrative Audit Trail</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-800 text-slate-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Audit ID</th>
                        <th class="p-3">Operator</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Action Performed</th>
                        <th class="p-3">Target Entity</th>
                        <th class="p-3">IP Address</th>
                        <th class="p-3">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($auditLogs as $log)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/40">
                            <td class="p-3 font-mono text-slate-500">#AUD-{{ $log->id }}</td>
                            <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $log->user_name }}</td>
                            <td class="p-3 uppercase font-semibold text-slate-600 dark:text-zinc-400 text-[10px]">{{ $log->user_role }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px] uppercase bg-slate-100 dark:bg-zinc-800 text-blue-600 dark:text-blue-400">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-3 font-semibold text-slate-700 dark:text-zinc-300">{{ $log->target_type }} #{{ $log->target_id }}</td>
                            <td class="p-3 font-mono text-slate-400 text-[11px]">{{ $log->ip_address }}</td>
                            <td class="p-3 text-slate-400">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400 text-xs">No audit logs recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $auditLogs->links() }}</div>
    </div>
@endif
@endsection
