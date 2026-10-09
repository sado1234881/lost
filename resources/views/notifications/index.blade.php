@extends('layouts.app')

@section('title', 'System Alerts')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <nav class="text-xs text-slate-400 dark:text-zinc-500 mb-1 flex items-center gap-1.5 uppercase font-semibold tracking-wide">
            <a href="{{ route('workspace') }}" class="hover:underline">My Workspace</a>
            <i class="fa-solid fa-angle-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-zinc-400">System Alerts</span>
        </nav>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">System Alerts & Notifications</h1>
    </div>

    @if(Auth::user()->unreadNotificationsCount() > 0)
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
                <i class="fa-solid fa-check-double"></i> Mark All Alerts as Read
            </button>
        </form>
    @endif
</div>

<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notif)
                <div class="p-4 rounded-xl border {{ $notif->is_read ? 'bg-slate-50/50 dark:bg-zinc-800/40 border-slate-100 dark:border-zinc-800' : 'bg-blue-50/40 dark:bg-blue-950/20 border-blue-200 dark:border-blue-900' }} flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $notif->type === 'claim' ? 'bg-emerald-100 text-emerald-700' : ($notif->type === 'match' ? 'bg-purple-100 text-purple-700' : ($notif->type === 'rejection' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) }} flex items-center justify-center text-base shrink-0 mt-0.5">
                            <i class="fa-solid {{ $notif->type === 'claim' ? 'fa-file-signature' : ($notif->type === 'match' ? 'fa-link' : ($notif->type === 'rejection' ? 'fa-triangle-exclamation' : 'fa-bell')) }}"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-2">
                                {{ $notif->title }}
                                @if(!$notif->is_read)
                                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded font-extrabold uppercase bg-blue-600 text-white">NEW ALERT</span>
                                @endif
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-zinc-300 leading-relaxed">{{ $notif->message }}</p>
                            <span class="text-[10px] text-slate-400 block">{{ $notif->created_at->diffForHumans() }} ({{ $notif->created_at->format('M d, Y H:i') }})</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0 self-end md:self-center">
                        @if($notif->action_url)
                            <a href="{{ $notif->action_url }}" class="px-3 py-1.5 rounded-lg bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs shadow-sm transition-colors flex items-center gap-1.5">
                                <span>Open Target Record</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        @endif

                        @if(!$notif->is_read)
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-slate-700 dark:hover:text-zinc-300">
                                    Mark Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-blue-50 dark:bg-zinc-800 text-blue-900 dark:text-blue-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-bell-slash"></i>
            </div>
            <h4 class="font-bold text-slate-700 dark:text-white text-base">No active alerts</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">You currently have no unread system alerts or notification events.</p>
        </div>
    @endif
</div>
@endsection
